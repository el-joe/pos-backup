<div class="col-12">
    <x-hud.filter-card :title="__('general.titles.hrm_employees')" icon="fa-filter" collapse-id="hrmEmployeesFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmEmployeesFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search_employee_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                <select class="form-select" wire:model.blur="filters.status">
                    <option value="all">{{ __('general.pages.hrm.all') }}</option>
                    <option value="active">{{ __('general.pages.hrm.active') }}</option>
                    <option value="suspended">{{ __('general.pages.hrm.suspended') }}</option>
                    <option value="terminated">{{ __('general.pages.hrm.terminated') }}</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.hrm_employees')" icon="fa-users">
        <x-slot:actions>
            @adminCan('hrm_master_data.create')
                <button class="btn btn-sm btn-theme" data-bs-toggle="modal" data-bs-target="#editHrmEmployeeModal" wire:click="$dispatch('hrm-employee-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.code') }}</th>
                <th>{{ __('general.pages.hrm.name') }}</th>
                <th>{{ __('general.pages.hrm.email') }}</th>
                <th>{{ __('general.pages.hrm.department') }}</th>
                <th>{{ __('general.pages.hrm.designation') }}</th>
                <th>{{ __('general.pages.hrm.manager') }}</th>
                <th>{{ __('general.pages.hrm.status') }}</th>
                <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
            </tr>
        </x-slot:head>

        @foreach($employees as $e)
            <tr>
                <td>{{ $e->id }}</td>
                <td>{{ $e->employee_code }}</td>
                <td>{{ $e->name }}</td>
                <td>{{ $e->email }}</td>
                <td>{{ $e->department?->name ?? '-' }}</td>
                <td>{{ $e->designation?->title ?? '-' }}</td>
                <td>{{ $e->manager?->name ?? '-' }}</td>
                <td>{{ __('general.pages.hrm.statuses.' . $e->status) }}</td>
                <td class="text-end text-nowrap">
                    @adminCan('hrm_master_data.update')
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editHrmEmployeeModal" wire:click="$dispatch('hrm-employee-set-current', { id: {{ $e->id }} })">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endadminCan
                    @adminCan('hrm_master_data.create')
                        <button class="btn btn-sm btn-outline-theme me-1" data-bs-toggle="modal" data-bs-target="#editHrmContractModal" wire:click="$dispatch('hrm-contract-set-employee', { employeeId: {{ $e->id }} })" title="{{ __('general.pages.hrm.new_replace_contract') }}">
                            <i class="fa fa-file-contract"></i>
                        </button>
                    @endadminCan
                    @adminCan('hrm_master_data.delete')
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $e->id }})">
                            <i class="fa fa-times"></i>
                        </button>
                    @endadminCan
                </td>
            </tr>
        @endforeach

        <x-slot:footer>
            {{ $employees->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.employee-modal')
    @livewire('admin.hrm.master-data.contract-modal')
@endpush
