<div class="col-12">
    <x-hud.table-card :title="__('general.titles.refunds')" icon="fa-undo">
        <x-slot:actions>
            <a class="btn btn-primary" href="{{ route('admin.refunds.create') }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.refunds.new_refund') }}
            </a>
            <button type="button" class="btn btn-outline-success" wire:click="$set('export','excel')">
                <i class="fa fa-file-excel"></i> {{ __('general.pages.refunds.export') }}
            </button>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.refunds.order_type') }}</th>
                <th>{{ __('general.pages.refunds.order_id') }}</th>
                <th>{{ __('general.pages.refunds.items_count') }}</th>
                <th>{{ __('general.pages.refunds.reason') }}</th>
                <th>{{ __('general.pages.refunds.created_at') }}</th>
                <th>{{ __('general.pages.refunds.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($refunds as $refund)
            <tr>
                <td>{{ $refund->id }}</td>
                <td>{{ class_basename($refund->order_type) }}</td>
                <td>
                    @php($isSale = $refund->order_type === \App\Models\Tenant\Sale::class || class_basename($refund->order_type) === 'Sale')
                    <a target="_blank" href="{{ $isSale ? route('admin.sales.details',$refund->order_id) : route('admin.purchases.details',$refund->order_id) }}">#{{ $refund->order_id }}</a>
                </td>
                <td>{{ $refund->items?->count() ?? 0 }}</td>
                <td>{{ $refund->reason }}</td>
                <td>{{ $refund->created_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.refunds.details', $refund->id) }}">
                        <i class="fa fa-eye"></i> {{ __('general.pages.refunds.details') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">{{ __('general.pages.refunds.no_refunds') }}</td>
            </tr>
        @endforelse

        <x-slot:footer>
            {{ $refunds->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>
