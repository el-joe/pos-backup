<div class="space-y-6">
    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.supplier_payable')" :description="($supplier?->name ?? '—') . ' — ' . __('general.pages.payables.total_due') . ': ' . currencyFormat($totalDue ?? 0, true)" icon="fa-truck">
        <x-slot:actions>
            <a href="{{ route('admin.users.list', ['type' => 'supplier']) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> {{ __('general.pages.payables.back') }}
            </a>
        </x-slot:actions>

        <div class="grid gap-4 p-5 md:grid-cols-3">
            <div>
                    <label class="form-label">{{ __('general.pages.payables.amount') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ currency()->symbol }}</span>
                        <input type="number" class="form-control" wire:model="payment.amount" placeholder="{{ __('general.pages.payables.amount') }}">
                    </div>
                    @error('payment.amount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
            </div>

            <div>
                    <label class="form-label">{{ __('general.pages.payables.account') }}</label>
                    <select class="form-select" wire:model="payment.account_id">
                        <option value="">{{ __('general.pages.payables.select_account') }}</option>
                        @foreach (($paymentAccounts ?? []) as $acc)
                            @php
                                $paymentAccount = json_decode(json_encode($acc), true) ?? [];
                                $accountLabel = '';
                                if (!empty(data_get($paymentAccount, 'branch.name'))) {
                                    $accountLabel .= data_get($paymentAccount, 'branch.name') . ' - ';
                                }
                                if (!empty(data_get($paymentAccount, 'paymentMethod.name'))) {
                                    $accountLabel .= data_get($paymentAccount, 'paymentMethod.name') . ' - ';
                                }
                                $accountLabel .= data_get($paymentAccount, 'name', '');
                            @endphp
                            <option value="{{ data_get($paymentAccount, 'id') }}">{{ $accountLabel }}</option>
                        @endforeach
                    </select>
                    @error('payment.account_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
            </div>

            <div>
                    <label class="form-label">{{ __('general.pages.payables.note') }}</label>
                    <input type="text" class="form-control" wire:model="payment.note" placeholder="{{ __('general.pages.payables.note_placeholder') }}">
                    @error('payment.note')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
            </div>

            <div class="md:col-span-3 flex justify-end">
                    <button class="btn btn-success" wire:click="savePayment">
                        <i class="fa fa-check me-1"></i> {{ __('general.pages.payables.apply_payment') }}
                    </button>
            </div>

            <div class="md:col-span-3">
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle me-1"></i>
                        {{ __('general.pages.payables.allocation_hint') }}
                    </div>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.payables.due_orders')" icon="fa-file-invoice-dollar" :render-table="false">
        <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.payables.ref_no') }}</th>
                            <th>{{ __('general.pages.payables.date') }}</th>
                            <th>{{ __('general.pages.payables.branch') }}</th>
                            <th>{{ __('general.pages.payables.total') }}</th>
                            <th>{{ __('general.pages.payables.paid') }}</th>
                            <th>{{ __('general.pages.payables.due') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.purchases.details', $purchase->id) }}" class="text-decoration-none">
                                        {{ $purchase->ref_no }}
                                    </a>
                                </td>
                                <td>{{ dateTimeFormat($purchase->order_date, true, false) }}</td>
                                <td>{{ $purchase->branch?->name ?? '—' }}</td>
                                <td>{{ currencyFormat($purchase->total_amount ?? 0, true) }}</td>
                                <td>{{ currencyFormat($purchase->paid_amount ?? 0, true) }}</td>
                                <td><span class="badge bg-danger">{{ currencyFormat($purchase->due_amount ?? 0, true) }}</span></td>
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
    </x-tenant-tailwind-gemini.table-card>
</div>
