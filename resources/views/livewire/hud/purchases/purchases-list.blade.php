<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.purchases.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.purchases.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Ref No. -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchases.ref_no') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.purchases.search_placeholder') }}"
                            wire:model.blur="filters.ref_no">
                    </div>

                    <!-- Filter by Supplier -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchases.supplier') }}</label>
                        <select class="form-select" wire:model.live="filters.supplier_id">
                            <option value="">{{ __('general.pages.purchases.all_suppliers') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Branch -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchases.branch') }}</label>
                        <select class="form-select" wire:model.live="filters.branch_id">
                            <option value="">{{ __('general.pages.purchases.all_branches_option') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.purchases.status') }}</label>
                        <select class="form-select" wire:model.live="filters.status">
                            <option value="">{{ __('general.pages.purchases.all_statuses') }}</option>
                            @foreach (App\Enums\PurchaseStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.purchases.reset') }}
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
            <h5 class="mb-0">{{ __('general.pages.purchases.purchase_orders') }}</h5>
            <div class="d-flex align-items-center gap-2">
                @adminCan('purchases.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.purchases.export') }}
                </button>
                @endadminCan
                @adminCan('purchases.create')
                <a href="{{ route('admin.purchases.add') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('general.pages.purchases.new_purchase_order') }}
                </a>
                @endadminCan
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
                            <th>{{ __('general.pages.purchases.status') }}</th>
                            <th>{{ __('general.pages.purchases.total_amount') }}</th>
                            <th>{{ __('general.pages.purchases.due_amount') }}</th>
                            <th>{{ __('general.pages.purchases.refund_status') }}</th>
                            <th class="text-nowrap">{{ __('general.pages.purchases.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->ref_no }}</td>
                            <td>{{ $purchase->supplier->name }}</td>
                            <td>{{ $purchase->branch->name }}</td>
                            <td>
                                <span class="badge bg-{{ $purchase->status->colorClass() }}">
                                    {{ $purchase->status->label() }}
                                </span>
                            </td>
                            <td>{{ $purchase->total_amount ?? 0 }}</td>
                            <td>
                                <span class="text-{{ $purchase->due_amount > 0 ? 'danger' : 'success' }}">
                                    {{ number_format($purchase->due_amount ?? 0, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-{{ $purchase->refund_status->colorClass() }}">
                                    {{ $purchase->refund_status->label() }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                @adminCan('purchases.show')
                                <a href="{{ route('admin.purchases.details', $purchase->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="{{ __('general.pages.purchases.details') }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endadminCan
                                @adminCan('purchases.delete')
                                <a href="#" class="btn btn-sm btn-outline-success" wire:click="setCurrent({{ $purchase->id }})" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="{{ $purchase->id }}">
                                    <i class="fa fa-credit-card"></i>
                                </a>
                                @endadminCan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $purchases->links() }}
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
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">💰 {{ __('general.pages.purchases.payment_modal_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label">{{ __('general.pages.purchases.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="{{ __('general.pages.purchases.amount') }}">
                                <span class="input-group-text">
                                    {{ __('general.pages.purchases.due_amount') }}: <strong class="text-danger ms-1">{{ number_format($current->due_amount ?? 0, 2) }}</strong>
                                </span>
                            </div>
                            @error('payment.amount') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="paymentMethod" class="form-label">{{ __('general.pages.purchases.supplier_account') }}</label>
                            <select class="form-select" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">{{ __('general.pages.purchases.select_account') }}</option>
                                @foreach (($current?->supplier?->accounts ?? []) as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->paymentMethod?->name }} - {{ $acc->name }}</option>
                                @endforeach
                            </select>
                            @error('payment.account_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-12">
                            <label for="paymentNote" class="form-label">{{ __('general.pages.purchases.note') }}</label>
                            <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="{{ __('general.pages.purchases.optional_notes') }}"></textarea>
                            @error('payment.note') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="button" class="btn btn-success w-100 mt-3" wire:click="savePayment">
                        <i class="fa fa-check"></i> {{ __('general.pages.purchases.save_payment') }}
                    </button>

                    <hr>

                    <h5 class="text-primary mb-3">{{ __('general.pages.purchases.recent_payments') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>{{ __('general.pages.purchases.date') }}</th>
                                    <th>{{ __('general.pages.purchases.amount') }}</th>
                                    <th>{{ __('general.pages.purchases.method') }}</th>
                                    <th>{{ __('general.pages.purchases.note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $payments = $current?->transactions ? $current?->transactions->whereIn('type', [
                                        App\Enums\TransactionTypeEnum::PURCHASE_PAYMENT,
                                        App\Enums\TransactionTypeEnum::PURCHASE_PAYMENT_REFUND,
                                    ])->load('lines') : [];
                                ?>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ carbon($payment->created_at)->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-success">{{ $payment->amount }}</span></td>
                                        <td>{{ $payment->account() ?  ($payment->account()->paymentMethod?->name ? $payment->account()->paymentMethod?->name .' - '  : '' ) . $payment->account()->name : 'N/A' }}</td>
                                        <td>{{ $payment->note }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">{{ __('general.pages.purchases.no_payments') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('general.pages.purchases.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>


@push('styles')
@endpush
