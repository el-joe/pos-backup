<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Stock Adjustments</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit stock adjustment --}}
                <a class="btn btn-primary"  href="{{ route('admin.stocks.adjustments.create') }}">
                    <i class="fa fa-plus"></i> New Stock Adjustment
                </a>
            </div>
        </div>

        <x-table-component :rows="$stockAdjustments" :columns="$columns" :headers="$headers" />
    </div>

</div>
@push('styles')
@endpush
