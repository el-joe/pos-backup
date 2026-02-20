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
                        <div class="form-text">{{ __('general.pages.reports.financial.fixed_assets_report.purchase_date_hint') }}</div>
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
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.status') }}</label>
                        <select class="form-select form-select-sm select2" name="status">
                            <option value="">{{ __('general.pages.reports.financial.fixed_assets_report.all_statuses') }}</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_active') }}</option>
                            <option value="under_construction" {{ $status === 'under_construction' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_under_construction') }}</option>
                            <option value="disposed" {{ $status === 'disposed' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_disposed') }}</option>
                            <option value="sold" {{ $status === 'sold' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_sold') }}</option>
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
        $totalCost = 0;
        $totalAccum = 0;
        $totalNBV = 0;

        foreach(($report ?? []) as $asset) {
            $acc = (float) ($asset->accumulated_depreciation ?? 0);
            $cost = (float) ($asset->cost ?? 0);
            $totalCost += $cost;
            $totalAccum += $acc;
            $totalNBV += max(0, $cost - $acc);
        }
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.fixed_assets_report.assets_count') }}</div>
                    <div class="fs-4 fw-semibold">{{ $count }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.fixed_assets_report.total_cost') }}</div>
                    <div class="fs-4 fw-semibold">{{ currencyFormat($totalCost, true) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.fixed_assets_report.total_accumulated_depreciation') }}</div>
                    <div class="fs-4 fw-semibold">{{ currencyFormat($totalAccum, true) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">{{ __('general.pages.reports.financial.fixed_assets_report.total_net_book_value') }}</div>
                    <div class="fs-4 fw-semibold">{{ currencyFormat($totalNBV, true) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0"><i class="fa fa-building me-2"></i> {{ __('general.pages.reports.financial.fixed_assets_report.title') }}</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>{{ __('general.pages.fixed_assets.code') }}</th>
                                <th>{{ __('general.pages.fixed_assets.name') }}</th>
                                <th>{{ __('general.pages.reports.common.branch') }}</th>
                                <th>{{ __('general.pages.fixed_assets.purchase_date') }}</th>
                                <th class="text-end">{{ __('general.pages.fixed_assets.cost') }}</th>
                                <th class="text-end">{{ __('general.pages.reports.financial.fixed_assets_report.accumulated_depreciation') }}</th>
                                <th class="text-end">{{ __('general.pages.fixed_assets.net_book_value') }}</th>
                                <th class="text-center">{{ __('general.pages.fixed_assets.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report as $asset)
                                @php
                                    $acc = (float) ($asset->accumulated_depreciation ?? 0);
                                    $cost = (float) ($asset->cost ?? 0);
                                    $nbv = max(0, $cost - $acc);
                                @endphp
                                <tr>
                                    <td>{{ $asset->id }}</td>
                                    <td>{{ $asset->code }}</td>
                                    <td>{{ $asset->name }}</td>
                                    <td>{{ $asset->branch?->name ?? '—' }}</td>
                                    <td>{{ $asset->purchase_date ? dateTimeFormat($asset->purchase_date, true, false) : '—' }}</td>
                                    <td class="text-end">{{ currencyFormat($cost, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($acc, true) }}</td>
                                    <td class="text-end">{{ currencyFormat($nbv, true) }}</td>
                                    <td class="text-center">
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">{{ __('general.pages.reports.financial.fixed_assets_report.no_data') }}</td>
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
