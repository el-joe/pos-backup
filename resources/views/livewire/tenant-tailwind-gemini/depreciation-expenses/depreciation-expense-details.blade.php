<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.depreciation_expenses.details_title') }} #{{ $expense->id }}</h5>
            <div class="d-flex gap-2">
                @if($expense->model)
                    <a class="btn btn-outline-primary" href="{{ route('admin.fixed-assets.details', $expense->model->id) }}">
                        <i class="fa fa-building me-1"></i> {{ __('general.pages.depreciation_expenses.view_asset') }}
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.branch') }}</div>
                    <div>{{ $expense->branch?->name ?? '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</div>
                    <div>{{ $expense->model?->code ?? '' }} {{ $expense->model?->name ?? $expense->model_id }}</div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.category') }}</div>
                    <div>{{ $expense->category?->display_name ?? '—' }}</div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.amount') }}</div>
                    <div>{{ currencyFormat($expense->amount ?? 0, true) }}</div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.tax_percentage') }}</div>
                    <div>{{ $expense->tax_percentage ?? 0 }}%</div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.date') }}</div>
                    <div>{{ $expense->expense_date }}</div>
                </div>

                @if($expense->note)
                    <div class="col-12">
                        <div class="fw-semibold">{{ __('general.pages.depreciation_expenses.note') }}</div>
                        <div>{{ $expense->note }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
