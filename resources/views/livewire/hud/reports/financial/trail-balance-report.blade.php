<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-list"></i> Transactions List</h4>
        </div>
        <div class="panel-body">
            <x-table-component :rows="$transactionLines" :columns="$columns" :headers="$headers" :totals="$totals" />
        </div>
    </div>
</div>
