# Ledger Architecture Decision

## Status
Accepted — 2026-09-08.

## Context
The tenant database runs two parallel general ledgers:

| System | Accounts table | Lines table | Live rows (verified) |
|---|---|---|---|
| A (operational) | `accounts` | `transaction_lines` | 50 accounts, 149 lines |
| B (accounting) | `chart_of_accounts` | `journal_entry_lines` | 0 accounts, 0 entries, 0 lines |

Every posting path in the app (`TransactionService`, `CashRegisterService`, `SaleRequestService`,
`PurchaseRequestService`, `FixedAssetService`, etc.) writes to system A. System B was intended to
be fed by `LedgerBridgeService`, but that service was only ever invoked from
`BackfillLedgerCommand`, which was never run against live data, and `chart_of_accounts` was never
seeded for this tenant. System B currently has no data source.

Every report under `app/Livewire/Admin/Reports/` already reads `transaction_lines` /
`Transaction` (verified by grep — zero reports reference `journal_entry_lines` or
`JournalEntryLine`). Reporting behavior today already depends entirely on system A.

`journal_entry_lines` carries `cost_center_id` and `project_id`; `transaction_lines` had no
equivalent. The contracting module's cost-center and project P&L design assumes journal entries,
so those columns cannot be dropped even though system B is not the source of truth.

## Decision
**System A (`accounts` / `transaction_lines`) is the single authoritative ledger.**

System B (`chart_of_accounts` / `journal_entries` / `journal_entry_lines`) becomes a **reporting
projection** derived from system A via `LedgerBridgeService`, not a second source of truth. All
balance, reversal, and integrity guarantees are enforced once, in `TransactionService`, against
system A. The bridge's only job is to translate an already-valid, already-balanced `Transaction`
into a `JournalEntry` for consumers that specifically need `chart_of_accounts` codes or
cost-center/project dimensions (e.g. future contracting P&L, GAAP-style trial balance/balance
sheet views).

### Why not the reverse (system B authoritative)
- System B has zero live rows; migrating 149 transaction lines' worth of operational logic
  (branch cash, cash register shifts, refunds, payroll, depreciation, etc.) to write against
  `chart_of_accounts` first would touch every service in the app for no functional gain.
- System A already enforces the balance invariant (`TransactionService::create()` rejects
  unbalanced line sets) and is the reversal-of-record (`TransactionService::reverse()`).
- Every existing report already reads system A; repointing them to an empty system B would break
  reporting today, not fix anything.

### Why not delete system B
- The contracting module (`ChartOfAccount`, `JournalEntry`, `JournalEntryLine`, `CostCenter`,
  `Project`) is designed around `journal_entry_lines.cost_center_id` / `project_id`, which system
  A has no equivalent for structurally (chart-of-accounts hierarchy, GAAP account types). Cost
  center and project P&L can only be built on system B.

## Consequences
- `chart_of_accounts` must be seeded for every tenant (new and existing) — see
  `database/seeders/Tenant/ChartOfAccountsSeeder.php` and `database/seeders/Tenant/DatabaseSeeder.php`,
  wired into tenant provisioning via `Jobs\SeedDatabase` in `TenancyServiceProvider`.
- `transaction_lines` gains `cost_center_id` and `project_id` so system A can carry those
  dimensions through to system B (migration
  `2026_09_08_210500_add_cost_center_and_project_to_transaction_lines.php`).
- `LedgerBridgeService::post()` is called synchronously from `TransactionService::create()`,
  inside the same `DB::transaction()`, after the balance guard passes. It throws rather than
  dropping unmapped lines or posting an unbalanced entry — a missing journal entry is a symptom
  to fix (map the account type, seed the COA code); a partial/unbalanced one is silent data
  corruption.
- Reports keep reading system A. No report changes are required by this decision (see Step 4
  audit in the accompanying PR/commit).
- `ledger:backfill` remains available for populating system B from historical system-A
  transactions, but defaults to `--dry-run` and must never be treated as a second write path for
  new transactions — new transactions are posted synchronously by `TransactionService::create()`.
