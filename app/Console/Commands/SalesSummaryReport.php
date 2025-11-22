<?php

namespace App\Console\Commands;

use App\Mail\SalesSummaryReport as MailSalesSummaryReport;
use App\Models\Tenant;
use App\Models\Tenant\Admin;
use App\Services\SellService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SalesSummaryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sales-summary-report';

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
        foreach (Tenant::all() as $tenant) {
            tenancy()->initialize($tenant);
            $sellService = app()->make(SellService::class);
            $report = $sellService->salesSummaryReport(
                now()->format('Y-m-d'),
                now()->format('Y-m-d'),
                'day'
            );

            Admin::whereType('super_admin')->get()->each(function (Admin $admin) use ($tenant, $report) {
                Mail::to($admin->email)->send(new MailSalesSummaryReport($tenant, $report[0]));
            });
            tenancy()->end();
        }
    }
}
