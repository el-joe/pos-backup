<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.purchase_requests.purchase_request') }}: {{ $request->request_number }}</h5>
            {{-- <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" wire:click="convertToOrder">
                    <i class="fa fa-exchange-alt me-1"></i> {{ __('general.pages.purchase_requests.convert_to_purchase_order') }}
                </button>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.purchase_requests.supplier') }}</div>
                    <div>{{ $request->supplier?->name ?? __('general.messages.n_a') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.purchase_requests.branch') }}</div>
                    <div>{{ $request->branch?->name ?? __('general.messages.n_a') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.purchase_requests.status') }}</div>
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
                    <div class="fw-semibold">{{ __('general.pages.purchase_requests.request_date') }}</div>
                    <div>{{ $request->request_date ? dateTimeFormat($request->request_date, true, false) : __('general.messages.n_a') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.purchase_requests.total') }}</div>
                    <div>{{ currencyFormat($request->total_amount ?? 0, true) }}</div>
                </div>
                @if($request->note)
                    <div class="col-12">
                        <div class="fw-semibold">{{ __('general.pages.purchase_requests.note') }}</div>
                        <div>{{ $request->note }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.purchase_requests.items') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('general.pages.purchase_requests.product') }}</th>
                            <th>{{ __('general.pages.purchase_requests.unit') }}</th>
                            <th>{{ __('general.pages.purchase_requests.qty') }}</th>
                            <th>{{ __('general.pages.purchase_requests.purchase_price') }}</th>
                            <th>{{ __('general.pages.purchase_requests.discount_percentage') }}</th>
                            <th>{{ __('general.pages.purchase_requests.tax_percentage_short') }}</th>
                            <th>{{ __('general.pages.purchase_requests.sell_price') }}</th>
                            <th>{{ __('general.pages.purchase_requests.total_incl_tax') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request->items as $item)
                            <tr>
                                <td>{{ $item->product?->name ?? $item->product_id }}</td>
                                <td>{{ $item->unit?->name ?? $item->unit_id }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ currencyFormat($item->purchase_price, true) }}</td>
                                <td>{{ $item->discount_percentage }}</td>
                                <td>{{ $item->tax_percentage }}</td>
                                <td>{{ currencyFormat($item->sell_price, true) }}</td>
                                <td>{{ currencyFormat($item->total_after_tax ?? 0, true) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
