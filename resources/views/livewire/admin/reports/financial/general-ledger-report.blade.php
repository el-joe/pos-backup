<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><i class="glyphicon glyphicon-filter"></i> Filter Options</strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>From Date</label>
                    <input type="date" class="form-control input-sm" wire:model.lazy="from_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>To Date</label>
                    <input type="date" class="form-control input-sm" wire:model.lazy="to_date">
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="glyphicon glyphicon-book"></i> General Ledger</h4>
        </div>
        <div class="panel-body">

            @if(count($accounts))
            <ul class="nav nav-tabs ledger-tabs" role="tablist">
                    @foreach($accounts as $i => $account)
                        <li class="nav-item">
                            <a href="javascript:void(0);"
                               class="nav-link {{ $active_account === $account ? 'active' : '' }}"
                               wire:click.prevent="loadAccountLedger('{{ $account }}')">
                                <i class="fa fa-folder-open"></i> {{ $account }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            <div class="tab-content" style="margin-top:24px;">
                @foreach($accounts as $i => $account)
                <div role="tabpanel" class="tab-pane {{ $active_account === $account ? 'active in' : '' }}" id="tab{{ $i }}">
                    @if($active_account === $account)
                        <div class="ledger-account mb-4">
                            <div class="account-header">
                                <h5><i class="fa fa-folder-open"></i> {{ $account }}</h5>
                                <span class="label label-info">{{ count($report['ledger'][$account] ?? []) }} transactions</span>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
                                <thead>
                                    <tr style="background:#e3f2fd;">
                                        <th>Date</th>
                                        <th>Txn ID</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report['ledger'][$account]??[] as $txn)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($txn['date'])->format('Y-m-d') }}</td>
                                        <td>{{ $txn['txn_id'] }}</td>
                                        <td>{{ ucfirst($txn['type']) }}</td>
                                        <td><span class="label label-{{ $txn['type'] === 'debit' ? 'success' : 'danger' }}">{{ number_format($txn['amount'], 2) }}</span></td>
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
                <div class="alert alert-info">No transactions found for selected period.</div>
            @endif
        </div>
    </div>
</div>
