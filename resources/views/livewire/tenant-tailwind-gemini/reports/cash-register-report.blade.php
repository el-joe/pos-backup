<div class="container-fluid">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
                <form wire:submit.prevent="applyFilter" class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="branch_id" class="form-label">{{ __('general.pages.reports.common.branch') }}</label>
                        <select id="branch_id" name="branch_id" class="form-select form-select-sm select2">
                            <option value="">{{ __('general.pages.reports.common.all_branches') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id == $branch_id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="admin_id" class="form-label">{{ __('general.pages.reports.cash_register_report.admin_cashier') }}</label>
                        <select id="admin_id" name="admin_id" class="form-select form-select-sm select2">
                            <option value="">{{ __('general.pages.reports.cash_register_report.all_admins') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $admin->id == $admin_id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 flex items-end gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                            <i class="fa fa-check-circle"></i> {{ __('general.pages.reports.common.apply') }}
                        </button>
                        <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i class="fa fa-refresh"></i> {{ __('general.pages.reports.common.reset') }}
                        </button>
                    </div>
                </form>
    </x-tenant-tailwind-gemini.filter-card>

    <div class="col-12">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.cash_register_report.title')" icon="fa-list-alt" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead >
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
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $register->status == 'open' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
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
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
        @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
