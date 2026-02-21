<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.deferred_purchases') }}</h5>
            <div class="d-flex align-items-center gap-2">
                @adminCan('purchases.create')
                <a href="{{ route('admin.purchases.add', ['is_deferred' => 1]) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> {{ __('general.pages.purchases.add_deferred_purchase') }}
                </a>
                @endadminCan
                <a href="{{ route('admin.purchases.list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-list"></i> {{ __('general.pages.purchases.all_purchases') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.purchases.id') }}</th>
                            <th>{{ __('general.pages.purchases.ref_no') }}</th>
                            <th>{{ __('general.pages.purchases.supplier') }}</th>
                            <th>{{ __('general.pages.purchases.branch') }}</th>
                            <th>{{ __('general.pages.purchases.total_amount') }}</th>
                            <th>{{ __('general.pages.purchases.due_amount') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.purchases.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->ref_no }}</td>
                            <td>{{ $purchase->supplier?->name }}</td>
                            <td>{{ $purchase->branch?->name }}</td>
                            <td>{{ currencyFormat($purchase->total_amount ?? 0, true) }}</td>
                            <td>
                                <span class="badge bg-danger">{{ currencyFormat($purchase->due_amount ?? 0, true) }}</span>
                            </td>
                            <td class="text-nowrap">
                                @adminCan('purchases.show')
                                <a href="{{ route('admin.purchases.details', $purchase->id) }}" class="btn btn-sm btn-primary" title="{{ __('general.pages.purchases.details') }}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                @endadminCan

                                <button class="btn btn-sm btn-warning"
                                        onclick="if(confirm('{{ __('general.pages.purchases.confirm_receive_inventory_now') }}')) { @this.receiveInventory({{ $purchase->id }}) }"
                                        title="{{ __('general.pages.purchases.receive_inventory') }}">
                                    <i class="fa fa-truck"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ __('general.pages.purchases.no_deferred_purchases_pending_receipt') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $purchases->links('pagination::default5') }}
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
