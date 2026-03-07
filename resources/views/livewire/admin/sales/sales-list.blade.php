<div class="col-sm-12">
    <x-admin.filter-card :title="__('general.pages.sales.filters')" icon="fa-filter" collapse-id="adminSalesFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button type="button" class="btn btn-default btn-sm" wire:click="$toggle('collapseFilters')" data-toggle="collapse" data-target="#adminSalesFilterCollapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.sales.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.sales.invoice_no') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.sales.search_placeholder') }}" wire:model.live="filters.search">
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.sales.due_filter') }}</label>
                <select class="form-control" wire:model.live="filters.due_filter">
                    <option value="all">{{ __('general.pages.sales.due_filter_all') }}</option>
                    <option value="paid">{{ __('general.pages.sales.due_filter_paid') }}</option>
                    <option value="unpaid">{{ __('general.pages.sales.due_filter_unpaid') }}</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.sales.customer') }}</label>
                <select class="form-control" wire:model.live="filters.customer_id">
                    <option value="">{{ __('general.pages.sales.all_customers') }}</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.sales.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.pages.sales.all_branches_option') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.sales.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.sales.selling_orders')" icon="fa-shopping-basket">
        <x-slot:actions>
            @adminCan('sales.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.sales.export') }}
                </button>
            @endadminCan
            @adminCan('pos.create')
                <a class="btn btn-primary btn-sm" href="{{ route('admin.pos') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.sales.new_selling_order') }}
                </a>
                <a class="btn btn-warning btn-sm" href="{{ route('admin.pos.deferred') }}">
                    <i class="fa fa-clock-o"></i> {{ __('general.titles.deferred_pos') }}
                </a>
                <a class="btn btn-default btn-sm" href="{{ route('admin.sales.deferred') }}">
                    <i class="fa fa-truck"></i> {{ __('general.titles.deferred_sales') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.sales.id') }}</th>
                <th>{{ __('general.pages.sales.invoice_no') }}</th>
                <th>{{ __('general.pages.sales.customer') }}</th>
                <th>{{ __('general.pages.sales.branch') }}</th>
                <th>{{ __('general.pages.sales.total_amount') }}</th>
                <th>{{ __('general.pages.sales.due_amount') }}</th>
                <th>{{ __('general.pages.sales.refund_status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.sales.action') }}</th>
            </tr>
        </x-slot:head>

        @foreach ($sales as $sale)
        <tr>
            <td>{{ $sale->id }}</td>
            <td>{{ $sale->invoice_number }}</td>
            <td>{{ $sale->customer?->name }}</td>
            <td>{{ $sale->branch?->name }}</td>
            <td>{{ $sale->grand_total_amount ?? 0 }}</td>
            <td>
                <span class="label label-danger">
                    {{ number_format($sale->due_amount ?? 0, 2) }}
                </span>
            </td>
            <td>
                <span class="label label-{{ $sale->refund_status->colorClass() }}">
                    {{ $sale->refund_status->label() }}
                </span>
            </td>
            <td class="text-nowrap">
                <a href="{{ route('admin.sales.details', $sale->id) }}" data-toggle="tooltip" data-original-title="Details">
                    <i class="fa fa-pencil text-primary m-r-10"></i>
                </a>
                <a href="#" wire:click="setCurrent({{ $sale->id }})" data-toggle="modal" data-target="#paymentModal" data-id="{{ $sale->id }}">
                    <i class="fa fa-credit-card text-success"></i>
                </a>
            </td>
        </tr>
        @endforeach

        <x-slot:footer>
            {{ $sales->links() }}
        </x-slot:footer>
    </x-admin.table-card>

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
                                @foreach (collect($paymentAccounts ?? [])->filter() as $paymentAccount)
                                    <option value="{{ data_get($paymentAccount, 'id') }}">{{ data_get($paymentAccount, 'name') }}</option>
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
                                        App\Enums\TransactionTypeEnum::SALE_PAYMENT,
                                        App\Enums\TransactionTypeEnum::SALE_PAYMENT_REFUND,
                                    ])->load('lines') : [];
                                ?>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ carbon($payment->created_at)->format('Y-m-d') }}</td>
                                        <td><span class="label label-success">{{ $payment->amount }}</span></td>
                                        <td>{{ $payment->account() ?  ($payment->account('credit')->paymentMethod?->name ? $payment->account('credit')->paymentMethod?->name .' - '  : '' ) . $payment->account('credit')->name : 'N/A' }}</td>
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
