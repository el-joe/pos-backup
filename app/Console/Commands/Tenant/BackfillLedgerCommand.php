<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\Contracting\JournalEntry;
use App\Services\LedgerBridgeService;
use Illuminate\Console\Command;

class BackfillLedgerCommand extends Command
{
    protected $signature = 'ledger:backfill {--tenant=}';
    protected $description = 'Backfill journal_entries for existing transactions that have not been posted to the ledger yet';

    public function handle(LedgerBridgeService $ledgerBridge)
    {
        $tenantId = $this->option('tenant');

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Processing tenant: {$tenant->id}");
            $this->backfillTenant($ledgerBridge);
            tenancy()->end();
        }

        return self::SUCCESS;
    }

    protected function backfillTenant(LedgerBridgeService $ledgerBridge): void
    {
        $query = Transaction::whereNotExists(function ($sub) {
            $sub->selectRaw(1)
                ->from('journal_entries')
                ->whereColumn('journal_entries.referenceable_id', 'transactions.id')
                ->whereIn('journal_entries.referenceable_type', [
                    Transaction::class,
                    'App\\Models\\Tenant\\Transaction',
                ]);
        });

        $total = $query->count();
        $posted = 0;

        if ($total === 0) {
            $this->info('Posted 0 / 0 transactions');
            return;
        }

        $query->orderBy('id')->chunkById(50, function ($transactions) use ($ledgerBridge, &$posted, $total) {
            foreach ($transactions as $transaction) {
                $ledgerBridge->post($transaction);
                $posted++;
            }
            $this->info("Posted {$posted} / {$total} transactions");
        });
    }
}
