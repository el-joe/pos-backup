<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('general.pages.stock-taking.add_stock_take') }}</h3>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Details Section -->
                <div class="col-md-5">
                    <div class="card section-card">
                        <div class="card-body">
                            <h4 class="section-title">
                                <i class="fa fa-info-circle text-primary me-2"></i> {{ __('general.pages.stock-taking.details') }}
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="branch_id" class="form-label">{{ __('general.pages.stock-taking.branch') }}</label>
                                        @if(admin()->branch_id == null)
                                        <select id="branch_id" class="form-select" wire:model.live="data.branch_id">
                                            <option value="">{{ __('general.pages.stock-taking.select_branch') }}</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">{{ __('general.pages.stock-taking.date') }}</label>
                                        <input type="date" id="date" class="form-control" wire:model="data.date">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="note" class="form-label">{{ __('general.pages.stock-taking.note') }}</label>
                                        <textarea id="note" class="form-control" wire:model="data.note" placeholder="{{ __('general.pages.stock-taking.optional_note') }}"></textarea>
                                    </div>
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

                <!-- Products Section -->
                <div class="col-md-7">
                    <div class="card section-card">
                        <div class="card-body">
                            <h4 class="section-title">
                                <i class="fa fa-cubes text-success me-2"></i> {{ __('general.pages.stock-taking.products') }}
                            </h4>

                            @if($data['branch_id'] ?? false)
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="product_search"
                                            placeholder="{{ __('general.pages.stock-taking.search_product_placeholder') }}"
                                            onkeydown="productSearchEvent(event)"
                                        >
                                    </div>
                                </div>

                                <div class="products-container mt-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover align-middle">
                                            <thead class="">
                                                <tr>
                                                    <th>{{ __('general.pages.stock-taking.product') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.unit') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.current_stock') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.actual_stock') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.difference') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.total_cost') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.status') }}</th>
                                                    <th>{{ __('general.pages.stock-taking.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stocks as $stock)
                                                    @foreach($stock as $unit)
                                                        <tr>
                                                            <td>{{ $unit['product_name'] }}</td>
                                                            <td>{{ $unit['unit_name'] }}</td>
                                                            <td>{{ $unit['current_stock'] }}</td>
                                                            <td>
                                                                <input type="number" class="form-control" id="countedStock_{{ $unit['product_id'] }}_{{ $unit['unit_id'] }}" min="0" wire:model.lazy="data.countedStock.{{ $unit['product_id'] }}.{{ $unit['unit_id'] }}">
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $currentStock = $unit['current_stock'];
                                                                    $actualStock = $data['countedStock'][$unit['product_id']][$unit['unit_id']] ?? 0;
                                                                    $difference = $actualStock - $currentStock;

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
                                                                {{ $sign }}{{ $difference }}
                                                            </td>
                                                            <td>{{ number_format($unit['unit_cost'] * $difference , 2) }}</td>
                                                            <td>
                                                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm btn-danger" wire:click="removeProductStock({{ $unit['product_id'] }}, {{ $unit['unit_id'] }})">
                                                                    <i class="fa fa-trash"></i> {{ __('general.pages.stock-taking.remove') }}
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">{{ __('general.pages.stock-taking.select_branch_to_view_products') }}</div>
                            @endif
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="col-12 text-center">
                    <button class="btn btn-primary save-stock-btn"
                        wire:click="save"
                        @if(!($data['branch_id'] ?? false) || count($stocks) == 0) disabled @endif>
                        <i class="fa fa-save"></i> {{ __('general.pages.stock-taking.save_stock_take') }}
                    </button>
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

@push('scripts')
<script>
    window.addEventListener('reset-search-input', event => {
        const input = document.getElementById('product_search');
        if (input) input.value = '';
    });

    function productSearchEvent(event) {
        if (event.key === "Enter") {
            @this.set('product_search', event.target.value);
            clearTimeout(window.productSearchTimeout);
        } else {
            clearTimeout(window.productSearchTimeout);
            window.productSearchTimeout = setTimeout(() => {
                @this.set('product_search', event.target.value);
            }, 1000);
        }
    }
</script>
@endpush

@push('styles')
{{-- <style>
    .section-card {
        border-radius: 18px;
        margin-top: 18px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        border: 1.5px solid #e3e6ed;
        background: #fff;
    }
    .section-card .card-body {
        padding: 32px 32px 24px 32px;
    }
    .section-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 24px;
        letter-spacing: 0.5px;
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    .save-stock-btn {
        min-width: 220px;
        max-width: 320px;
        padding: 14px 0;
        font-size: 20px;
        font-weight: 600;
        border-radius: 10px;
        margin-top: 24px;
    }
</style> --}}
@endpush
