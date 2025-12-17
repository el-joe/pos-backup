<div class="container-fluid mt-4">
    <!-- 🔍 Filter Options -->
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
                        <label for="branch_id" class="form-label">{{ __('general.pages.reports.branch_profitability.branch') }}</label>
                        <select id="branch_id" wire:model.defer="branch_id" class="form-select form-select-sm">
                            <option value="">{{ __('general.pages.reports.branch_profitability.all_branches') }}</option>
                            @if(function_exists('branches'))
                                @foreach(branches() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
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

    <!-- 📊 Branch Profitability Report -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fa fa-line-chart me-2"></i>
                    <h5 class="mb-0">{{ __('general.pages.reports.branch_profitability.title') }}</h5>
                </div>
                <small class="text-warning">
                    <i class="fa fa-info-circle"></i> {{ __('general.pages.reports.branch_profitability.filter_other_income_note') }}
                </small>
            </div>
            <div class="card-body p-0">
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
                                    <td class="text-end">{{ currency()->symbol }}{{ number_format($row->sales_revenue ?? 0, 2) }}</td>
                                    <td class="text-end">{{ currency()->symbol }}{{ number_format($row->cogs ?? 0, 2) }}</td>
                                    <td class="text-end">{{ currency()->symbol }}{{ number_format($row->expenses ?? 0, 2) }}</td>
                                    <td class="text-end">{{ currency()->symbol }}{{ number_format($row->other_income ?? 0, 2) }}</td>
                                    <td class="text-end fw-bold text-{{ $isProfit ? 'success' : 'danger' }}">
                                        {{ currency()->symbol }}{{ number_format($row->net_profit ?? 0, 2) }}
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
                                <th class="text-end">{{ currency()->symbol }}{{ number_format($totals['sales_revenue'], 2) }}</th>
                                <th class="text-end">{{ currency()->symbol }}{{ number_format($totals['cogs'], 2) }}</th>
                                <th class="text-end">{{ currency()->symbol }}{{ number_format($totals['expenses'], 2) }}</th>
                                <th class="text-end">{{ currency()->symbol }}{{ number_format($totals['other_income'], 2) }}</th>
                                <th class="text-end text-primary">{{ currency()->symbol }}{{ number_format($totals['net_profit'], 2) }}</th>
                            </tr>
                        </tfoot>
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
