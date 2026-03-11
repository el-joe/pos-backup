<div class="col-12">
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
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.financial.balance_sheet.title')" icon="fa-balance-scale" :render-table="false">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                    <tbody>
                        {{-- ================= Assets ================= --}}
                        <tr class="bg-slate-100">
                            <th colspan="2" class="text-uppercase text-primary">{{ __('general.pages.reports.financial.balance_sheet.assets') }}</th>
                        </tr>
                        @foreach(($report['assets'] ?? []) as $type => $amount)
                        <tr>
                            <td>{{ \App\Enums\AccountTypeEnum::tryFrom($type)?->translatedLabel() ?? $type }}</td>
                            <td>{{ currencyFormat($amount, true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-sky-50 font-bold text-slate-900">
                            <td>{{ __('general.pages.reports.financial.balance_sheet.total_assets') }}</td>
                            <td>{{ currencyFormat($report['total_assets'] ?? 0, true) }}</td>
                        </tr>

                        {{-- ================= Liabilities ================= --}}
                        <tr class="bg-slate-100">
                            <th colspan="2" class="text-uppercase text-danger">{{ __('general.pages.reports.financial.balance_sheet.liabilities') }}</th>
                        </tr>
                        @foreach(($report['liabilities'] ?? []) as $type => $amount)
                        <tr>
                            <td>{{ \App\Enums\AccountTypeEnum::tryFrom($type)?->translatedLabel() ?? $type }}</td>
                            <td>{{ currencyFormat($amount, true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-rose-50 font-bold text-slate-900">
                            <td>{{ __('general.pages.reports.financial.balance_sheet.total_liabilities') }}</td>
                            <td>{{ currencyFormat($report['total_liabilities'] ?? 0, true) }}</td>
                        </tr>

                        {{-- ================= Equity ================= --}}
                        <tr class="bg-slate-100">
                            <th colspan="2" class="text-uppercase text-success">{{ __('general.pages.reports.financial.balance_sheet.equity') }}</th>
                        </tr>
                        @foreach(($report['equity'] ?? []) as $type => $amount)
                        <tr>
                            <td>{{ \App\Enums\AccountTypeEnum::tryFrom($type)?->translatedLabel() ?? $type }}</td>
                            <td>{{ currencyFormat($amount, true) }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-emerald-50 font-bold text-slate-900">
                            <td>{{ __('general.pages.reports.financial.balance_sheet.total_equity') }}</td>
                            <td>{{ currencyFormat($report['total_equity'] ?? 0, true) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
