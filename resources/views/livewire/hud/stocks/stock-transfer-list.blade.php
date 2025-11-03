<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Stock Transfers</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit stock transfer --}}
                <a class="btn btn-primary"  href="{{ route('admin.stocks.transfers.create') }}">
                    <i class="fa fa-plus"></i> New Stock Transfer
                </a>
            </div>
        </div>

        <x-table-component :rows="$stockTransfers" :columns="$columns" :headers="$headers" />
    </div>

</div>
@push('styles')
@endpush
