<?php

namespace App\Livewire\Admin\Reports\Performance;


use App\Enums\AccountTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Tenant\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.admin')]
class SalesThresholdReport extends Component
{
    public $report = [];
    public $from_date;
    public $to_date;

    public function mount()
    {
        $this->from_date = Carbon::now()->startOfMonth()->toDateString();
        $this->to_date = Carbon::now()->endOfDay()->toDateString();
    }

    public function updated($property)
    {
        if (in_array($property, ['from_date', 'to_date'])) {
            $this->loadReport();
        }
    }

    public function loadReport()
    {
        $fromDate = Carbon::parse($this->from_date)->startOfDay();
        $toDate = Carbon::parse($this->to_date)->endOfDay();

        $salesType = AccountTypeEnum::SALES->value;

        $this->report = User::whereType(UserTypeEnum::CUSTOMER->value)
            ->with('sales.saleItems')
            ->get()
            ->map(function ($user) {
                $totalSales = $user->sales->sum(fn($q)=>$q->grand_total_amount);
                return (object)[
                    'customer_id' => $user->id,
                    'customer_name' => $user->name,
                    'sales_threshold' => $user->sales_threshold,
                    'total_sales' => $totalSales,
                    'status' => $totalSales >= $user->sales_threshold ? 'Reached' : 'Not Yet',
                ];
            });

            // dd($this->report);
    }

    public function render()
    {
        if (empty($this->report)) {
            $this->loadReport();
        }
        return view('livewire.admin.reports.performance.sales-threshold-report');
    }
}
