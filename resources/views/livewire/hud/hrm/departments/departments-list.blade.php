<div class="col-12">
    <x-hud.filter-card :title="__('general.titles.hrm_departments')" icon="fa-filter" collapse-id="hrmDepartmentsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmDepartmentsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search_department_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.hrm_departments')" icon="fa-building">
        <x-slot:actions>
            @adminCan('hrm_master_data.create')
                <button class="btn btn-sm btn-theme" data-bs-toggle="modal" data-bs-target="#editHrmDepartmentModal" wire:click="$dispatch('hrm-department-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.name') }}</th>
                <th>{{ __('general.pages.hrm.parent_department_id') }}</th>
                <th>{{ __('general.pages.hrm.manager') }}</th>
                <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
            </tr>
        </x-slot:head>

        @foreach($departments as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->name }}</td>
                <td>{{ $d->parent_id ?? '-' }}</td>
                <td>{{ $d->manager_id ?? '-' }}</td>
                <td class="text-end text-nowrap">
                    @adminCan('hrm_master_data.update')
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editHrmDepartmentModal" wire:click="$dispatch('hrm-department-set-current', { id: {{ $d->id }} })">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endadminCan
                    @adminCan('hrm_master_data.delete')
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $d->id }})">
                            <i class="fa fa-times"></i>
                        </button>
                    @endadminCan
                </td>
            </tr>
        @endforeach

        <x-slot:footer>
            {{ $departments->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.department-modal')
@endpush
