<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\Admin;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Setting;
use App\Services\PaymentMethodService;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantCreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tenant-create-admin {request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // الحصول على التينانت المحدد
        $tenant = Tenant::find(tenant()->id);

        // التبديل للقاعدة الخاصة بالـ tenant
        $tenant->run(function () {
            // get arguments
            $admin = Admin::create(json_decode($this->argument('request'),true));

            $permissions = defaultPermissionsList();

            $permissionsData = [];

            foreach ($permissions as $key => $value) {
                foreach($value as $permission){
                    $data = ['name' => $key.'.'.$permission, 'guard_name' => 'tenant_admin'];
                    $permissionsData[] = $data;
                }
            }

            Permission::upsert($permissionsData, ['name', 'guard_name']);

            $this->defaultPaymentMethods();
            $this->defaultSettings();
        });

    }

    function defaultPaymentMethods() {
        $paymentMethodService = app(PaymentMethodService::class);

        $paymentMethods = [
            ['name' => 'Cash' , 'slug' => 'cash', 'active' => true],
            ['name' => 'Bank Transfer', 'slug' => 'bank-transfer', 'active' => true],
        ];

        foreach ($paymentMethods as $method) {
            $paymentMethodService->save(null, $method);
        }
    }

    function defaultSettings() {

    }
}
