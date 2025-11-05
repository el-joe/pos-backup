<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Stock Adjustments</h3>
            <a class="btn btn-primary" href="{{ route('admin.stocks.adjustments.create') }}">
                <i class="fa fa-plus"></i> New Stock Adjustment
            </a>
        </div>

        <div class="card-body">
            <x-table-component :rows="$stockAdjustments" :columns="$columns" :headers="$headers" />
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
@endpush
