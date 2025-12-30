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
            ->paginate(20)->withQueryString()
            ->through(function($line) {
                return [
                    'id' => $line->id,
                    'transaction_id' => $line->transaction_id,
                    'type' => $line->transaction?->type?->label(),
                    'branch' => $line->transaction?->branch?->name ?? 'N/A',
                    'reference' => $line->ref,
                    'note' => $line->transaction?->note,
                    'description' => $line->transaction?->description,
                    'date' => dateTimeFormat($line->transaction?->date,true,false),
                    'account' => $line->account?->paymentMethod?->name . ' - ' . ($line->account?->name ?? 'N/A'),
                    'account_type' => $line->account?->type->label() ?? 'N/A',
                    'debit' => currencyFormat($line->type == 'debit' ? number_format($line->amount, 2) : 0 , true),
                    'credit' => currencyFormat($line->type == 'credit' ? number_format($line->amount, 2) : 0, true),
                    'created_at' => dateTimeFormat($line->created_at,true,false),
                ];
            });

        // Refactor the headers by columns
        $headers = [
            '#' , 'Transaction ID' , 'Transaction Type' , 'Branch' , 'Reference' , 'Note' , 'Description' , 'Date' , 'Account' , 'Account Type' ,'Debit' , 'Credit' , 'Created At'
        ];

        $columns = [
            'id' => [ 'type' => 'number'],
            'transaction_id' => [ 'type' => 'number'],
            'type' => [ 'type' => 'text'],
            'branch' => [ 'type' => 'text'],
            'reference' => [ 'type' => 'text'],
            'note' => [ 'type' => 'text'],
            'description' => [ 'type' => 'text'],
            'date' => [ 'type' => 'text'],
            'account' => [ 'type' => 'text'],
            'account_type' => [ 'type' => 'text'],
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
            'created_at' => [ 'type' => 'text'],
        ];

        $totals = [
            'total' => [
                'colspan' => 10,
                'label' => 'Totals',
                'class' => app()->getLocale() == 'ar' ? 'text-end pe-3' : 'text-start ps-3',
            ],
            'debit' => currencyFormat($query->clone()->where('type', 'debit')->sum('amount'), true),
            'credit' => currencyFormat($query->clone()->where('type', 'credit')->sum('amount'), true),
        ];

        return layoutView('reports.financial.trail-balance-report',get_defined_vars());
    }
}
