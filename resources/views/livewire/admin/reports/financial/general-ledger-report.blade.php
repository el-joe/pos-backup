

<div class="white-box">
    <h3 class="box-title">General Ledger Report</h3>

    <div class="row mb-4">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" class="form-control" wire:model.lazy="from_date">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" class="form-control" wire:model.lazy="to_date">
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <h4 class="section-title"><i class="fa fa-book"></i> General Ledger</h4>

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
                                <span class="badge badge-info">{{ count($report['ledger'][$account] ?? []) }} transactions</span>
                            </div>
                            <table class="table table-striped table-bordered table-hover">
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

@push('styles')
<style>
.white-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px 28px;
    margin-top: 20px;
}
.section-title {
    font-size: 22px;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.section-card {
    border-radius: 16px;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
}
.ledger-tabs {
    border-bottom: 2px solid #e0e0e0;
}
.ledger-tabs .nav-link {
    color: #555;
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    margin-right: 4px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}
.ledger-tabs .nav-link:hover {
    background: #e3f2fd;
    color: #1976d2;
}
.ledger-tabs .nav-link.active {
    background: #1976d2;
    color: #fff !important;
    border-color: #1976d2;
}
.ledger-tabs > li.active > a {
    background: #1976d2;
    color: #fff !important;
    border-bottom: 2px solid #1976d2;
}
.ledger-account h5 {
    margin-bottom: 10px;
    color: #1976d2;
    font-size: 20px;
    font-weight: 600;
}
.account-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 10px;
}
.badge-info {
    background: #e3f2fd;
    color: #1976d2;
    font-size: 15px;
    padding: 6px 14px;
    border-radius: 12px;
}
.label-success {
    background: #43a047;
}
.label-danger {
    background: #e53935;
}
</style>
@endpush

@push('styles')
<style>
.white-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px 28px;
    margin-top: 20px;
}
.section-title {
    font-size: 22px;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.section-card {
    border-radius: 16px;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
}
.ledger-account h5 {
    margin-bottom: 10px;
    color: #1976d2;
}
</style>
@endpush
