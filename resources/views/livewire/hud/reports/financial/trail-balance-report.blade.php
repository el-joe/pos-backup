<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-list"></i> {{ __('general.pages.reports.financial.trial_balance.transactions_list') }}</h4>
        </div>
        <div class="panel-body">
            @include('admin.partials.tableHandler',[
                'rows' => $transactionLines,
                'columns' => $columns,
                'headers' => $headers,
                'totals' => $totals,
            ])
        </div>
    </div>
</div>
