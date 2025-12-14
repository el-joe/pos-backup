<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.sales.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.sales.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Invoice -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.sales.invoice_no') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.sales.search_placeholder') }}"
                            wire:model.blur="filters.search">
                    </div>

                    <!-- Filter by Customer -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.sales.customer') }}</label>
                        <select class="form-select" wire:model.live="filters.customer_id">
                            <option value="">{{ __('general.pages.sales.all_customers') }}</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter by Branch --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.sales.branch') }}</label>
                        <select class="form-select" wire:model.live="filters.branch_id">
                            <option value="">{{ __('general.pages.sales.all_branches_option') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- due amount switch --}}
                    {{-- <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="dueAmountSwitch"
                                   wire:model.live="filters.only_due_amount">
                            <label class="form-check-label" for="dueAmountSwitch">
                                Only Due Amount
                            </label>
                        </div>
                    </div> --}}

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.sales.reset') }}
                        </button>
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
            <h3 class="card-title mb-0">{{ __('general.pages.sales.selling_orders') }}</h3>
            <div class="d-flex align-items-center gap-2">
                @adminCan('sales.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.sales.export') }}
                </button>
                @endadminCan
                @adminCan('pos.create')
                <a class="btn btn-primary btn-sm" href="{{ route('admin.pos') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.sales.new_selling_order') }}
                </a>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('general.pages.sales.id') }}</th>
                            <th>{{ __('general.pages.sales.invoice_no') }}</th>
                            <th>{{ __('general.pages.sales.customer') }}</th>
                            <th>{{ __('general.pages.sales.branch') }}</th>
                            <th>{{ __('general.pages.sales.total_amount') }}</th>
                            <th>{{ __('general.pages.sales.due_amount') }}</th>
                            <th>{{ __('general.pages.sales.refund_status') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.sales.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->customer?->name }}</td>
                                <td>{{ $sale->branch?->name }}</td>
                                <td>{{ $sale->grand_total_amount ?? 0 }}</td>
                                <td>
                                    <span class="badge bg-danger">
                                        {{ number_format($sale->due_amount ?? 0, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sale->refund_status->colorClass() }}">
                                        {{ $sale->refund_status->label() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @adminCan('sales.show')
                                    <a href="{{ route('admin.sales.details', $sale->id) }}"
                                       class="btn btn-sm btn-primary me-1"
                                       title="{{ __('general.pages.sales.details') }}">
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
                                    @adminCan('sales.show-invoice')
                                    {{-- create 2 btn one for print and one for export to pdf --}}
                                    <a href="{{ route('sales.invoice', encodedData(['type' => '80mm','order_id'=>$sale->id, 'action' => 'print'])) }}"
                                       class="btn btn-sm btn-info ms-1"
                                       title="{{ __('general.pages.sales.print') }}"
                                       target="_blank">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <a href="{{ route('sales.invoice', encodedData(['type' => 'a4','order_id'=>$sale->id, 'action' => 'pdf'])) }}"
                                       class="btn btn-sm btn-warning ms-1"
                                       title="{{ __('general.pages.sales.export_pdf') }}"
                                       target="_blank">
                                        <i class="fa fa-file-pdf"></i>
                                    </a>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
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

    <!-- Payment Modal -->
    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">💰 {{ __('general.pages.sales.payment_modal_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label fw-bold">{{ __('general.pages.sales.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="{{ __('general.pages.sales.amount') }}">
                                <span class="input-group-text">
                                    {{ __('general.pages.sales.due') ?? 'Due' }}:
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
                    </div>

                    <div class="mt-3">
                        <label for="paymentNote" class="form-label fw-bold">{{ __('general.pages.sales.note') }}</label>
                        <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="{{ __('general.pages.sales.optional_notes') }}"></textarea>
                        @error('payment.note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="button" class="btn btn-success w-100 mt-3" wire:click="savePayment">
                        <i class="fa fa-check"></i> {{ __('general.pages.sales.save_payment') }}
                    </button>

                    <hr class="my-4">

                    <h5 class="text-primary fw-bold mb-3">{{ __('general.pages.sales.recent_payments') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th>{{ __('general.pages.sales.date') }}</th>
                                    <th>{{ __('general.pages.sales.amount') }}</th>
                                    <th>{{ __('general.pages.sales.method') }}</th>
                                    <th>{{ __('general.pages.sales.note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $payments = $current?->transactions
                                        ? $current?->transactions->whereIn('type', [
                                            App\Enums\TransactionTypeEnum::SALE_PAYMENT,
                                            App\Enums\TransactionTypeEnum::SALE_PAYMENT_REFUND,
                                        ])->load('lines') : [];
                                ?>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ carbon($payment->created_at)->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-success">{{ $payment->amount }}</span></td>
                                        <td>{{ $payment->account() ? ($payment->account('credit')->paymentMethod?->name ? $payment->account('credit')->paymentMethod?->name .' - '  : '' ) . $payment->account('credit')->name : 'N/A' }}</td>
                                        <td>{{ $payment->note }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">{{ __('general.pages.sales.no_payments') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.sales.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('styles')
@endpush
