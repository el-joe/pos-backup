<?php

namespace App\Console\Commands;

use App\Models\Tenant\Admin;
use App\Models\Tenant\Branch;
use App\Services\PaymentMethodService;
use Illuminate\Console\Command;

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
        // get arguments
        $admin = Admin::create(json_decode($this->argument('request'),true));

        // $permissions = Admin::PERMISSIONS;

        // foreach ($permissions as $key => $value) {
        //     foreach($value as $permission){
        //         $admin->givePermissionTo($key.'.'.$permission);
        //     }
        // }

        $this->defaultPaymentMethods();
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
}
