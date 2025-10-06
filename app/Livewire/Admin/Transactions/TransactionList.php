<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Tenant\TransactionLine;
use App\Services\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class TransactionList extends Component
{
    use WithPagination;

    public function render()
    {
        $transactionLines = TransactionLine::with(['transaction','account','transaction.branch'])
        ->orderByDesc('transaction_id')
        ->orderByDesc('id')
        ->paginate(10)->withQueryString()
        ->through(function($line) {
            return [
                'id' => $line->id,
                'transaction_id' => $line->transaction_id,
                'type' => $line->transaction?->type?->label(),
                'branch' => $line->transaction?->branch?->name ?? 'N/A',
                'reference' => $line->ref,
                'note' => $line->transaction?->note,
                'date' => $line->transaction?->date,
                'account' => $line->account?->paymentMethod?->name . ' - ' . ($line->account?->name ?? 'N/A'),
                'line_type' => $line->type,
                'amount' => $line->amount,
                'created_at' => $line->created_at,
            ];
        });

        $headers = [
            '#' , 'Transaction ID' , 'Transaction Type' , 'Branch' , 'Reference' , 'Note' , 'Date' , 'Account' ,'Line Type', 'Amount' , 'Created At'
        ];

        $columns = [
            'id' => [ 'type' => 'number'],
            'transaction_id' => [ 'type' => 'number'],
            'type' => [ 'type' => 'text'],
            'branch' => [ 'type' => 'text'],
            'reference' => [ 'type' => 'text'],
            'note' => [ 'type' => 'text'],
            'date' => [ 'type' => 'date'],
            'account' => [ 'type' => 'text'],
            'line_type' => [ 'type' => 'badge', 'class' => function($row) {
                return $row['line_type'] === 'debit' ? 'badge-success' : 'badge-danger';
            },'icon' => function($row) {
                // i want make up/down with color green/red arrow
                return $row['line_type'] === 'debit' ? 'fa-arrow-up text-success' : 'fa-arrow-down text-danger';
            }],
            'amount' => [ 'type' => 'decimal'],
            'created_at' => [ 'type' => 'datetime'],
        ];

        return view('livewire.admin.transactions.transaction-list',get_defined_vars());
    }
}
