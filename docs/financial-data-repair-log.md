# Financial Data Repair Log — Prompt 09

## Command

`app/Console/Commands/Tenant/RepairFinancialDataCommand.php`
(`php artisan tenant:repair-financial-data {--tenant=} {--step=} {--dry-run=1} {--report-path=}`)

Repairs the defects verified in `mohaaseb_test.sql` (a specific tenant's live data), one
numbered step per invocation. `--dry-run=1` is the default and is safe: nothing is written,
only printed and saved to a markdown report under `storage/app/repair-reports/` (or
`--report-path`). Passing `--dry-run=0` is required to actually write, and only after the
backup below has been taken and an accountant has reviewed the corresponding dry-run report.

A companion read-only command, `app/Console/Commands/Tenant/FinancialHealthCheckCommand.php`
(`php artisan tenant:financial-health-check {--tenant=}`), is safe to schedule daily — it never
writes anything and exits non-zero when it finds an issue, for cron alerting.

## Backup procedure (run before ANY `--dry-run=0` execution)

This app is multi-tenant (stancl/tenancy) — each tenant has its own database. Back up the
**specific tenant database** you are about to repair, not the central database:

```bash
# Identify the tenant's database name first, e.g. via:
php artisan tinker --execute="echo App\Models\Tenant::find('<tenant-id>')->database()->getName();"

# Then dump it:
mysqldump --single-transaction --routines --triggers \
  -u <db_user> -p <tenant_database_name> \
  > backup_<tenant_database_name>_$(date +%Y%m%d_%H%M%S).sql
```

Keep the dump until the repair has been verified in production (re-run
`tenant:financial-health-check` and `tenant:audit-ledger` after each live step). Do not delete
the backup until the accountant has signed off on the corrected numbers.

## How to run a step

```bash
# 1. Dry run — always start here, review the printed report and the saved .md file
php artisan tenant:repair-financial-data --tenant=<id> --step=1 --dry-run=1

# 2. Take the backup above.

# 3. Only after accountant sign-off on the dry-run report:
php artisan tenant:repair-financial-data --tenant=<id> --step=1 --dry-run=0
```

Only one `--step` may be requested per invocation — the command rejects missing, out-of-range,
or multiple steps. Steps 5, 9, and 13 have no write path at all (by design — see checklist
below) and will always print "REQUIRES MANUAL DECISION — no write path implemented," regardless
of `--dry-run`.

All writes go through `TransactionService::create()` / `TransactionService::reverse()` — no raw
SQL against `transactions` or `transaction_lines`, and those tables are never deleted from,
only reversed. Every correcting entry's `description`/reversal `reason` names the repair and
step number, e.g. `"Repair step 1: reverse register open/close posted to equity"`.

## Defect checklist

| # | Defect | Repair approach | Status |
|---|---|---|---|
| 1 | Register open/close posted to equity (3 opening + 2 closing balance txns) | Reverse via contra-entry (`TransactionService::reverse()`) | pending — dry run only, awaiting accountant approval before write |
| 2 | Orphan register entries (`cash_registers` empty) | Report only — reversed by step 1 | pending — report-only, no write path |
| 3 | Duplicate control accounts (20× Checks Under Collection, 10× Issued Checks) | Delegates to `tenant:merge-duplicate-accounts` (prompt 02) | pending — dry run only, awaiting accountant approval before write |
| 4 | Split subsidiary ledgers (user 11 → accts 21/25/44; user 10 → accts 20/28) | Delegates to `tenant:merge-duplicate-accounts`; flags cross-branch cases for manual review | pending — dry run only, awaiting accountant approval before write |
| 5 | Transactions with zero lines (41,42,43,44,45,50) | Report only — per-row accountant decision | pending — report-only, no write path |
| 6 | Unposted purchases 8, 9 | Post missing inventory/AP entry via `TransactionService::create()` | pending — dry run only, awaiting accountant approval before write |
| 7 | Inventory GL ≠ sub-ledger (variance 296,471.00) | Recompute `stocks.total_value` on weighted-average basis; post one dated revaluation entry for the residual | pending — dry run only, awaiting accountant approval before write |
| 8 | Orphan GL for deleted expenses #2, #3 | Reverse orphan entries; added `Expense` model `deleting` guard that auto-reverses referencing transactions | pending — dry run only, awaiting accountant approval before write |
| 9 | Unposted accrued expense #9 (10,000, 2026-01-01) | Report only — post vs. leave unposted is an accountant call | pending — report-only, no write path |
| 10 | Prepaid expense #10 (1,000) posted straight to expense | Create `Prepaid Asset` account (new `AccountTypeEnum::PREPAID_ASSET`), reverse + repost reclassified; amortization schedule left as a TODO (no amortization engine exists yet) | pending — dry run only, awaiting accountant approval before write |
| 11 | Check on wrong side (FA-000003, txn 68, account 38 at −5,000) | Reverse and repost to Issued Checks | pending — dry run only, awaiting accountant approval before write |
| 12 | Missing depreciation (FA-000001, ~20,000 owed) | Delegates to `tenant:run-depreciation` (prompt 05) once per historical period from `depreciation_start_date` to now | pending — dry run only, awaiting accountant approval before write |
| 13 | Missing check records (FA-000003's two 5,000 payments) | Report only — reconstructing check numbers from a GL entry is guesswork | pending — report-only, no write path |
| 14 | Deleted check with surviving GL (check #2, txn 43) | Reverse; added `Check` model `forceDeleting` guard that blocks hard-delete outright | pending — dry run only, awaiting accountant approval before write |
| 15 | Drawer overstated by non-cash sale #6 (145,867.50 check payment) | Recompute `cash_registers.total_sales` cash-only via a direct guarded model update (no GL lines involved, so `TransactionService` is intentionally not used here) | pending — dry run only, awaiting accountant approval before write |
| 16 | Stale open register (id 1) never counted pre-open cash activity → 371,687 phantom variance | Recompute counters from `order_payments` (cash accounts only) within `opened_at`–now, close the register with `discrepancy = 0`. Posts nothing to the ledger — there is no real cash variance, only uncounted counters | pending — dry run only, awaiting accountant approval before write |
| 17 | Input VAT on purchases 1, 3 buried in inventory cost (116,875.00 total: 83,125.00 + 33,750.00) | Reclassify DR VAT Receivable / CR Inventory per purchase, dated today (not backdated into the closed purchase period) | pending — dry run only, awaiting accountant approval before write |

## Constraints (enforced in code)

- Never `DELETE` from `transactions` or `transaction_lines` — reversal entries only.
- Never modify a historical `sale_items.unit_cost` — post a revaluation entry instead.
- Exactly one `--step` per invocation.
- `--dry-run=1` is the default; going live requires an explicit `--dry-run=0`.
- Any dry-run result the operator cannot fully explain in the report is left as a manual
  review item rather than getting a write path (this is why steps 5, 9, and 13 are report-only).
