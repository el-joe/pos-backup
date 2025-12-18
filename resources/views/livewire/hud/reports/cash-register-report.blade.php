<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="applyFilter" class="row g-3">
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">{{ __('general.pages.reports.common.from_date') }}</label>
                        <input type="date" id="from_date" wire:model.defer="from_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">{{ __('general.pages.reports.common.to_date') }}</label>
                        <input type="date" id="to_date" wire:model.defer="to_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="branch_id" class="form-label">{{ __('general.pages.reports.common.branch') }}</label>
                        <select id="branch_id" wire:model.defer="branch_id" class="form-select form-select-sm">
                            <option value="">{{ __('general.pages.reports.common.all_branches') }}</option>
                            @if(function_exists('branches'))
                                @foreach(branches() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="admin_id" class="form-label">{{ __('general.pages.reports.cash_register_report.admin_cashier') }}</label>
                        <select id="admin_id" wire:model.defer="admin_id" class="form-select form-select-sm">
                            <option value="">{{ __('general.pages.reports.cash_register_report.all_admins') }}</option>
                            @if(function_exists('admins'))
                                @foreach(admins() as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="fa fa-check-circle"></i> {{ __('general.pages.reports.common.apply') }}
                        </button>
                        <button type="button" wire:click="resetFilters" class="btn btn-secondary btn-sm">
                            <i class="fa fa-refresh"></i> {{ __('general.pages.reports.common.reset') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <!-- Cash Register Summary -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa fa-list-alt me-2"></i>
                <h5 class="mb-0">{{ __('general.pages.reports.cash_register_report.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.reports.cash_register_report.opened_at') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.closed_at') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.branch') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.admin') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.opening_balance') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_sales') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_sale_refunds') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_purchases') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_purchase_refunds') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_expenses') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_expense_refunds') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_deposits') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.total_withdrawals') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.closing_balance') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.status') }}</th>
                                <th>{{ __('general.pages.reports.cash_register_report.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registers as $register)
                                <tr>
                                    <td>{{ dateTimeFormat($register->opened_at, false,true) }}</td>
                                    <td>{{ dateTimeFormat($register->closed_at, false,true) ?? '-' }}</td>
                                    <td>{{ $register->branch?->name ?? $register->branch_id }}</td>
                                    <td>{{ $register->admin->name ?? $register->admin_id }}</td>
                                    <td class="text-end">{{ currencyFormat($register->opening_balance, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_sales, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_sale_refunds, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_purchases, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_purchase_refunds, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_expenses, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_expense_refunds, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_deposits, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->total_withdrawals, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($register->closing_balance, true) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $register->status == 'open' ? 'success' : 'danger' }}">
                                            {{ ucfirst($register->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $register->notes }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted">{{ __('general.pages.reports.cash_register_report.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>
</div>
