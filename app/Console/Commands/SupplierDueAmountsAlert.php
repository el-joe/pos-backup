<?php

namespace App\Console\Commands;

use App\Mail\SupplierDueAmountEmail;
use App\Models\Tenant;
use App\Models\Tenant\Admin;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SupplierDueAmountsAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:supplier-due-amounts-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send alerts for supplier due amounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach(Tenant::all() as $tenant){
            tenancy()->initialize($tenant);
            $this->action();
            tenancy()->end();
        }
    }

    function action(){
        $today = now();
        // Find last Saturday (or today if Saturday)
        $fromDate = $today->copy()->startOfWeek(
            Carbon::SATURDAY
        );
        // Find next Friday after start
        $toDate = $fromDate->copy()->addDays(6)->endOfDay();

        $report = app(UserService::class)->supplierDueAmountsReport(fromDate: $fromDate, toDate: $toDate, addSelect: ['users.id as user_id','users.name as user_name', 'users.email as user_email']);

        $adminEmails = Admin::whereType('super_admin')->pluck('email')->toArray();
        $data = [];

        foreach($report as $row){
            $data[] = [
                'supplierName' => $row->user_name,
                'dueAmount' => $row->balance,
            ];
        }

        Mail::to($adminEmails)->send(new SupplierDueAmountEmail(
            data : $data
        ));
    }
}
