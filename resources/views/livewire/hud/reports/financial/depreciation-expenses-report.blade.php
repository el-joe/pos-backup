<div class="container-fluid">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                <strong><i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}</strong>
                <button type="button" wire:click="resetFilters" class="btn btn-sm btn-secondary">
                    <i class="fa fa-refresh"></i> {{ __('general.pages.reports.common.reset') }}
                </button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>

                    <div class="col-sm-3">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.branch') }}</label>
                        <select class="form-select form-select-sm select2" name="branch_id">
                            <option value="">{{ __('general.pages.reports.common.all_branches') }}</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (string)$branch->id === (string)$branch_id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label class="form-label fw-semibold">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</label>
                        <select class="form-select form-select-sm select2" name="fixed_asset_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.all_assets') }}</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ (string)$asset->id === (string)$fixed_asset_id ? 'selected' : '' }}>{{ $asset->code }} - {{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label class="form-label fw-semibold">{{ __('general.pages.depreciation_expenses.category') }}</label>
                        <select class="form-select form-select-sm select2" name="expense_category_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.all_categories') }}</option>
                            @foreach($expenseCategories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)$cat->id === (string)$expense_category_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
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
    </div>

    @php
        $count = $report?->count() ?? 0;
        $totalAmount = 0;
        foreach(($report ?? []) as $row) {
            $totalAmount += (float) ($row->amount ?? 0);
        }
        $avg = $count ? ($totalAmount / $count) : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.depreciation_expenses_report.expenses_count') }}</div>
                    <div class="fs-4 fw-semibold">{{ $count }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.depreciation_expenses_report.total_amount') }}</div>
                    <div class="fs-4 fw-semibold">{{ currencyFormat($totalAmount, true) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.depreciation_expenses_report.avg_amount') }}</div>
                    <div class="fs-4 fw-semibold">{{ currencyFormat($avg, true) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-chart-area me-2"></i> {{ __('general.pages.reports.financial.depreciation_expenses_report.top_assets_title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.depreciation_expenses.fixed_asset') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.financial.depreciation_expenses_report.expenses_count') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.financial.depreciation_expenses_report.total_amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byAsset as $row)
                                <tr>
                                    <td>{{ $row['asset_name'] }}</td>
                                    <td class="text-end">{{ $row['expenses_count'] }}</td>
                                    <td class="text-end">{{ currencyFormat($row['total_amount'], true) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">{{ __('general.pages.reports.financial.depreciation_expenses_report.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-receipt me-2"></i> {{ __('general.pages.reports.financial.depreciation_expenses_report.title') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>{{ __('general.pages.reports.common.branch') }}</th>
                                <th>{{ __('general.pages.depreciation_expenses.fixed_asset') }}</th>
                                <th>{{ __('general.pages.depreciation_expenses.category') }}</th>
                                <th class="text-end">{{ __('general.pages.depreciation_expenses.amount') }}</th>
                                <th class="text-end">{{ __('general.pages.depreciation_expenses.tax_percentage') }}</th>
                                <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                                <th>{{ __('general.pages.depreciation_expenses.note') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report as $expense)
                                <tr>
                                    <td>{{ $expense->id }}</td>
                                    <td>{{ $expense->branch?->name ?? '—' }}</td>
                                    <td>{{ $expense->model?->code ?? '' }} {{ $expense->model?->name ?? $expense->model_id }}</td>
                                    <td>{{ $expense->category?->name ?? '—' }}</td>
                                    <td class="text-end">{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                    <td class="text-end">{{ $expense->tax_percentage ?? 0 }}%</td>
                                    <td>{{ $expense->expense_date }}</td>
                                    <td>{{ $expense->note ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">{{ __('general.pages.reports.financial.depreciation_expenses_report.no_data') }}</td>
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

@push('scripts')
    @include('layouts.hud.partials.select2-script')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
