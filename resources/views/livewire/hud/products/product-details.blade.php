<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fa fa-cube me-2"></i>{{ $product->name }}
                </h5>
                <div class="text-muted small">
                    {{ __('general.pages.products.sku') }}: <strong>{{ $product->sku }}</strong>
                    @if($product->code)
                        <span class="mx-2">•</span>
                        {{ __('general.pages.products.code') }}: <strong>{{ $product->code }}</strong>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.products.list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-list"></i> {{ __('general.pages.products.products') }}
                </a>
                @adminCan('products.update')
                <a href="{{ route('admin.products.add-edit', $product->id) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i> {{ __('general.pages.products.edit') }}
                </a>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link {{ $tab === 'overview' ? 'active' : '' }}"
                       wire:click="setTab('overview')">
                        <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.products.tabs.overview') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link {{ $tab === 'stock' ? 'active' : '' }}"
                       wire:click="setTab('stock')">
                        <i class="fa fa-warehouse me-1"></i> {{ __('general.pages.products.tabs.stock') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link {{ $tab === 'sales' ? 'active' : '' }}"
                       wire:click="setTab('sales')">
                        <i class="fa fa-chart-line me-1"></i> {{ __('general.pages.products.tabs.sales') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link {{ $tab === 'purchases' ? 'active' : '' }}"
                       wire:click="setTab('purchases')">
                        <i class="fa fa-shopping-bag me-1"></i> {{ __('general.pages.products.tabs.purchases') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link {{ $tab === 'transfers' ? 'active' : '' }}"
                       wire:click="setTab('transfers')">
                        <i class="fa fa-exchange-alt me-1"></i> {{ __('general.pages.products.tabs.transfers') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link {{ $tab === 'adjustments' ? 'active' : '' }}"
                       wire:click="setTab('adjustments')">
                        <i class="fa fa-sliders-h me-1"></i> {{ __('general.pages.products.tabs.adjustments') }}
                    </a>
                </li>
            </ul>

            <div class="pt-3">
                @if($tab === 'overview')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2">{{ __('general.pages.products.details') }}</div>

                                @if($product->image_path)
                                    <img src="{{ $product->image_path }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">
                                @endif

                                <div class="small">
                                    <div class="mb-1"><strong>{{ __('general.pages.products.name') }}:</strong> {{ $product->name }}</div>
                                    <div class="mb-1"><strong>{{ __('general.pages.products.sku') }}:</strong> {{ $product->sku }}</div>
                                    <div class="mb-1"><strong>{{ __('general.pages.products.unit') }}:</strong> {{ $product->unit?->name }}</div>
                                    <div class="mb-1"><strong>{{ __('general.pages.products.category') }}:</strong> {{ $product->category?->name }}</div>
                                    <div class="mb-1"><strong>{{ __('general.pages.products.brand') }}:</strong> {{ $product->brand?->name }}</div>
                                    <div class="mb-1"><strong>{{ __('general.pages.products.alert_quantity') }}:</strong> {{ $product->alert_qty ?? 0 }}</div>
                                    <div class="mb-1">
                                        <strong>{{ __('general.pages.products.status') }}:</strong>
                                        <span class="badge bg-{{ $product->active ? 'success' : 'danger' }}">
                                            {{ $product->active ? __('general.pages.products.active') : __('general.pages.products.inactive') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2">{{ __('general.pages.products.quick_stock') }}</div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="alert alert-info mb-0">
                                            <div class="small text-muted">{{ __('general.pages.products.branch_stock') }}</div>
                                            <div class="fs-5 fw-bold">{{ $product->branch_stock }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-primary mb-0">
                                            <div class="small text-muted">{{ __('general.pages.products.all_stock') }}</div>
                                            <div class="fs-5 fw-bold">{{ $product->all_stock }}</div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.stocks.list') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-warehouse"></i> {{ __('general.pages.products.open_stocks_list') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($tab === 'stock')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.pages.products.branch') }}</th>
                                    <th>{{ __('general.pages.products.unit') }}</th>
                                    <th>{{ __('general.pages.products.qty') }}</th>
                                    <th>{{ __('general.pages.products.unit_cost') }}</th>
                                    <th>{{ __('general.pages.products.sell_price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                    <tr>
                                        <td>{{ $stock->branch?->name }}</td>
                                        <td>{{ $stock->unit?->name }}</td>
                                        <td>{{ round($stock->qty, 3) }}</td>
                                        <td>{{ number_format($stock->unit_cost ?? 0, 2) }}</td>
                                        <td>{{ number_format($stock->sell_price ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">{{ __('general.pages.products.no_stock_records') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($tab === 'sales')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.pages.sales.invoice_number') }}</th>
                                    <th>{{ __('general.pages.sales.customer') }}</th>
                                    <th>{{ __('general.pages.sales.branch') }}</th>
                                    <th>{{ __('general.pages.products.qty') }}</th>
                                    <th>{{ __('general.pages.sales.date') }}</th>
                                    <th>{{ __('general.pages.sales.details') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSalesItems as $item)
                                    <tr>
                                        <td>{{ $item->sale?->invoice_number }}</td>
                                        <td>{{ $item->sale?->customer?->name }}</td>
                                        <td>{{ $item->sale?->branch?->name }}</td>
                                        <td>{{ $item->actual_qty }}</td>
                                        <td>{{ dateTimeFormat($item->sale?->order_date) }}</td>
                                        <td class="text-nowrap">
                                            @if($item->sale)
                                                <a href="{{ route('admin.sales.details', $item->sale->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">{{ __('general.pages.products.no_sales_records') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($tab === 'purchases')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.pages.purchases.ref_no') }}</th>
                                    <th>{{ __('general.pages.purchases.supplier') }}</th>
                                    <th>{{ __('general.pages.purchases.branch') }}</th>
                                    <th>{{ __('general.pages.products.qty') }}</th>
                                    <th>{{ __('general.pages.purchases.order_date') }}</th>
                                    <th>{{ __('general.pages.purchases.details') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchaseItems as $item)
                                    <tr>
                                        <td>{{ $item->purchase?->ref_no }}</td>
                                        <td>{{ $item->purchase?->supplier?->name }}</td>
                                        <td>{{ $item->purchase?->branch?->name }}</td>
                                        <td>{{ $item->actual_qty }}</td>
                                        <td>{{ dateTimeFormat($item->purchase?->order_date) }}</td>
                                        <td class="text-nowrap">
                                            @if($item->purchase)
                                                <a href="{{ route('admin.purchases.details', $item->purchase->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">{{ __('general.pages.products.no_purchase_records') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($tab === 'transfers')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.pages.stock-transfers.ref_no') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.from_branch') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.to_branch') }}</th>
                                    <th>{{ __('general.pages.products.qty') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.transfer_date') }}</th>
                                    <th>{{ __('general.pages.stock-transfers.details_tab') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransferItems as $item)
                                    <tr>
                                        <td>{{ $item->stockTransfer?->ref_no }}</td>
                                        <td>{{ $item->stockTransfer?->fromBranch?->name }}</td>
                                        <td>{{ $item->stockTransfer?->toBranch?->name }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ dateTimeFormat($item->stockTransfer?->transfer_date) }}</td>
                                        <td class="text-nowrap">
                                            @if($item->stockTransfer)
                                                <a href="{{ route('admin.stocks.transfers.details', $item->stockTransfer->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">{{ __('general.pages.products.no_transfer_records') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($tab === 'adjustments')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.pages.stock-taking.adjustment_id') }}</th>
                                    <th>{{ __('general.pages.stock-taking.branch') }}</th>
                                    <th>{{ __('general.pages.stock-taking.current_stock') }}</th>
                                    <th>{{ __('general.pages.stock-taking.actual_stock') }}</th>
                                    <th>{{ __('general.pages.stock-taking.difference') }}</th>
                                    <th>{{ __('general.pages.stock-taking.details') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAdjustments as $adj)
                                    <tr>
                                        <td>{{ $adj->stock_taking_id }}</td>
                                        <td>{{ $adj->stockTaking?->branch?->name }}</td>
                                        <td>{{ round($adj->current_qty, 3) }}</td>
                                        <td>{{ round($adj->actual_qty, 3) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $adj->difference >= 0 ? 'success' : 'danger' }}">
                                                {{ round($adj->difference, 3) }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            @if($adj->stockTaking)
                                                <a href="{{ route('admin.stocks.adjustments.details', $adj->stock_taking_id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">{{ __('general.pages.products.no_adjustment_records') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
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
