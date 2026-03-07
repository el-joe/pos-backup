<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
        <div class="row">
            <div class="col-sm-4">
                <label class="control-label">From</label>
                <input type="date" class="form-control input-sm" wire:model.defer="from_date">
            </div>
            <div class="col-sm-4">
                <label class="control-label">To</label>
                <input type="date" class="form-control input-sm" wire:model.defer="to_date">
            </div>
            <div class="col-sm-4 d-flex align-items-end justify-content-end" style="padding-top: 25px">
                <button wire:click="resetDates" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card title="Withholding Tax" icon="fa-list-alt" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom:0;">
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
    </x-admin.table-card>
</div>
