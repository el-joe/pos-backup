<?php

namespace App\Repositories;

use App\Enums\AccountTypeEnum;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SellRepository extends BaseRepository
{
    function __construct(Sale $sale)
    {
        $this->setInstance($sale);
    }

    function salesSummaryReport($from_date, $to_date, $period)
    {
        $toDate = Carbon::parse($to_date)->endOfDay()->format('Y-m-d H:i:s');
        $groupFormat = match($period) {
            'day' => '%Y-%m-%d',
            'week' => '%x-W%v',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $salesAccountType = AccountTypeEnum::SALES->value;
        $salesDiscountAccountType = AccountTypeEnum::SALES_DISCOUNT->value;
        $salesReturnAccountType = AccountTypeEnum::SALES_RETURN->value;
        $salesVatPayableAccountType = AccountTypeEnum::VAT_PAYABLE->value;
        $salesCogsAccountType = AccountTypeEnum::COGS->value;

        $grossSales = 'SUM(CASE WHEN accounts.type = "' . $salesAccountType . '" AND transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END)';
        $discount = 'SUM(CASE WHEN accounts.type = "' . $salesDiscountAccountType . '" AND transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END)';
        $salesReturn = 'SUM(CASE WHEN accounts.type = "' . $salesReturnAccountType . '" AND transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END)';
        //Net Sales=Gross Sales−Discounts−Sales Returns
        $netSales = $grossSales . ' - ' . $discount . ' - ' . $salesReturn;
        $vatPayable = 'SUM(CASE WHEN accounts.type = "' . $salesVatPayableAccountType . '" AND transaction_lines.type = "credit" THEN transaction_lines.amount ELSE 0 END)';
        //Total Collected=Net Sales+VAT Payable
        $totalCollected = $netSales . ' + ' . $vatPayable;
        $cogs = 'SUM(CASE WHEN accounts.type = "' . $salesCogsAccountType . '" AND transaction_lines.type = "debit" THEN transaction_lines.amount ELSE 0 END)';
        //Gross Profit=Net Sales−COGS
        $grossProfit = $netSales . ' - ' . $cogs;

        $rows = DB::table('transaction_lines')
            ->join('accounts', 'accounts.id', '=', 'transaction_lines.account_id')
            ->select(
                DB::raw("DATE_FORMAT(transaction_lines.created_at, '$groupFormat') as period"),
                DB::raw("{$grossSales} as gross_sales"),
                DB::raw("{$discount} as discount"),
                DB::raw("{$salesReturn} as sales_return"),
                DB::raw("{$netSales} as net_sales"),
                DB::raw("{$vatPayable} as vat_payable"),
                DB::raw("{$totalCollected} as total_collected"),
                DB::raw("{$cogs} as cogs"),
                DB::raw("{$grossProfit} as gross_profit")
            )
            ->whereBetween('transaction_lines.created_at', [$from_date, $toDate])
            ->groupBy(DB::raw("DATE_FORMAT(transaction_lines.created_at, '$groupFormat')"))
            ->orderBy('period')
            ->get();

        $summary = [];
        foreach ($rows as $row) {
            $summary[] = [
                'period' => $row->period,
                'gross_sales' => $row->gross_sales ?? 0,
                'discount' => $row->discount ?? 0,
                'sales_return' => $row->sales_return ?? 0,
                'net_sales' => $row->net_sales ?? 0,
                'vat_payable' => $row->vat_payable ?? 0,
                'total_collected' => $row->total_collected ?? 0,
                'cogs' => $row->cogs ?? 0,
                'gross_profit' => $row->gross_profit ?? 0,
            ];
        }

        return $summary;
    }
}
