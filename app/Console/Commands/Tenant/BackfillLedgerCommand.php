<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Transaction;
use App\Services\LedgerBridgeService;
use Illuminate\Console\Command;

class BackfillLedgerCommand extends Command
{
    protected $signature = 'ledger:backfill {--tenant=} {--dry-run=1 : Report what would happen without posting anything (default on; pass --dry-run=0 to actually post)}';
    protected $description = 'Backfill journal_entries for existing transactions that have not been posted to the ledger yet';

    public function handle(LedgerBridgeService $ledgerBridge)
    {
        $tenantId = $this->option('tenant');
        $dryRun = (bool) $this->option('dry-run');

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        if ($dryRun) {
            $this->warn('DRY RUN — no journal entries will be written. Pass --dry-run=0 to post for real.');
        }

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Processing tenant: {$tenant->id}");
            $dryRun
                ? $this->previewTenant($ledgerBridge, $tenant)
                : $this->backfillTenant($ledgerBridge);
            tenancy()->end();
        }

        return self::SUCCESS;
    }

    protected function unpostedQuery()
    {
        return Transaction::whereNotExists(function ($sub) {
            $sub->selectRaw(1)
                ->from('journal_entries')
                ->whereColumn('journal_entries.referenceable_id', 'transactions.id')
                ->whereIn('journal_entries.referenceable_type', [
                    Transaction::class,
                    'App\\Models\\Tenant\\Transaction',
                ]);
        });
    }

    protected function previewTenant(LedgerBridgeService $ledgerBridge, Tenant $tenant): void
    {
        $query = $this->unpostedQuery();
        $total = $query->count();

        if ($total === 0) {
            $this->info("[{$tenant->id}] 0 unposted transactions — nothing to do.");
            return;
        }

        $wouldMap = 0;
        $wouldFail = 0;
        $reasons = [];

        $query->orderBy('id')->chunkById(50, function ($transactions) use ($ledgerBridge, &$wouldMap, &$wouldFail, &$reasons) {
            foreach ($transactions as $transaction) {
                $result = $ledgerBridge->preview($transaction);
                if ($result['ok']) {
                    $wouldMap++;
                } else {
                    $wouldFail++;
                    $reasons[] = "  #{$transaction->id}: {$result['reason']}";
                }
            }
        });

        $this->info("[{$tenant->id}] {$total} unposted transactions — {$wouldMap} would map, {$wouldFail} would fail.");
        foreach (array_slice($reasons, 0, 50) as $reason) {
            $this->line($reason);
        }
        if (count($reasons) > 50) {
            $this->line('  ... and ' . (count($reasons) - 50) . ' more failures.');
        }
    }

    protected function backfillTenant(LedgerBridgeService $ledgerBridge): void
    {
        $query = $this->unpostedQuery();

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
