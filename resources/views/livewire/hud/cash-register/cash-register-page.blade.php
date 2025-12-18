<div class="row g-4">
    @if(adminCan('cash_register.create'))
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ __('general.pages.cash_register.summary') }}</h5>
                <small class="text-muted">{{ __('general.pages.cash_register.aggregated_totals_across_registers') }}</small>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.cash_register.field') }}</th>
                            <th class="text-end">{{ __('general.pages.cash_register.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fw-semibold">
                            <td>{{ __('general.pages.cash_register.opening_balance') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['opening_balance'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_sales') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_sales'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_sale_refunds') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_sale_refunds'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_purchases') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_purchases'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_purchase_refunds') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_purchase_refunds'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_expenses') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_expenses'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_expense_refunds') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_expense_refunds'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_deposits') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_deposits'] ?? 0, true) }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('general.pages.cash_register.total_withdrawals') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['total_withdrawals'] ?? 0, true) }}</td>
                        </tr>
                        <tr class="fw-semibold table-light">
                            <td>{{ __('general.pages.cash_register.closing_balance') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['closing_balance'] ?? 0, true) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ __('general.pages.cash_register.open_close_register') }}</h5>
            </div>
            <div class="card-body">
                @if($currentRegister)
                <p><strong>{{ __('general.pages.cash_register.open_since') }}:</strong> {{ dateTimeFormat($currentRegister->opened_at) }}</p>
                <p><strong>{{ __('general.pages.cash_register.opening_balance') }}:</strong> {{ currencyFormat($currentRegister->opening_balance, true) }}</p>

                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.cash_register.closing_balance') }}</label>
                    <input type="number" class="form-control" wire:model="closing_balance_input">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.cash_register.notes') }}</label>
                    <textarea class="form-control" wire:model="closing_notes" rows="3"></textarea>
                </div>

                <button wire:click="closeRegister" class="btn btn-danger w-100">{{ __('general.pages.cash_register.close_register') }}</button>
                @else
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.cash_register.opening_balance') }}</label>
                    <input type="number" class="form-control" wire:model="opening_balance_input">
                </div>
                @if(admin()->branch_id === null)
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.cash_register.select_branch') }}</label>
                    <select class="form-select" wire:model="branchId">
                        <option value="">-- {{ __('general.pages.cash_register.select_branch') }} --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <button wire:click="openRegister" class="btn btn-success w-100">{{ __('general.pages.cash_register.open_register') }}</button>
                @endif
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>

        </div>
    </div>
    @else
        <div class="col-12">
            <div class="alert alert-danger">
                {{ __('general.messages.you_do_not_have_permission_to_access') }}
            </div>
        </div>
    @endif
</div>
