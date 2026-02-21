<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.fixed_assets.fixed_asset') }}: {{ $asset->code }}</h5>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-primary"
                   href="{{ route('admin.depreciation-expenses.create', ['fixed_asset_id' => $asset->id]) }}">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.fixed_assets.add_asset_entry') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.name') }}</div>
                    <div>{{ $asset->name }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.branch') }}</div>
                    <div>{{ $asset->branch?->name ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.status') }}</div>
                    <div>
                        <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'under_construction' ? 'warning' : ($asset->status === 'sold' ? 'info' : 'secondary')) }}">
                            @if($asset->status === 'active')
                                {{ __('general.pages.fixed_assets.status_active') }}
                            @elseif($asset->status === 'under_construction')
                                {{ __('general.pages.fixed_assets.status_under_construction') }}
                            @elseif($asset->status === 'sold')
                                {{ __('general.pages.fixed_assets.status_sold') }}
                            @else
                                {{ __('general.pages.fixed_assets.status_disposed') }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.purchase_date') }}</div>
                    <div>{{ $asset->purchase_date ? dateTimeFormat($asset->purchase_date, true, false) : '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.cost') }}</div>
                    <div>{{ currencyFormat($asset->cost ?? 0, true) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.salvage_value') }}</div>
                    <div>{{ currencyFormat($asset->salvage_value ?? 0, true) }}</div>
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.useful_life_months') }}</div>
                    <div>{{ $asset->useful_life_months ?? 0 }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.depreciation_method') }}</div>
                    <div>
                        @if($asset->depreciation_method === 'declining_balance')
                            {{ __('general.pages.fixed_assets.method_declining_balance') }}
                        @elseif($asset->depreciation_method === 'double_declining_balance')
                            {{ __('general.pages.fixed_assets.method_double_declining_balance') }}
                        @else
                            {{ __('general.pages.fixed_assets.method_straight_line') }}
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.depreciation_rate') }}</div>
                    <div>{{ $asset->depreciation_rate ? number_format((float)$asset->depreciation_rate, 4).'%' : '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.monthly_depreciation') }}</div>
                    <div>{{ currencyFormat($asset->monthly_depreciation ?? 0, true) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold">{{ __('general.pages.fixed_assets.net_book_value') }}</div>
                    <div>{{ currencyFormat($asset->net_book_value ?? 0, true) }}</div>
                </div>

                @if($asset->note)
                    <div class="col-12">
                        <div class="fw-semibold">{{ __('general.pages.fixed_assets.note') }}</div>
                        <div>{{ $asset->note }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.fixed_assets.depreciation_history') }}</h5>
            <a class="btn btn-sm btn-primary" href="{{ route('admin.depreciation-expenses.create', ['fixed_asset_id' => $asset->id]) }}">
                <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.new_depreciation_expense') }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.depreciation_expenses.category') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.amount') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.note') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.depreciation_expenses.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depreciationExpenses as $expense)
                            <tr>
                                <td>{{ $expense->id }}</td>
                                <td>{{ $expense->category?->display_name ?? '—' }}</td>
                                <td>{{ currencyFormat($expense->amount ?? 0, true) }}</td>
                                <td>{{ $expense->expense_date }}</td>
                                <td>{{ $expense->note ?? '—' }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.depreciation-expenses.details', $expense->id) }}">
                                        {{ __('general.pages.depreciation_expenses.details') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('general.pages.depreciation_expenses.no_records') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $depreciationExpenses->links() }}
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.fixed_assets.lifespan_extensions') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.depreciation_expenses.amount') }}</th>
                            <th>{{ __('general.pages.fixed_assets.added_useful_life_months') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.date') }}</th>
                            <th>{{ __('general.pages.depreciation_expenses.note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lifespanExtensions as $extension)
                            <tr>
                                <td>{{ $extension->id }}</td>
                                <td>{{ currencyFormat($extension->amount ?? 0, true) }}</td>
                                <td>{{ $extension->added_useful_life_months ?? 0 }}</td>
                                <td>{{ $extension->extension_date ? dateTimeFormat($extension->extension_date, true, false) : '—' }}</td>
                                <td>{{ $extension->note ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('general.pages.fixed_assets.no_extensions') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
