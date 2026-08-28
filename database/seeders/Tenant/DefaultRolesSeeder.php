<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DefaultRolesSeeder extends Seeder
{
    /**
     * Permissions granted to the built-in "Cashier" role.
     */
    private const CASHIER_PERMISSIONS = [
        'pos.create',
        'cash_register.create',
        'sales.list',
        'sales.show',
        'sales.show-invoice',
        'customers.list',
        'customers.show',
        'products.list',
        'products.show',
        'payment_methods.list',
        'deferred_pos.create',
    ];

    /**
     * Permissions granted to the built-in "Accountant" role.
     */
    private const ACCOUNTANT_PERMISSIONS = [
        'statistics.show',
        'transactions.list',
        'transactions.export',
        'checks.list',
        'checks.collect',
        'checks.bounce',
        'checks.clear',
        'reports.list',
        'reports.export',
        'accounts.list',
        'accounts.export',
        'expenses.list',
        'expenses.create',
        'expenses.update',
        'expenses.export',
        'fixed_assets.list',
        'fixed_assets.show',
        'fixed_assets.export',
        'depreciation_expenses.list',
        'depreciation_expenses.export',
    ];

    public function run(): void
    {
        $guard = 'tenant_admin';

        $existingPermissionNames = Permission::where('guard_name', $guard)->pluck('name')->all();

        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => $guard],
            ['active' => true]
        );
        $superAdmin->syncPermissions($existingPermissionNames);

        $cashier = Role::firstOrCreate(
            ['name' => 'Cashier', 'guard_name' => $guard],
            ['active' => true]
        );
        $cashier->syncPermissions($this->filterExisting(self::CASHIER_PERMISSIONS, $existingPermissionNames));

        $accountant = Role::firstOrCreate(
            ['name' => 'Accountant', 'guard_name' => $guard],
            ['active' => true]
        );
        $accountant->syncPermissions($this->filterExisting(self::ACCOUNTANT_PERMISSIONS, $existingPermissionNames));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Only keep permission names that actually exist for the guard, so a
     * missing/renamed permission never throws when syncing a role.
     */
    private function filterExisting(array $wanted, array $existingPermissionNames): array
    {
        return array_values(array_intersect($wanted, $existingPermissionNames));
    }
}
