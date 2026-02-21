<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">{{ __('general.titles.refunds') }}</h3>
            </div>
            <div class="col-xs-6 text-right">
                <a class="btn btn-primary" href="{{ route('admin.refunds.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.refunds.new_refund') }}
                </a>
                <button type="button" class="btn btn-default" wire:click="$set('export','excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.refunds.export') }}
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('general.pages.refunds.order_type') }}</th>
                        <th>{{ __('general.pages.refunds.order_id') }}</th>
                        <th>{{ __('general.pages.refunds.items_count') }}</th>
                        <th>{{ __('general.pages.refunds.reason') }}</th>
                        <th>{{ __('general.pages.refunds.created_at') }}</th>
                        <th>{{ __('general.pages.refunds.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>

            <div class="pagination-wrapper t-a-c">
                {{ $refunds->links() }}
            </div>
        </div>
    </div>
</div>
