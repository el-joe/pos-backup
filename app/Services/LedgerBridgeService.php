<?php

namespace App\Services;

use App\Models\Tenant\Transaction;
use App\Models\Tenant\Contracting\ChartOfAccount;
use App\Models\Tenant\Contracting\JournalEntry;
use App\Models\Tenant\Contracting\JournalEntryLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LedgerBridgeService
{
    /**
     * Post a Transaction to the journal_entries system.
     * Idempotent — skips if a journal entry already exists for this transaction.
     */
    public function post(Transaction $transaction): ?JournalEntry
    {
        $existing = JournalEntry::where('referenceable_type', Transaction::class)
            ->where('referenceable_id', $transaction->id)
            ->first();
        if ($existing) return $existing;

        $map = config('tenant.account_coa_map', []);
        $lines = $transaction->lines()->with('account')->get();

        if ($lines->isEmpty()) return null;

        return DB::transaction(function () use ($transaction, $lines, $map) {
            $journalLines = [];
            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($lines as $line) {
                $accountType = $line->account?->type?->value;
                $coaCode = $map[$accountType] ?? null;

                if (!$coaCode) {
                    Log::warning("LedgerBridge: No COA mapping for account type '{$accountType}' (account_id: {$line->account_id}, transaction_id: {$transaction->id})");
                    continue;
                }

                $coa = ChartOfAccount::where('code', $coaCode)->where('is_active', 1)->first();
                if (!$coa) {
                    Log::warning("LedgerBridge: COA code '{$coaCode}' not found or inactive.");
                    continue;
                }

                $journalLines[] = [
                    'account_id'  => $coa->id,
                    'debit'       => $line->type === 'debit' ? $line->amount : 0,
                    'credit'      => $line->type === 'credit' ? $line->amount : 0,
                    'description' => $transaction->description,
                ];

                if ($line->type === 'debit') {
                    $totalDebit += $line->amount;
                } else {
                    $totalCredit += $line->amount;
                }
            }

            if (empty($journalLines)) return null;

            $entry = JournalEntry::create([
                'reference'          => $transaction->type . '#' . $transaction->id,
                'referenceable_type' => Transaction::class,
                'referenceable_id'   => $transaction->id,
                'date'               => $transaction->date,
                'description'        => $transaction->description,
                'status'             => 'posted',
                'total_debit'        => $totalDebit,
                'total_credit'       => $totalCredit,
                'posted_by'          => auth('tenant_admin')->id(),
                'posted_at'          => now(),
            ]);

            foreach ($journalLines as $jl) {
                JournalEntryLine::create(array_merge($jl, ['journal_entry_id' => $entry->id]));
            }

            return $entry;
        });
    }
}
