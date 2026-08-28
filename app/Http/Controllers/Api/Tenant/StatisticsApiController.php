<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Models\Tenant\Expense;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class StatisticsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'statistics.show';
    }

    public function summary(Request $request)
    {
        $today = now()->toDateString();

        $sales = Sale::filter(['from_date' => $today, 'to_date' => $today])->get();
        $purchases = Purchase::filter(['date_from' => $today, 'date_to' => $today])->get();
        $expensesAmount = (float) Expense::filter(['date_from' => $today, 'date_to' => $today])->sum('amount');

        $totalSales = (float) $sales->sum(fn ($s) => $s->grand_total_amount);
        $totalPurchases = (float) $purchases->sum('total_amount');

        return $this->success([
            'date' => $today,
            'salesTotal' => $totalSales,
            'salesCount' => $sales->count(),
            'purchasesTotal' => $totalPurchases,
            'purchasesCount' => $purchases->count(),
            'expensesTotal' => $expensesAmount,
        ]);
    }

    public function daily(Request $request)
    {
        $days = (int) $request->query('days', 30);
        $days = max(1, min($days, 365));

        $from = now()->subDays($days - 1)->startOfDay();
        $to = now()->endOfDay();

        $sales = Sale::filter(['from_date' => $from->toDateString(), 'to_date' => $to->toDateString()])->get();
        $purchases = Purchase::filter(['date_from' => $from->toDateString(), 'date_to' => $to->toDateString()])->get();
        $expenses = Expense::filter(['date_from' => $from->toDateString(), 'date_to' => $to->toDateString()])->get();

        $period = collect(CarbonPeriod::create($from, $to));

        $data = $period->map(function (Carbon $date) use ($sales, $purchases, $expenses) {
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            return [
                'date' => $date->toDateString(),
                'salesTotal' => round((float) $sales->whereBetween('order_date', [$dayStart, $dayEnd])->sum('grand_total_amount'), 2),
                'purchasesTotal' => round((float) $purchases->whereBetween('order_date', [$dayStart, $dayEnd])->sum('total_amount'), 2),
                'expensesTotal' => round((float) $expenses->whereBetween('expense_date', [$dayStart, $dayEnd])->sum('amount'), 2),
            ];
        })->values();

        return $this->success($data);
    }
}
