<div class="col-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Stock Transfers</h3>
            <a class="btn btn-primary" href="{{ route('admin.stocks.transfers.create') }}">
                <i class="fa fa-plus"></i> New Stock Transfer
            </a>
        </div>

        <div class="card-body">
            @include('admin.partials.tableHandler',[
                'rows' => $stockTransfers,
                'columns' => $columns,
                'headers' => $headers
            ])
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* .card {
        border-radius: 16px;
        border: 1.5px solid #e3e6ed;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 16px 24px;
    }
    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    } */
</style>
@endpush
