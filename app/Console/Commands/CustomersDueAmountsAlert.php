<?php

namespace App\Console\Commands;

use App\Mail\CustomerDueAmountEmail;
use App\Models\Tenant;
use App\Models\Tenant\Admin;
use Illuminate\Console\Command;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CustomersDueAmountsAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:customers-due-amounts-alert';

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
        foreach(Tenant::all() as $tenant){
            tenancy()->initialize($tenant);
            $this->action();
            tenancy()->end();
        }
    }

    function action(){
        // Calculate week start (Saturday) and end (Friday) using Carbon
        $today = now();
        // Find last Saturday (or today if Saturday)
        $fromDate = $today->copy()->startOfWeek(
            Carbon::SATURDAY
        );
        // Find next Friday after start
        $toDate = $fromDate->copy()->addDays(6)->endOfDay();

        $report = app(UserService::class)->customerDueAmountsReport(fromDate: $fromDate, toDate: $toDate, addSelect: ['users.id as user_id','users.name as user_name', 'users.email as user_email']);

        $adminEmails = Admin::whereType('super_admin')->pluck('email')->toArray();
        foreach($report as $row){
            $emails = $adminEmails;
            $emails[] = $row->user_email;

            Mail::to($emails)->send(new CustomerDueAmountEmail(
                username: $row->user_name,
                dueAmount: $row->balance
            ));
        }
    }
}
