<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Refunds</h3>
            </div>
            <div class="col-xs-6 text-right">
                <a class="btn btn-primary" href="{{ route('admin.refunds.create') }}">
                    <i class="fa fa-plus"></i> New Refund
                </a>
                <button type="button" class="btn btn-default" wire:click="$set('export','excel')">
                    <i class="fa fa-file-excel-o"></i> Export
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Type</th>
                        <th>Order ID</th>
                        <th>Items Count</th>
                        <th>Reason</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($refunds as $refund)
                        <tr>
                            <td>{{ $refund->id }}</td>
                            <td>{{ class_basename($refund->order_type) }}</td>
                            <td>
                                <a target="_blank" href="{{ class_basename($refund->order_type) == 'sale' ? route('admin.sales.details',$refund->order_id) : route('admin.purchases.details',$refund->order_id) }}">#{{ $refund->order_id }}</a>
                            </td>
                            <td>{{ $refund->items?->count() ?? 0 }}</td>
                            <td>{{ $refund->reason }}</td>
                            <td>{{ $refund->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No refunds found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-wrapper t-a-c">
                {{ $refunds->links() }}
            </div>
        </div>
    </div>
</div>
