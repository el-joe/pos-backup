<div class="col-12">
    <!-- Filter Options Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-filter me-2"></i> {{ __('general.pages.reports.common.filter_options') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('general.pages.reports.common.from_date') }}</label>
                    <input type="date" class="form-control form-control-sm" wire:model.lazy="from_date">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('general.pages.reports.common.to_date') }}</label>
                    <input type="date" class="form-control form-control-sm" wire:model.lazy="to_date">
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

    <!-- General Ledger Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-book me-2"></i> {{ __('general.pages.reports.financial.general_ledger.title') }}
            </h5>
        </div>
        <div class="card-body">

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
                                                        <td>{{ \Carbon\Carbon::parse($txn['date'])->format('Y-m-d') }}</td>
                                                        <td>{{ $txn['txn_id'] }}</td>
                                                        <td>{{ ucfirst($txn['type']) }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $txn['type'] === 'debit' ? 'success' : 'danger' }}">
                                                                {{ number_format($txn['amount'], 2) }}
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

        </div>
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
