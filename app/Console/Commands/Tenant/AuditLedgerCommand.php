<?php

namespace App\Console\Commands\Tenant;

use App\Models\Tenant;
use App\Models\Tenant\Transaction;
use App\Models\Tenant\TransactionLine;
use Illuminate\Console\Command;

class AuditLedgerCommand extends Command
{
    protected $signature = 'tenant:audit-ledger {--tenant=}';
    protected $description = 'Read-only audit of ledger integrity: unbalanced transactions, zero-line transactions, and orphan transaction lines';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');

        $tenants = $tenantId
            ? Tenant::where('id', $tenantId)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            $this->info("Tenant: {$tenant->id}");
            $this->auditTenant();
            tenancy()->end();
        }

        return self::SUCCESS;
    }

    protected function auditTenant(): void
    {
        $unbalanced = Transaction::unbalanced()->get(['id', 'type', 'reference_type', 'reference_id', 'amount', 'date']);

        $this->line('Unbalanced transactions: '.$unbalanced->count());
        if ($unbalanced->isNotEmpty()) {
            $this->table(
                ['ID', 'Type', 'Reference Type', 'Reference ID', 'Amount', 'Date'],
                $unbalanced->map(fn ($t) => [
                    $t->id,
                    $t->type?->value ?? $t->getRawOriginal('type'),
                    $t->reference_type,
                    $t->reference_id,
                    $t->amount,
                    $t->date,
                ])
            );
        }

        $zeroLines = Transaction::whereDoesntHave('lines')->get(['id', 'type', 'reference_type', 'reference_id', 'amount', 'date']);

        $this->line('Transactions with zero lines: '.$zeroLines->count());
        if ($zeroLines->isNotEmpty()) {
            $this->table(
                ['ID', 'Type', 'Reference Type', 'Reference ID', 'Amount', 'Date'],
                $zeroLines->map(fn ($t) => [
                    $t->id,
                    $t->type?->value ?? $t->getRawOriginal('type'),
                    $t->reference_type,
                    $t->reference_id,
                    $t->amount,
                    $t->date,
                ])
            );
        }

        $orphanLines = TransactionLine::whereDoesntHave('transaction')->get(['id', 'transaction_id', 'account_id', 'type', 'amount']);

        $this->line('Orphan transaction lines: '.$orphanLines->count());
        if ($orphanLines->isNotEmpty()) {
            $this->table(
                ['ID', 'Transaction ID', 'Account ID', 'Type', 'Amount'],
                $orphanLines->map(fn ($l) => [$l->id, $l->transaction_id, $l->account_id, $l->type, $l->amount])
            );
        }
    }
}
