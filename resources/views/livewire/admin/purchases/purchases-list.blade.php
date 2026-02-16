<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">{{ __('general.pages.purchases.purchase_orders') }}</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit branch --}}
                <a class="btn btn-primary" href="#">
                    <i class="fa fa-plus"></i> {{ __('general.pages.purchases.new_purchase_order') }}
                </a>
            </div>
        </div>

        <div class="row" style="margin-bottom:15px;">
            <div class="col-sm-3">
                <label class="control-label">{{ __('general.pages.purchases.due_filter') }}</label>
                <select class="form-control" wire:model.live="filters.due_filter">
                    <option value="all">{{ __('general.pages.purchases.due_filter_all') }}</option>
                    <option value="paid">{{ __('general.pages.purchases.due_filter_paid') }}</option>
                    <option value="unpaid">{{ __('general.pages.purchases.due_filter_unpaid') }}</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
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
                            <span class="label label-{{ $purchase->status->colorClass() }}">
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
                            <a href="{{ route('admin.purchases.details', $purchase->id) }}" data-toggle="tooltip" data-original-title="Details">
                                <i class="fa fa-eye text-primary m-r-10"></i>
                            </a>
                            <a href="#" wire:click="setCurrent({{ $purchase->id }})" data-toggle="modal" data-target="#paymentModal" data-id="{{ $purchase->id }}">
                                <i class="fa fa-credit-card text-success"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <!-- wider modal -->
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                    <h4 class="modal-title" id="paymentModalLabel">💰 Add Payment</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="paymentAmount" class="control-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" class="form-control" id="paymentAmount" wire:model="payment.amount" placeholder="Enter amount">
                                <span class="input-group-addon">
                                    Due:
                                    <strong class="text-danger">
                                        {{ number_format($current->due_amount ?? 0, 2) }}
                                    </strong>
                                </span>
                            </div>
                            @error('payment.amount') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="paymentMethod" class="control-label">Payment Account</label>
                            <select class="form-control" id="paymentMethod" wire:model="payment.account_id">
                                <option value="">-- Select Account --</option>
                                @foreach (($paymentAccounts ?? []) as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->paymentMethod?->name }} - {{ $acc->name }}</option>
                                @endforeach
                            </select>
                            @error('payment.account_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="paymentNote" class="control-label">Note</label>
                        <textarea class="form-control" id="paymentNote" wire:model="payment.note" rows="3" placeholder="Optional notes..."></textarea>
                        @error('payment.note') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <button type="button" class="btn btn-success btn-block" wire:click="savePayment">
                        <i class="glyphicon glyphicon-ok"></i> Save Payment
                    </button>

                    <hr />

                    <h4 class="text-primary">Recent Payments</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="bg-info">
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
                                        <td><span class="label label-success">{{ $payment->amount }}</span></td>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="glyphicon glyphicon-remove"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
@endpush
