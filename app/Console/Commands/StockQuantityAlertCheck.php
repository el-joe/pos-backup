<?php

namespace App\Console\Commands;

use App\Mail\StockQtyCheckMail;
use App\Models\Tenant\Admin;
use App\Models\Tenant\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Concerns\HasATenantsOption;
use Stancl\Tenancy\Concerns\TenantAwareCommand;

class StockQuantityAlertCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stock-quantity-alert-check {--branch_id=} {--tenant_id=}';

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
        $branchId = $this->option('branch_id');
        $tenant_id = $this->option('tenant_id');

        tenancy()->find($tenant_id)->run(function () use ($branchId) {
            $products = Product::select('id','name','unit_id','alert_qty')->get();
            $productsAlert = [];

            foreach ($products as $product) {
                $currentStock = $product->branchStock($product->unit_id,$branchId);
                $alertQty = $product->alert_qty ?? 0;
                if($currentStock == 0){
                    $productsAlert[] = [
                        'name' => $product->name,
                        'current_qty' => $currentStock,
                        'alert_qty' => $alertQty,
                        'status' => 'out_of_stock',
                    ];
                }elseif($currentStock <= $alertQty){
                    $productsAlert[] = [
                        'name' => $product->name,
                        'current_qty' => $currentStock,
                        'alert_qty' => $alertQty,
                        'status' => 'low_stock',
                    ];
                }
            }

            if(count($productsAlert) > 0){
                $adminsEmail = Admin::pluck('email')->toArray();
                Mail::to($adminsEmail)->send(new StockQtyCheckMail($productsAlert));
            }
        });
    }
}
