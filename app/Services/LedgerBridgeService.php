<?php

namespace App\Services;

use App\Enums\AccountTypeEnum;
use App\Exceptions\LedgerBridgeException;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\Contracting\ChartOfAccount;
use App\Models\Tenant\Contracting\JournalEntry;
use App\Models\Tenant\Contracting\JournalEntryLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LedgerBridgeService
{
    /**
     * Post a Transaction (system A, the authoritative ledger) to journal_entries
     * (system B, the accounting/reporting projection — see docs/ledger-architecture-decision.md).
     * Idempotent — skips if a journal entry already exists for this transaction.
     *
     * Throws rather than dropping lines or posting an unbalanced entry: a partial or
     * unbalanced journal entry is worse than no journal entry, since system A already
     * holds the authoritative, balanced record.
     */
    public function post(Transaction $transaction): ?JournalEntry
    {
        $existing = JournalEntry::where('referenceable_type', Transaction::class)
            ->where('referenceable_id', $transaction->id)
            ->first();
        if ($existing) return $existing;

        $map = config('tenant.account_coa_map', []);

        if (app()->environment('local', 'staging')) {
            $validTypes = array_column(AccountTypeEnum::cases(), 'value');
            $unknownKeys = array_diff(array_keys($map), $validTypes);
            if ($unknownKeys) {
                Log::error('LedgerBridge config has unknown account type keys: ' . implode(', ', $unknownKeys));
            }
        }

        if ($transaction->lines()->doesntExist()) return null;

        return DB::transaction(function () use ($transaction, $map) {
            [$journalLines, $totalDebit, $totalCredit] = $this->mapLines($transaction, $map);

            $type = $transaction->type instanceof \BackedEnum ? $transaction->type->value : $transaction->type;

            $entry = JournalEntry::create([
                'reference'          => $type . '#' . $transaction->id,
                'referenceable_type' => Transaction::class,
                'referenceable_id'   => $transaction->id,
                'date'               => $transaction->date,
                'description'        => $transaction->description,
                'status'             => 'posted',
                'total_debit'        => $totalDebit,
                'total_credit'       => $totalCredit,
                'posted_by'          => admin()?->id,
                'posted_at'          => now(),
            ]);

            foreach ($journalLines as $jl) {
                JournalEntryLine::create(array_merge($jl, ['journal_entry_id' => $entry->id]));
            }

            return $entry;
        });
    }

    /**
     * Post the reversal Transaction (created by TransactionService::reverse()) to the ledger.
     * The reversal is a distinct Transaction row with its lines' debit/credit flipped, so this
     * simply delegates to post() — kept as a named entry point so callers reversing a posted
     * transaction don't need to know that detail.
     */
    public function reverse(Transaction $transaction): ?JournalEntry
    {
        $reversal = $transaction->reversed_by_transaction_id
            ? Transaction::find($transaction->reversed_by_transaction_id)
            : null;

        if (!$reversal) return null;

        return $this->post($reversal);
    }

    /**
     * Dry-run check: can this transaction be mapped to a balanced journal entry without
     * writing anything? Used by ledger:backfill --dry-run to report per-transaction
     * failure reasons before any posting is attempted.
     *
     * @return array{ok: bool, reason: ?string}
     */
    public function preview(Transaction $transaction): array
    {
        $existing = JournalEntry::where('referenceable_type', Transaction::class)
            ->where('referenceable_id', $transaction->id)
            ->first();
        if ($existing) return ['ok' => true, 'reason' => null];

        if ($transaction->lines()->doesntExist()) return ['ok' => true, 'reason' => null];

        try {
            $this->mapLines($transaction, config('tenant.account_coa_map', []));
        } catch (LedgerBridgeException $e) {
            return ['ok' => false, 'reason' => $e->getMessage()];
        }

        return ['ok' => true, 'reason' => null];
    }

    /**
     * Maps a transaction's lines to journal_entry_lines rows + totals, without writing
     * anything. Throws LedgerBridgeException on the first unmapped/missing/unbalanced case.
     *
     * @return array{0: array<int, array>, 1: float, 2: float}
     */
    private function mapLines(Transaction $transaction, array $map): array
    {
        $lines = $transaction->lines()->with('account')->get();

        $journalLines = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $accountType = $line->account?->type?->value;
            $coaCode = $map[$accountType] ?? null;

            if (!$coaCode) {
                throw LedgerBridgeException::unmappedAccountType($accountType, $line->account_id, $transaction->id);
            }

            $coa = ChartOfAccount::where('code', $coaCode)->where('is_active', 1)->first();
            if (!$coa) {
                throw LedgerBridgeException::missingCoaAccount($coaCode, $transaction->id);
            }

            $journalLines[] = [
                'account_id'      => $coa->id,
                'cost_center_id'  => $line->cost_center_id,
                'project_id'      => $line->project_id,
                'debit'           => $line->type === 'debit' ? $line->amount : 0,
                'credit'          => $line->type === 'credit' ? $line->amount : 0,
                'description'     => $transaction->description,
            ];

            if ($line->type === 'debit') {
                $totalDebit += $line->amount;
            } else {
                $totalCredit += $line->amount;
            }
        }

        if (abs($totalDebit - $totalCredit) > 0.005) {
            throw LedgerBridgeException::unbalanced($transaction->id, $totalDebit, $totalCredit);
        }

        return [$journalLines, $totalDebit, $totalCredit];
    }
}
