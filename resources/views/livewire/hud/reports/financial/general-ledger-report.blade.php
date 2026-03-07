<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.reports.financial.general_ledger.title')" icon="fa-book" :render-table="false">

            @if(count($accounts))
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    @foreach($accounts as $i => $account)
                        <li class="nav-item">
                            <a href="#"
                               class="nav-link {{ $active_account === $account ? 'active' : '' }}"
                               wire:click.prevent="loadAccountLedger('{{ $account }}')">
                                <i class="fa fa-folder-open me-1"></i> {{ $account }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    @foreach($accounts as $i => $account)
                        <div class="tab-pane fade {{ $active_account === $account ? 'show active' : '' }}" id="tab{{ $i }}">
                            @if($active_account === $account)
                                <div class="ledger-account mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                        <h5 class="mb-0">
                                            <i class="fa fa-folder-open me-1 text-primary"></i> {{ $account }}
                                        </h5>
                                        <span class="badge bg-info">
                                            {{ count($report['ledger'][$account] ?? []) }} {{ __('general.pages.reports.financial.general_ledger.transactions') }}
                                        </span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                                            <thead class="table-primary">
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
                                                            <span class="badge bg-{{ $txn['type'] === 'debit' ? 'success' : 'danger' }}">
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
                <div class="alert alert-info mb-0">
                    <i class="fa fa-info-circle me-1"></i> {{ __('general.pages.reports.financial.general_ledger.no_transactions') }}
                </div>
            @endif

    </x-hud.table-card>
</div>
@push('scripts')
    @include('layouts.hud.partials.daterange-picker-script')
@endpush
