<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.deferred_sales') }}</h5>

            <div class="d-flex align-items-center gap-2">
                @adminCan('pos.create')
                <a class="btn btn-primary btn-sm" href="{{ route('admin.pos.deferred') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.sales.new_deferred_sale') }}
                </a>
                @endadminCan
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.sales.index') }}">
                    <i class="fa fa-list"></i> {{ __('general.pages.sales.all_sales') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('general.pages.sales.id') }}</th>
                            <th>{{ __('general.pages.sales.invoice_number') }}</th>
                            <th>{{ __('general.pages.sales.customer') }}</th>
                            <th>{{ __('general.pages.sales.branch') }}</th>
                            <th>{{ __('general.pages.sales.total') }}</th>
                            <th>{{ __('general.pages.sales.due') }}</th>
                            <th class="text-center">{{ __('general.pages.sales.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->customer?->name }}</td>
                                <td>{{ $sale->branch?->name }}</td>
                                <td>{{ currencyFormat($sale->grand_total_amount ?? 0, true) }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ currencyFormat($sale->due_amount ?? 0, true) }}</span>
                                </td>
                                <td class="text-center text-nowrap">
                                    @adminCan('sales.show')
                                    <a href="{{ route('admin.sales.details', $sale->id) }}" class="btn btn-sm btn-primary" title="{{ __('general.pages.sales.details') }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    @endadminCan

                                    @adminCan('sales.pay')
                                    <button class="btn btn-sm btn-success"
                                            wire:click="setCurrent({{ $sale->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentModal">
                                        <i class="fa fa-credit-card"></i>
                                    </button>
                                    @endadminCan

                                    <button class="btn btn-sm btn-warning"
                                            onclick="if(confirm('{{ __('general.pages.sales.confirm_deliver_inventory_now') }}')) { @this.deliverInventory({{ $sale->id }}) }"
                                            title="{{ __('general.pages.sales.deliver_inventory') }}">
                                        <i class="fa fa-truck"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ __('general.pages.sales.no_deferred_sales_pending_delivery') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $sales->links('pagination::default5') }}
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

    <!-- Payment Modal (same UX as sales list) -->
    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">{{ __('general.pages.sales.payment_modal_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label fw-bold">{{ __('general.pages.sales.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ currency()->symbol }}</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="{{ __('general.pages.sales.amount') }}">
                                <span class="input-group-text">
                                    {{ __('general.pages.sales.due') }}:
                                    <strong class="text-danger ms-1">
                                        {{ number_format($current->due_amount ?? 0, 2) }}
                                    </strong>
                                </span>
                            </div>
                            @error('payment.amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="paymentMethod" class="form-label fw-bold">{{ __('general.pages.sales.customer_account') }}</label>
                            <select class="form-select" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.sales.select_account') }}</option>
                                @foreach (($current?->customer?->accounts ?? []) as $acc)
                                    <option value="{{ $acc->id }}">
                                        {{ $acc->paymentMethod?->name }} - {{ $acc->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment.account_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="paymentNote" class="form-label fw-bold">{{ __('general.pages.sales.note') }}</label>
                            <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="{{ __('general.pages.sales.optional_notes') }}"></textarea>
                            @error('payment.note')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-success w-100" wire:click="savePayment">
                                <i class="fa fa-check"></i> {{ __('general.pages.sales.save_payment') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.sales.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
