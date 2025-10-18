<div class="white-box">
    <h3 class="box-title">Withholding Tax Report</h3>

    <!-- Filter Card -->
    <div class="card section-card m-b-20">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">From</label>
                    <input type="date" class="form-control" wire:model.defer="from_date">
                </div>
                <div class="col-sm-4">
                    <label class="control-label">To</label>
                    <input type="date" class="form-control" wire:model.defer="to_date">
                </div>
                <div class="col-sm-4 d-flex align-items-end justify-content-end" style="padding-top: 25px">
                    <button wire:click="resetDates" class="btn btn-default">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Card -->
    <div class="card section-card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr style="background:#e3f2fd;">
                        <th>#</th>
                        <th>Account</th>
                        <th>Type</th>
                        <th>Supplier / Customer</th>
                        <th class="text-right">Withholding Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @forelse($report as $row)
                        @php $total += $row->withholding_amount; @endphp
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->account_name }}</td>
                            <td>{{ $row->party_type }}</td>
                            <td>{{ $row->party_name ?? 'N/A' }}</td>
                            <td class="text-right">{{ number_format($row->withholding_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No withholding tax data found for selected dates.</td>
                        </tr>
                    @endforelse

                    @if(count($report))
                        <tr style="font-weight:600; background:#f1f8e9;">
                            <td colspan="3">Total</td>
                            <td colspan="2" class="text-right">{{ number_format($total, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
