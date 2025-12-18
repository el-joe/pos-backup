<?php

namespace App\Livewire\Admin\Reports\Financial;

use App\Models\Tenant\TransactionLine;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class TrailBalanceReport extends Component
{
    use WithPagination;
    public function render()
    {
        $query = TransactionLine::with(['transaction','account','transaction.branch'])
            ->orderByDesc('transaction_id')
            ->orderByDesc('id');


        $transactionLines = $query->clone()
            ->paginate(100)->withQueryString()
            ->through(function($line) {
                return [
                    'id' => $line->id,
                    'transaction_id' => $line->transaction_id,
                    'type' => $line->transaction?->type?->label(),
                    'branch' => $line->transaction?->branch?->name ?? 'N/A',
                    'reference' => $line->ref,
                    'note' => $line->transaction?->note,
                    'description' => $line->transaction?->description,
                    'date' => $line->transaction?->date,
                    'account' => $line->account?->paymentMethod?->name . ' - ' . ($line->account?->name ?? 'N/A'),
                    'debit' => currencyFormat($line->type == 'debit' ? number_format($line->amount, 2) : 0 , true),
                    'credit' => currencyFormat($line->type == 'credit' ? number_format($line->amount, 2) : 0, true),
                    'created_at' => $line->created_at,
                ];
            });

        // Refactor the headers by columns
        $headers = [
            '#' , 'Transaction ID' , 'Transaction Type' , 'Branch' , 'Reference' , 'Note' , 'Description' , 'Date' , 'Account' ,'Debit' , 'Credit' , 'Created At'
        ];

        $columns = [
            'id' => [ 'type' => 'number'],
            'transaction_id' => [ 'type' => 'number'],
            'type' => [ 'type' => 'text'],
            'branch' => [ 'type' => 'text'],
            'reference' => [ 'type' => 'text'],
            'note' => [ 'type' => 'text'],
            'description' => [ 'type' => 'text'],
            'date' => [ 'type' => 'date'],
            'account' => [ 'type' => 'text'],
            'debit' => [
                'type' => 'badge',
                'class' => 'badge-success',
                'icon' => 'fa-arrow-up text-success',
                'value' => fn($q)=>$q['debit']
            ],
            'credit' => [
                'type' => 'badge',
                'class' => 'badge-danger',
                'icon' => 'fa-arrow-down text-danger',
                'value' => fn($q)=>$q['credit']
            ],
            'created_at' => [ 'type' => 'datetime'],
        ];

        $totals = [
            'total' => [
                'colspan' => 8,
                'label' => 'Totals',
                'class' => 'text-end',
            ],
            'debit' => currencyFormat($query->clone()->where('type', 'debit')->sum('amount'), true),
            'credit' => currencyFormat($query->clone()->where('type', 'credit')->sum('amount'), true),
        ];

        return layoutView('reports.financial.trail-balance-report',get_defined_vars());
    }
}
