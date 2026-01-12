<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.sale_requests.sale_request') }}: {{ $request->quote_number }}</h5>
            {{-- <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" wire:click="convertToOrder">
                    <i class="fa fa-exchange-alt me-1"></i> {{ __('general.pages.sale_requests.convert_to_sale_order') }}
                </button>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.sale_requests.customer') }}</div>
                    <div>{{ $request->customer?->name ?? 'N/A' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.sale_requests.branch') }}</div>
                    <div>{{ $request->branch?->name ?? 'N/A' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.sale_requests.status') }}</div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $request->status?->colorClass() ?? 'secondary' }}">
                            {{ $request->status?->label() ?? (string) $request->status }}
                        </span>
                        <select class="form-select form-select-sm" style="max-width: 220px;" wire:change="updateStatus($event.target.value)">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ ($request->status?->value ?? (string) $request->status) == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.sale_requests.request_date') }}</div>
                    <div>{{ $request->request_date ? dateTimeFormat($request->request_date, true, false) : 'N/A' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.sale_requests.valid_until') }}</div>
                    <div>{{ $request->valid_until ? dateTimeFormat($request->valid_until, true, false) : 'N/A' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.sale_requests.total') }}</div>
                    <div>{{ currencyFormat($request->grand_total_amount ?? 0, true) }}</div>
                </div>
                @if($request->note)
                    <div class="col-12">
                        <div class="fw-semibold">{{ __('general.pages.sale_requests.note') }}</div>
                        <div>{{ $request->note }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.sale_requests.items') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('general.pages.sale_requests.product') }}</th>
                            <th>{{ __('general.pages.sale_requests.unit') }}</th>
                            <th>{{ __('general.pages.sale_requests.qty') }}</th>
                            <th>{{ __('general.pages.sale_requests.sell_price') }}</th>
                            <th>{{ __('general.pages.sale_requests.taxable') }}</th>
                            <th>{{ __('general.pages.sale_requests.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request->items as $item)
                            <tr>
                                <td>{{ $item->product?->name ?? $item->product_id }}</td>
                                <td>{{ $item->unit?->name ?? $item->unit_id }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ currencyFormat($item->sell_price, true) }}</td>
                                <td>{{ $item->taxable ? __('general.pages.sale_requests.yes') : __('general.pages.sale_requests.no') }}</td>
                                <td>{{ currencyFormat(($item->sell_price * $item->qty), true) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
