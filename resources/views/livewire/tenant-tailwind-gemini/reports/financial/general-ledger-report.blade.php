<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.financial.general_ledger.title')" icon="fa-book" :render-table="false">

            @if(count($accounts))
                <div class="mb-4 flex flex-wrap gap-2" role="tablist">
                    @foreach($accounts as $account)
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition {{ $active_account === $account ? 'border-slate-900 bg-slate-900 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}"
                            wire:click="loadAccountLedger('{{ $account }}')"
                        >
                            <i class="fa fa-folder-open"></i>
                            <span>{{ $account }}</span>
                        </button>
                    @endforeach
                </div>

                <div>
                    @foreach($accounts as $i => $account)
                        <div class="{{ $active_account === $account ? 'block' : 'hidden' }}" id="tab{{ $i }}">
                            @if($active_account === $account)
                                <div class="mb-4">
                                    <div class="mb-3 flex items-center justify-between border-b border-slate-200 pb-3">
                                        <h5 class="mb-0 text-base font-semibold text-slate-900">
                                            <i class="fa fa-folder-open me-1 text-sky-600"></i> {{ $account }}
                                        </h5>
                                        <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                                            {{ count($report['ledger'][$account] ?? []) }} {{ __('general.pages.reports.financial.general_ledger.transactions') }}
                                        </span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('general.pages.reports.financial.general_ledger.date') }}</th>
                                                    <th>{{ __('general.pages.reports.financial.general_ledger.txn_id') }}</th>
                                                    <th>{{ __('general.pages.reports.financial.general_ledger.type') }}</th>
                                                    <th>{{ __('general.pages.reports.financial.general_ledger.amount') }}</th>
                                                    <th>{{ __('general.pages.reports.financial.general_ledger.description') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($report['ledger'][$account] ?? [] as $txn)
                                                    <tr>
                                                        <td>{{ dateTimeFormat($txn['date'], true, false) }}</td>
                                                        <td>{{ $txn['txn_id'] }}</td>
                                                        <td>{{ ucfirst($txn['type']) }}</td>
                                                        <td>
                                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $txn['type'] === 'debit' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                                                {{ currencyFormat($txn['amount'], true) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $txn['description'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-700">
                    <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.reports.financial.general_ledger.no_transactions') }}
                </div>
            @endif

    </x-tenant-tailwind-gemini.table-card>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
