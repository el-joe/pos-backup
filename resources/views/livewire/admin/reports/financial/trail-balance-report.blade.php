<div class="container-fluid">
    <x-admin.table-card :title="__('general.pages.reports.financial.trial_balance.transactions_list')" icon="fa-list" :render-table="false">
        @include('admin.partials.tableHandler',[
            'rows' => $transactionLines,
            'columns' => $columns,
            'headers' => $headers,
            'totals' => $totals,
        ])
    </x-admin.table-card>
</div>
