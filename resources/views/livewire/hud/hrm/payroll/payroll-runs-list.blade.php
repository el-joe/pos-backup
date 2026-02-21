<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payroll Runs</h5>

            @adminCan('hrm_payroll.create')
                <button class="btn btn-sm btn-theme"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmPayrollRunModal"
                        wire:click="$dispatch('hrm-payroll-run-set-current', null)">
                    <i class="fa fa-plus me-1"></i> New
                </button>
            @endadminCan
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Total Payout</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($runs as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->month }}</td>
                                <td>{{ $r->year }}</td>
                                <td>{{ $r->status }}</td>
                                <td>{{ numFormat($r->total_payout) }}</td>
                                <td class="text-end text-nowrap">
                                    @adminCan('hrm_payroll.approve')
                                        @if(($r->status ?? null) === 'draft')
                                            <button class="btn btn-sm btn-outline-success me-1" wire:click="approveAlert({{ $r->id }})" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                    @endadminCan

                                    @adminCan('hrm_payroll.pay')
                                        @if(($r->status ?? null) === 'approved')
                                            <button class="btn btn-sm btn-outline-theme me-1" wire:click="payAlert({{ $r->id }})" title="Mark Paid">
                                                <i class="fa fa-money-bill"></i>
                                            </button>
                                        @endif
                                    @endadminCan

                                    @adminCan('hrm_payroll.update')
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHrmPayrollRunModal"
                                                wire:click="$dispatch('hrm-payroll-run-set-current', { id: {{ $r->id }} })">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan
                                    @adminCan('hrm_payroll.delete')
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $r->id }})">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endadminCan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $runs->links() }}
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

@push('scripts')
    @livewire('admin.hrm.payroll.payroll-run-modal')
@endpush

