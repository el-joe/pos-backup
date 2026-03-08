<div class="container-fluid">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="flex justify-end mb-3">
            <button type="button" wire:click="resetFilters" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                <i class="fa fa-refresh"></i> {{ __('general.pages.reports.common.reset') }}
            </button>
        </div>
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
    </x-tenant-tailwind-gemini.filter-card>

    @php
        $reportItems = collect($report ?? []);
        $count = $reportItems->count();
        $totalCost = 0;
        $totalAccum = 0;
        $totalNBV = 0;

        foreach($reportItems as $asset) {
            $acc = (float) data_get($asset, 'accumulated_depreciation', 0);
            $cost = (float) data_get($asset, 'cost', 0);
            $totalCost += $cost;
            $totalAccum += $acc;
            $totalNBV += max(0, $cost - $acc);
        }
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('general.pages.reports.financial.fixed_assets_report.assets_count') }}</div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">{{ $count }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('general.pages.reports.financial.fixed_assets_report.total_cost') }}</div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">{{ currencyFormat($totalCost, true) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('general.pages.reports.financial.fixed_assets_report.total_accumulated_depreciation') }}</div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">{{ currencyFormat($totalAccum, true) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ __('general.pages.reports.financial.fixed_assets_report.total_net_book_value') }}</div>
                <div class="mt-3 text-3xl font-semibold text-slate-900">{{ currencyFormat($totalNBV, true) }}</div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.financial.fixed_assets_report.title')" icon="fa-building" :render-table="false">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                        <thead>
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
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $asset->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($asset->status === 'under_construction' ? 'bg-amber-100 text-amber-700' : ($asset->status === 'sold' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-700')) }}">
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
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
