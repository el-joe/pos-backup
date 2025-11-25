<?php


namespace App\Livewire\Admin\Reports\Purchases;

use App\Models\Tenant\Purchase;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class PurchasesSummaryReport extends Component
{
    public $from_date;
    public $to_date;
    public $report = [];

    public function mount()
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $this->report = $this->getPurchasesSummaryReport();

        return layoutView('reports.purchases.purchases-summary-report', [
            'report' => $this->report,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
        ]);
    }

    protected function getPurchasesSummaryReport()
    {
        $toDate = carbon($this->to_date)->endOfDay()->format('Y-m-d H:i:s');
        // Use subquery to calculate per-purchase total, then aggregate for summary
        $subQuery = DB::table('purchases')
            ->select([
                'purchases.id',
                DB::raw('DATE(purchases.order_date) as purchase_date'),
                // Calculate items total
                DB::raw('(
                    SELECT SUM((pi.purchase_price - (pi.purchase_price * pi.discount_percentage / 100)) * (pi.qty - pi.refunded_qty)
                        + ((pi.purchase_price - (pi.purchase_price * pi.discount_percentage / 100)) * (pi.qty - pi.refunded_qty) * pi.tax_percentage / 100)
                    ) FROM purchase_items pi WHERE pi.purchase_id = purchases.id
                ) as items_total'),
                // Calculate expenses total
                DB::raw('(
                    SELECT SUM(e.amount) FROM expenses e WHERE e.model_id = purchases.id AND e.model_type = "App\\Models\\Tenant\\Purchase"
                ) as expenses_total'),
                'purchases.discount_type',
                'purchases.discount_value',
                'purchases.tax_percentage'
            ])
            ->whereBetween('purchases.order_date', [$this->from_date, $toDate]);

        $purchases = $subQuery->get();

        // Now, calculate grand total per purchase in PHP, then aggregate per day
        $summary = [];
        foreach ($purchases as $purchase) {
            $itemsTotal = (float)($purchase->items_total ?? 0);
            $expensesTotal = (float)($purchase->expenses_total ?? 0);
            $subTotal = $itemsTotal + $expensesTotal;
            if ($purchase->discount_type === 'fixed') {
                $discountAmount = (float)$purchase->discount_value;
            } else {
                $discountAmount = $subTotal * ((float)$purchase->discount_value / 100);
            }
            $totalAfterDiscount = $subTotal - $discountAmount;
            $taxAmount = $totalAfterDiscount * ((float)$purchase->tax_percentage / 100);
            $grandTotal = $totalAfterDiscount + $taxAmount;

            $date = $purchase->purchase_date;
            if (!isset($summary[$date])) {
                $summary[$date] = [
                    'purchase_date' => $date,
                    'purchase_count' => 0,
                    'total_value' => 0.0,
                ];
            }
            $summary[$date]['purchase_count']++;
            $summary[$date]['total_value'] += $grandTotal;
        }

        // Return as array of objects for blade
        return array_values($summary);
    }
}
