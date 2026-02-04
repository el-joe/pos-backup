<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ __('general.titles.customer_payable') }}</h5>
                <div class="text-muted small">
                    {{ $customer?->name }} — {{ __('general.pages.payables.total_due') }}:
                    <strong class="text-danger">{{ currencyFormat($totalDue ?? 0, true) }}</strong>
                </div>
            </div>

            <a href="{{ route('admin.users.list', ['type' => 'customer']) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> {{ __('general.pages.payables.back') }}
            </a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('general.pages.payables.amount') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ currency()->symbol }}</span>
                        <input type="number" class="form-control" wire:model="payment.amount" placeholder="{{ __('general.pages.payables.amount') }}">
                    </div>
                    @error('payment.amount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('general.pages.payables.account') }}</label>
                    <select class="form-select" wire:model="payment.account_id">
                        <option value="">{{ __('general.pages.payables.select_account') }}</option>
                        @foreach (($customer?->accounts ?? []) as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->paymentMethod?->name }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                    @error('payment.account_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('general.pages.payables.note') }}</label>
                    <input type="text" class="form-control" wire:model="payment.note" placeholder="{{ __('general.pages.payables.note_placeholder') }}">
                    @error('payment.note')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-success" wire:click="savePayment">
                        <i class="fa fa-check me-1"></i> {{ __('general.pages.payables.apply_payment') }}
                    </button>
                </div>

                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle me-1"></i>
                        {{ __('general.pages.payables.allocation_hint') }}
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

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.payables.due_orders') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.payables.invoice_number') }}</th>
                            <th>{{ __('general.pages.payables.date') }}</th>
                            <th>{{ __('general.pages.payables.branch') }}</th>
                            <th>{{ __('general.pages.payables.total') }}</th>
                            <th>{{ __('general.pages.payables.paid') }}</th>
                            <th>{{ __('general.pages.payables.due') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.sales.details', $sale->id) }}" class="text-decoration-none">
                                        {{ $sale->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ dateTimeFormat($sale->order_date, true, false) }}</td>
                                <td>{{ $sale->branch?->name ?? '—' }}</td>
                                <td>{{ currencyFormat($sale->grand_total_amount ?? 0, true) }}</td>
                                <td>{{ currencyFormat($sale->paid_amount ?? 0, true) }}</td>
                                <td><span class="badge bg-danger">{{ currencyFormat($sale->due_amount ?? 0, true) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    {{ __('general.pages.payables.no_due_orders') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
