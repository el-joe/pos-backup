<div class="col-sm-12">
    <x-admin.table-card title="Refunds" icon="fa-undo">
        <x-slot:actions>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.refunds.create', array_filter(['order_type' => $order_type, 'order_id' => $order_id], fn($v) => !is_null($v) && $v !== '')) }}">
                <i class="fa fa-plus"></i> New Refund
            </a>
            <button type="button" class="btn btn-default btn-sm" wire:click="$set('export','excel')">
                <i class="fa fa-file-excel-o"></i> Export
            </button>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>Order Type</th>
                <th>Order ID</th>
                <th>Items</th>
                <th>Total</th>
                <th>Reason</th>
                <th>Created At</th>
            </tr>
        </x-slot:head>

        @forelse ($refunds as $refund)
            <tr>
                <td>{{ $refund->id }}</td>
                <td>{{ class_basename($refund->order_type) }}</td>
                <td>{{ $refund->order_id }}</td>
                <td>{{ $refund->items?->count() ?? 0 }}</td>
                <td>{{ number_format($refund->total ?? 0, 2) }}</td>
                <td>{{ $refund->reason }}</td>
                <td>{{ $refund->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No refunds found.</td>
            </tr>
        @endforelse

        <x-slot:footer>
            {{ $refunds->links() }}
        </x-slot:footer>
    </x-admin.table-card>
</div>
