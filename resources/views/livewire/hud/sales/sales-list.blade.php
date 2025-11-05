<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Selling Orders</h3>
            <a class="btn btn-primary btn-sm" href="#">
                <i class="fa fa-plus"></i> New Selling Order
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Invoice No.</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th>Total Amount</th>
                            <th>Due Amount</th>
                            <th>Refund Status</th>
                            <th class="text-nowrap text-center">Action</th>
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
                                    <a href="{{ route('admin.sales.details', $sale->id) }}"
                                       class="btn btn-sm btn-primary me-1"
                                       title="Details">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-success"
                                            wire:click="setCurrent({{ $sale->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentModal">
                                        <i class="fa fa-credit-card"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $sales->links() }}
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
                    <h5 class="modal-title" id="paymentModalLabel">💰 Add Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="paymentAmount" class="form-label fw-bold">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="Enter amount">
                                <span class="input-group-text">
                                    Due:
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
                            <label for="paymentMethod" class="form-label fw-bold">Customer Account</label>
                            <select class="form-select" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">-- Select Account --</option>
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
                        <label for="paymentNote" class="form-label fw-bold">Note</label>
                        <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="Optional notes..."></textarea>
                        @error('payment.note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="button" class="btn btn-success w-100 mt-3" wire:click="savePayment">
                        <i class="fa fa-check"></i> Save Payment
                    </button>

                    <hr class="my-4">

                    <h5 class="text-primary fw-bold mb-3">Recent Payments</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Note</th>
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
