<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Transactions List</h3>
            </div>
        </div>

        <x-table-component :rows="$transactionLines" :columns="$columns" :headers="$headers" :totals="$totals" />
    </div>
</div>
@push('styles')
@endpush
