<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.titles.hrm_leave_types')" icon="fa-filter" collapse-id="hrmLeaveTypesFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmLeaveTypesFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.search') }}</label>
                <input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search_name_placeholder') }}" wire:model.blur="filters.search">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_leave_types')" icon="fa-calendar">
        <x-slot:actions>
            @adminCan('hrm_leaves.create')
                <button class="btn btn-sm btn-theme" data-bs-toggle="modal" data-bs-target="#editHrmLeaveTypeModal" wire:click="$dispatch('hrm-leave-type-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.name') }}</th>
                <th>{{ __('general.pages.hrm.yearly_allowance') }}</th>
                <th>{{ __('general.pages.hrm.paid') }}</th>
                <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
            </tr>
        </x-slot:head>

        @foreach($types as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->name }}</td>
                <td>{{ numFormat($t->yearly_allowance) }}</td>
                <td>
                    <span class="badge bg-{{ $t->is_paid ? 'success' : 'secondary' }}">
                        {{ $t->is_paid ? __('general.pages.hrm.yes') : __('general.pages.hrm.no') }}
                    </span>
                </td>
                <td class="text-end text-nowrap">
                    @adminCan('hrm_leaves.update')
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editHrmLeaveTypeModal" wire:click="$dispatch('hrm-leave-type-set-current', { id: {{ $t->id }} })">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endadminCan
                    @adminCan('hrm_leaves.delete')
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $t->id }})">
                            <i class="fa fa-times"></i>
                        </button>
                    @endadminCan
                </td>
            </tr>
        @endforeach

        <x-slot:footer>
            {{ $types->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.leaves.leave-type-modal')
@endpush
