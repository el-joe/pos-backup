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
                            <td>{{ __('general.pages.cash_register.expected_closing_balance') }}</td>
                            <td class="text-end">{{ currencyFormat($aggregates['calculated_closing_balance'] ?? 0, true) }}</td>
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

        @if($currentRegister)
        <div class="card shadow-sm mt-4">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ __('general.pages.cash_register.recent_sessions') }}</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.cash_register.open_since') }}</th>
                            <th class="text-end">{{ __('general.pages.cash_register.closing_balance') }}</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSessions as $session)
                        <tr>
                            <td>{{ dateTimeFormat($session->opened_at) }}</td>
                            <td class="text-end">{{ currencyFormat($session->closing_balance, true) }}</td>
                            <td class="text-end">
                                @if($session->status === 'open')
                                <span class="badge bg-success"><i class="fa fa-check-circle"></i> {{ __('general.pages.cash_register.status_open') }}</span>
                                @else
                                <span class="badge bg-secondary"><i class="fa fa-lock"></i> {{ __('general.pages.cash_register.status_closed') }}</span>
                                @endif
                                @if($session->discrepancy && abs($session->discrepancy) > 0.009)
                                <span class="badge bg-warning text-dark"><i class="fa fa-triangle-exclamation"></i> {{ __('general.pages.cash_register.discrepancy') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ __('general.pages.cash_register.open_close_register') }}</h5>
            </div>
            <div class="card-body">
                @if($currentRegister)
                    <p>
                        <span class="badge bg-success"><i class="fa fa-check-circle"></i> {{ __('general.pages.cash_register.status_open') }}</span>
                        @if($currentRegister->currency_code)
                        <span class="badge bg-info">{{ $currentRegister->currency_code }}</span>
                        @endif
                    </p>
                    <p><strong>{{ __('general.pages.cash_register.open_since') }}:</strong> {{ dateTimeFormat($currentRegister->opened_at) }}</p>
                    <p><strong>{{ __('general.pages.cash_register.opening_balance') }}:</strong> {{ currencyFormat($currentRegister->opening_balance, true) }}</p>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="mb-2">{{ __('general.pages.cash_register.cash_deposit') }}</h6>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.pages.cash_register.amount') }}</label>
                            <input type="number" class="form-control" dir="ltr" wire:model="deposit_amount_input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.pages.cash_register.notes') }}</label>
                            <textarea class="form-control" wire:model="deposit_notes" rows="2"></textarea>
                        </div>
                        <button wire:click="depositCash" class="btn btn-primary w-100">{{ __('general.pages.cash_register.record_deposit') }}</button>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="mb-2">{{ __('general.pages.cash_register.cash_withdrawal') }}</h6>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.pages.cash_register.amount') }}</label>
                            <input type="number" class="form-control" dir="ltr" wire:model="withdrawal_amount_input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.pages.cash_register.notes') }}</label>
                            <textarea class="form-control" wire:model="withdrawal_notes" rows="2"></textarea>
                        </div>
                        <button wire:click="withdrawCash" class="btn btn-warning w-100">{{ __('general.pages.cash_register.record_withdrawal') }}</button>
                    </div>

                    <div class="mb-3" x-data="{
                            expected: {{ (float) ($aggregates['calculated_closing_balance'] ?? 0) }},
                            counted: {{ (float) ($closing_balance_input ?? 0) }},
                            get difference() { return (parseFloat(this.counted || 0) - parseFloat(this.expected || 0)).toFixed(2); }
                        }">
                        <label class="form-label">{{ __('general.pages.cash_register.expected_closing_balance') }}</label>
                        <input type="text" class="form-control text-end" dir="ltr" readonly value="{{ number_format($aggregates['calculated_closing_balance'] ?? 0, 2) }}">

                        <label class="form-label mt-2">{{ __('general.pages.cash_register.counted_cash') }}</label>
                        <input type="number" class="form-control text-end" dir="ltr" wire:model.live="closing_balance_input" x-model="counted">

                        <label class="form-label mt-2">{{ __('general.pages.cash_register.difference') }}</label>
                        <input type="text" class="form-control text-end" dir="ltr" readonly x-bind:value="difference">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('general.pages.cash_register.notes') }}</label>
                        <textarea class="form-control" wire:model="closing_notes" rows="3"></textarea>
                    </div>

                    @if($requiresOverride)
                        <div class="alert alert-warning">
                            <i class="fa fa-triangle-exclamation"></i>
                            {{ __('general.pages.cash_register.manager_override_required') }}
                            <strong>({{ number_format($discrepancyPreview, 2) }})</strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.pages.cash_register.override_reason') }}</label>
                            <textarea class="form-control" wire:model="override_reason" rows="2"></textarea>
                            @error('override_reason') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <button wire:click="closeRegister" class="btn btn-danger w-100">{{ __('general.pages.cash_register.confirm_override_and_close') }}</button>
                    @else
                        <button wire:click="confirmCloseRegister" class="btn btn-danger w-100">{{ __('general.pages.cash_register.close_register') }}</button>
                    @endif
                @else
                    <div class="mb-3">
                        <label class="form-label">{{ __('general.pages.cash_register.opening_balance') }}</label>
                        <input type="number" class="form-control" dir="ltr" wire:model="opening_balance_input">
                    </div>
                    @if(admin()->branch_id === null)
                        <div class="mb-3">
                            <label class="form-label">{{ __('general.pages.cash_register.select_branch') }}</label>
                            <select class="form-select select2" name="branchId">
                                <option value=""> {{ __('general.pages.cash_register.select_branch') }} </option>
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

@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
