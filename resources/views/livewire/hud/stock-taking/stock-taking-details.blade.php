<div class="col-12">
    <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="fa fa-clipboard-list me-2"></i> {{ __('general.pages.stock-taking.stock_take_details') }} #{{ $stockTake->id }}</h3>
        </div>

        <div class="card-body">
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" id="stockTakeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($activeTab === 'details') active @endif"
                        wire:click="$set('activeTab', 'details')" id="details-tab" data-bs-toggle="tab"
                        data-bs-target="#details" type="button" role="tab">
                        <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.stock-taking.details') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($activeTab === 'product_stocks') active @endif"
                        wire:click="$set('activeTab', 'product_stocks')" id="product-stocks-tab" data-bs-toggle="tab"
                        data-bs-target="#product_stocks" type="button" role="tab">
                        <i class="fa fa-cubes me-1"></i> {{ __('general.pages.stock-taking.product_stocks') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Details Tab -->
                <div class="tab-pane fade @if($activeTab === 'details') show active @endif" id="details" role="tabpanel">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="fa fa-info-circle me-2"></i> {{ __('general.pages.stock-taking.stock_take_details') }}
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="p-3 border rounded text-center">
                                <h6><i class="fa fa-building me-1"></i> {{ __('general.pages.stock-taking.branch') }}</h6>
                                <p class="mb-0">{{ $stockTake->branch?->name ?? __('general.messages.n_a') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded text-center">
                                <h6><i class="fa fa-calendar me-1"></i> {{ __('general.pages.stock-taking.date') }}</h6>
                                <p class="mb-0">{{ formattedDate($stockTake->date) }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded text-center">
                                <h6><i class="fa fa-clock me-1"></i> {{ __('general.pages.stock-taking.created_at') }}</h6>
                                <p class="mb-0">{{ formattedDateTime($stockTake->created_at) }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded text-center">
                                <h6><i class="fa fa-sticky-note me-1"></i> {{ __('general.pages.stock-taking.note') }}</h6>
                                <p class="mb-0">{{ $stockTake->note ?? __('general.messages.n_a') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Stocks Tab -->
                <div class="tab-pane fade @if($activeTab === 'product_stocks') show active @endif" id="product_stocks" role="tabpanel">
                    <h5 class="border-bottom pb-2 mb-3">
                        <i class="fa fa-cubes me-2"></i> {{ __('general.pages.stock-taking.product_stocks') }}
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('general.pages.stock-taking.product') }}</th>
                                    <th>{{ __('general.pages.stock-taking.unit') }}</th>
                                    <th>{{ __('general.pages.stock-taking.current_stock') }}</th>
                                    <th>{{ __('general.pages.stock-taking.actual_stock') }}</th>
                                    <th>{{ __('general.pages.stock-taking.difference') }}</th>
                                    <th>{{ __('general.pages.stock-taking.status') }}</th>
                                    <th>{{ __('general.pages.stock-taking.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockTake->products as $stProduct)
                                    @php
                                        $stock = $stProduct->stock;
                                        $difference = $stProduct->difference;
                                        if($difference > 0) {
                                            $badgeClass = 'bg-success';
                                            $status = __('general.pages.stock-taking.surplus');
                                            $sign = '+';
                                        } elseif($difference < 0) {
                                            $badgeClass = 'bg-danger';
                                            $status = __('general.pages.stock-taking.shortage');
                                            $sign = '';
                                        } else {
                                            $badgeClass = 'bg-secondary';
                                            $status = __('general.pages.stock-taking.no_change');
                                            $sign = '';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $stock->product?->name }}</td>
                                        <td>{{ $stock->unit?->name }}</td>
                                        <td>{{ $stProduct->current_qty }}</td>
                                        <td>{{ $stProduct->actual_qty }}</td>
                                        <td>{{ $sign }}{{ $difference }}</td>
                                        <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>
                                        <td>
                                            @if($stProduct->returned == 0)
                                                <button class="btn btn-warning btn-sm" wire:click="returnStockAlert({{ $stProduct->id }})">
                                                    <i class="fa fa-undo"></i> {{ __('general.pages.stock-taking.return') }}
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fa fa-check"></i> {{ __('general.pages.stock-taking.returned') }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Arrow -->
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
.table th, .table td {
    vertical-align: middle !important;
}
</style>

@endpush
