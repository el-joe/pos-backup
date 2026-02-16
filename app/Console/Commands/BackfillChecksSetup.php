<?php

namespace App\Console\Commands;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant;
use App\Models\Tenant\Account;
use App\Models\Tenant\Branch;
use App\Models\Tenant\PaymentMethod;
use Illuminate\Console\Command;

class BackfillChecksSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-checks-setup {--tenant_id=} {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill payment method "check" and seed check accounts per branch for existing tenants.';

    public function handle(): int
    {
        $tenantId = $this->option('tenant_id');
        $dryRun = (bool) $this->option('dry-run');

        $tenants = $tenantId ? Tenant::query()->where('id', $tenantId)->get() : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No tenants found.');
            return self::SUCCESS;
        }

        foreach ($tenants as $tenant) {
            $this->line("Tenant: {$tenant->id}");

            tenancy()->initialize($tenant);

            try {
                if (!$dryRun) {
                    $method = PaymentMethod::withTrashed()
                        ->where('slug', 'check')
                        ->whereNull('branch_id')
                        ->first();

                    if (!$method) {
                        PaymentMethod::create([
                            'name' => 'Check',
                            'slug' => 'check',
                            'active' => true,
                            'branch_id' => null,
                        ]);
                        $this->info('  - Created payment method: check');
                    } else {
                        if ($method->trashed()) {
                            $method->restore();
                            $this->info('  - Restored payment method: check');
                        }

                        if (!$method->active) {
                            $method->update(['active' => true]);
                            $this->info('  - Activated payment method: check');
                        }
                    }

                    $branches = Branch::query()->select('id')->get();

                    foreach ($branches as $branch) {
                        Account::defaultForPaymentMethodSlug(
                            'Checks Under Collection',
                            AccountTypeEnum::CHECKS_UNDER_COLLECTION->value,
                            $branch->id,
                            'check'
                        );

                        Account::defaultForPaymentMethodSlug(
                            'Issued Checks',
                            AccountTypeEnum::ISSUED_CHECKS->value,
                            $branch->id,
                            'check'
                        );
                    }

                    $this->info('  - Seeded check accounts for branches: ' . $branches->count());
                } else {
                    $branchesCount = Branch::query()->count();
                    $this->info('  - DRY RUN: would ensure payment method check + seed 2 accounts per branch (' . $branchesCount . ' branches)');
                }
            } catch (\Throwable $e) {
                $this->error('  - Failed: ' . $e->getMessage());
            } finally {
                tenancy()->end();
            }
        }

        return self::SUCCESS;
    }
}
