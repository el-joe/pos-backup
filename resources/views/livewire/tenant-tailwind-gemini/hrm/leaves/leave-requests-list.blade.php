<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.titles.hrm_leave_requests')" icon="fa-filter" collapse-id="hrmLeaveRequestsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmLeaveRequestsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                <select class="form-select" wire:model.blur="filters.status">
                    <option value="all">{{ __('general.pages.hrm.all') }}</option>
                    @foreach ([App\Enums\LeaveRequestStatusEnum::PENDING, App\Enums\LeaveRequestStatusEnum::APPROVED, App\Enums\LeaveRequestStatusEnum::REJECTED] as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search_reason_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.employee') }}</label>
                <select class="form-select" wire:model.blur="filters.employee_id">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters"><i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}</button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_leave_requests')" icon="fa-calendar-minus-o">
        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.employee') }}</th>
                            <th>{{ __('general.pages.hrm.type') }}</th>
                <th>{{ __('general.pages.hrm.start') }}</th>
                <th>{{ __('general.pages.hrm.end') }}</th>
                <th>{{ __('general.pages.hrm.days') }}</th>
                <th>{{ __('general.pages.hrm.status') }}</th>
                <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
            </tr>
        </x-slot:head>
        @foreach($requests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->employee?->name ?? $r->employee_id }}</td>
                <td>{{ $r->leaveType?->name ?? $r->leave_type_id }}</td>
                <td>{{ optional($r->start_date)->format('Y-m-d') }}</td>
                <td>{{ optional($r->end_date)->format('Y-m-d') }}</td>
                <td>{{ numFormat($r->days) }}</td>
                <td>{{ $r->status?->label() ?? '-' }}</td>
                <td class="text-end text-nowrap">
                    @if(($r->status?->value ?? $r->status) === App\Enums\LeaveRequestStatusEnum::PENDING->value)
                        @adminCan('hrm_leaves.approve')
                            <button class="btn btn-sm btn-outline-success me-1" wire:click="approveAlert({{ $r->id }})"><i class="fa fa-check"></i></button>
                        @endadminCan
                        @adminCan('hrm_leaves.reject')
                            <button class="btn btn-sm btn-outline-danger" wire:click="rejectAlert({{ $r->id }})"><i class="fa fa-times"></i></button>
                        @endadminCan
                    @else
                        <span class="text-inverse text-opacity-50">-</span>
                    @endif
                </td>
            </tr>
        @endforeach
        <x-slot:footer>
            {{ $requests->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
