<div class="container-fluid mt-4">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
                <form wire:submit.prevent="applyFilter" class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="branch_id" class="form-label">{{ __('general.pages.reports.branch_profitability.branch') }}</label>
                        <select id="branch_id" name="branch_id" class="form-select form-select-sm select2">
                            <option value="">{{ __('general.pages.reports.branch_profitability.all_branches') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id == $branch_id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
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
    </x-tenant-tailwind-gemini.filter-card>

    <div class="col-12">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.branch_profitability.title')" icon="fa-line-chart" :render-table="false">
            <div class="d-flex justify-content-between align-items-center px-3 pt-3">
                <div class="d-flex align-items-center">
                </div>
                <small class="text-warning">
                    <i class="fa fa-info-circle"></i> {{ __('general.pages.reports.branch_profitability.filter_other_income_note') }}
                </small>
            </div>
            <div class="pt-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.reports.branch_profitability.branch') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.branch_profitability.sales_revenue') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.branch_profitability.cogs') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.branch_profitability.expenses') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.branch_profitability.other_income') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.branch_profitability.net_profit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totals = [
                                    'sales_revenue' => 0,
                                    'cogs' => 0,
                                    'expenses' => 0,
                                    'other_income' => 0,
                                    'net_profit' => 0,
                                ];
                            @endphp

                            @forelse($report as $row)
                                @php
                                    $totals['sales_revenue'] += $row->sales_revenue ?? 0;
                                    $totals['cogs'] += $row->cogs ?? 0;
                                    $totals['expenses'] += $row->expenses ?? 0;
                                    $totals['other_income'] += $row->other_income ?? 0;
                                    $totals['net_profit'] += $row->net_profit ?? 0;
                                    $isProfit = ($row->net_profit ?? 0) >= 0;
                                @endphp

                                <tr class="{{ $isProfit ? 'table-success' : 'table-danger' }}">
                                    <td>
                                        <strong>{{ $row->branch_name }}</strong>
                                        <small class="text-muted d-block">(ID: {{ $row->branch_id ?? '-' }})</small>
                                    </td>
                                    <td class="text-end">{{ currencyFormat($row->sales_revenue ?? 0, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->cogs ?? 0, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->expenses ?? 0, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($row->other_income ?? 0, true) }}</td>
                                    <td class="text-end fw-bold text-{{ $isProfit ? 'success' : 'danger' }}">
                                        {{ currencyFormat($row->net_profit ?? 0, true) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fa fa-exclamation-circle"></i> {{ __('general.pages.reports.branch_profitability.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <th class="">{{ __('general.pages.reports.branch_profitability.totals') }}</th>
                                <th class="text-end">{{ currencyFormat($totals['sales_revenue'], true) }}</th>
                                <th class="text-end">{{ currencyFormat($totals['cogs'], true) }}</th>
                                <th class="text-end">{{ currencyFormat($totals['expenses'], true) }}</th>
                                <th class="text-end">{{ currencyFormat($totals['other_income'], true) }}</th>
                                <th class="text-end text-primary">{{ currencyFormat($totals['net_profit'], true) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
