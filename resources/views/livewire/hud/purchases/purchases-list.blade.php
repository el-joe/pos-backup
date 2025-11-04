<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Purchase Orders</h5>
            <a href="#" class="btn btn-primary">
                <i class="fa fa-plus"></i> New Purchase Order
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ref No.</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Due Amount</th>
                            <th>Refund Status</th>
                            <th class="text-nowrap">Action</th>
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
                                <a href="{{ route('admin.purchases.details', $purchase->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-success" wire:click="setCurrent({{ $purchase->id }})" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="{{ $purchase->id }}">
                                    <i class="fa fa-credit-card"></i>
                                </a>
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
                    <h5 class="modal-title" id="paymentModalLabel">💰 Add Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="Enter amount">
                                <span class="input-group-text">
                                    Due: <strong class="text-danger ms-1">{{ number_format($current->due_amount ?? 0, 2) }}</strong>
                                </span>
                            </div>
                            @error('payment.amount') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="paymentMethod" class="form-label">Supplier Account</label>
                            <select class="form-select" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">-- Select Account --</option>
                                @foreach (($current?->supplier?->accounts ?? []) as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->paymentMethod?->name }} - {{ $acc->name }}</option>
                                @endforeach
                            </select>
                            @error('payment.account_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-12">
                            <label for="paymentNote" class="form-label">Note</label>
                            <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="Optional notes..."></textarea>
                            @error('payment.note') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="button" class="btn btn-success w-100 mt-3" wire:click="savePayment">
                        <i class="fa fa-check"></i> Save Payment
                    </button>

                    <hr>

                    <h5 class="text-primary mb-3">Recent Payments</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Note</th>
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
                                        <td colspan="4" class="text-center text-muted">No payments recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>


@push('styles')
@endpush
