<?php

namespace App\Livewire\Central\CPanel\Admins;

use App\Models\Tenant;
use App\Traits\LivewireOperations;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.cpanel')]
class PermissionsAuditPage extends Component
{
    use LivewireOperations;

    public $rows = [];

    public function mount()
    {
        $this->loadAudit();
    }

    public function loadAudit()
    {
        $default = defaultPermissionsList();
        $defaultNames = [];
        foreach ($default as $key => $actions) {
            foreach ($actions as $action) {
                $defaultNames[] = $key . '.' . $action;
            }
        }

        $rows = [];

        foreach (Tenant::all() as $tenant) {
            tenancy()->initialize($tenant);

            $existingNames = Permission::where('guard_name', 'tenant_admin')->pluck('name')->all();
            $missing = array_values(array_diff($defaultNames, $existingNames));
            $lastSeeded = Permission::where('guard_name', 'tenant_admin')->max('created_at');

            $rows[] = [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name ?? $tenant->id,
                'permissions_count' => count($existingNames),
                'missing_count' => count($missing),
                'last_seeded' => $lastSeeded,
            ];

            tenancy()->end();
        }

        $this->rows = $rows;
    }

    public function seedAll()
    {
        if (cpanelAdmin()->type !== 'super_admin') {
            $this->popup('error', 'You are not authorized to perform this action');
            return;
        }

        foreach (Tenant::all() as $tenant) {
            tenancy()->initialize($tenant);
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\Tenant\\PermissionSeeder', '--force' => true]);
            tenancy()->end();
        }

        $this->loadAudit();

        $this->popup('success', 'Permissions seeded for all tenants');
    }

    public function seedSingle($tenantId)
    {
        if (cpanelAdmin()->type !== 'super_admin') {
            $this->popup('error', 'You are not authorized to perform this action');
            return;
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->popup('error', 'Tenant not found');
            return;
        }

        tenancy()->initialize($tenant);
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\Tenant\\PermissionSeeder', '--force' => true]);
        tenancy()->end();

        $this->loadAudit();

        $this->popup('success', 'Permissions seeded for ' . ($tenant->name ?? $tenant->id));
    }

    public function render()
    {
        return view('livewire.central.cpanel.admins.permissions-audit', get_defined_vars());
    }
}
