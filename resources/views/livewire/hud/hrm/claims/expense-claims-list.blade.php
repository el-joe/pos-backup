<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.hrm_expense_claims') }}</h5>

            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                        wire:click="$toggle('collapseFilters')"
                        data-bs-target="#hrmExpenseClaimsFilterCollapse">
                    <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
                </button>
            </div>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="hrmExpenseClaimsFilterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <select class="form-select" wire:model.blur="filters.status">
                            <option value="all">{{ __('general.pages.hrm.all') }}</option>
                            <option value="pending">{{ __('general.pages.hrm.pending') }}</option>
                            <option value="approved">{{ __('general.pages.hrm.approved') }}</option>
                            <option value="rejected">{{ __('general.pages.hrm.rejected') }}</option>
                            <option value="paid">{{ __('general.pages.hrm.paid') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.employee') }} {{ __('general.pages.hrm.id') }}</label>
                        <input type="number" class="form-control" wire:model.blur="filters.employee_id">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
                        </button>
                    </div>
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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.hrm.id') }}</th>
                            <th>{{ __('general.pages.hrm.employee') }}</th>
                            <th>{{ __('general.pages.hrm.date') }}</th>
                            <th>{{ __('general.pages.hrm.total') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                            <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->employee?->name ?? $c->employee_id }}</td>
                                <td>{{ optional($c->claim_date)->format('Y-m-d') }}</td>
                                <td>{{ numFormat($c->total_amount) }}</td>
                                <td>{{ $c->status }}</td>
                                <td class="text-end text-nowrap">
                                    @if($c->status === 'pending')
                                        @adminCan('hrm_claims.approve')
                                            <button class="btn btn-sm btn-outline-success me-1" wire:click="approveAlert({{ $c->id }})">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endadminCan
                                        @adminCan('hrm_claims.reject')
                                            <button class="btn btn-sm btn-outline-danger" wire:click="rejectAlert({{ $c->id }})">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endadminCan
                                    @else
                                        <span class="text-inverse text-opacity-50">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $claims->links() }}
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
