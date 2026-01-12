<div>
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('general.pages.purchase_requests.request_details') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.branch') }}</label>
                        @if(admin()->branch_id == null)
                            <select class="form-select select2" name="data.branch_id">
                                <option value="">{{ __('general.pages.purchase_requests.select_branch') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.supplier_optional') }}</label>
                        <select class="form-select select2" name="data.supplier_id">
                            <option value="">{{ __('general.pages.purchase_requests.none') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ ($data['supplier_id']??0) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.request_no') }}</label>
                        <input type="text" class="form-control" wire:model="data.request_number" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.request_date') }}</label>
                        <input type="date" class="form-control" wire:model="data.request_date">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.status') }}</label>
                        <select class="form-select select2" name="data.status">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.discount_type') }}</label>
                        <select class="form-select select2" name="data.discount_type">
                            <option value="">{{ __('general.pages.purchase_requests.none') }}</option>
                            <option value="fixed">{{ __('general.pages.purchase_requests.fixed') }}</option>
                            <option value="percentage">{{ __('general.pages.purchase_requests.percentage') }}</option>
                        </select>
                    </div>
                    @if($data['discount_type'] ?? false)
                        <div class="col-md-4">
                            <label class="form-label">{{ __('general.pages.purchase_requests.discount_value') }}</label>
                            <input type="number" step="0.01" class="form-control" wire:model="data.discount_value">
                        </div>
                    @endif
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchase_requests.tax_percentage') }}</label>
                        <input type="number" step="0.01" class="form-control" wire:model="data.tax_percentage">
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __('general.pages.purchase_requests.note') }}</label>
                        <textarea class="form-control" rows="2" wire:model="data.note"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('general.pages.purchase_requests.products') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text"
                                   class="form-control"
                                   placeholder="{{ __('general.pages.purchase_requests.search_product_placeholder') }}"
                                   wire:model.live.debounce.800ms="product_search"
                                   x-data
                                   @reset-search-input.window="$el.value=''">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" style="min-width: 1300px;">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('general.pages.purchase_requests.product') }}</th>
                                <th style="width: 220px;">{{ __('general.pages.purchase_requests.unit') }}</th>
                                <th style="width: 120px;">{{ __('general.pages.purchase_requests.qty') }}</th>
                                <th style="width: 160px;">{{ __('general.pages.purchase_requests.purchase_price') }}</th>
                                <th style="width: 160px;">{{ __('general.pages.purchase_requests.discount_percentage') }}</th>
                                <th style="width: 160px;">{{ __('general.pages.purchase_requests.tax_percentage_short') }}</th>
                                <th style="width: 140px;">{{ __('general.pages.purchase_requests.x_margin') }}</th>
                                <th style="width: 160px;">{{ __('general.pages.purchase_requests.sell_price') }}</th>
                                <th style="width: 90px;">{{ __('general.pages.purchase_requests.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderProducts as $index => $product)
                                <tr>
                                    <td class="fw-semibold">{{ $product['name'] }}</td>
                                    <td>
                                        <select class="form-select select2" name="orderProducts.{{ $index }}.unit_id">
                                            @foreach ($product['units'] ?? [] as $unit)
                                                <option value="{{ $unit->id }}" {{ ($product['unit_id'] ?? 0) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="1" min="1" class="form-control" wire:model.live="orderProducts.{{ $index }}.qty">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.purchase_price">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.discount_percentage">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.tax_percentage">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.x_margin">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control" wire:model.live="orderProducts.{{ $index }}.sell_price">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteProduct({{ $index }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            @if (count($orderProducts) === 0)
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('general.pages.purchase_requests.no_products_yet') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary" wire:click="saveRequest">
                        <i class="fa fa-save me-1"></i> {{ __('general.pages.purchase_requests.save_request') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
@include('layouts.hud.partials.select2-script')
    <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>

@endpush
