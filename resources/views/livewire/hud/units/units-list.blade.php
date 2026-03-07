<div class="col-12">
    <x-hud.filter-card
        :title="__('general.pages.units.filters')"
        icon="fa-filter"
        collapse-id="hudUnitFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudUnitFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.units.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.units.search') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.units.search') }} ..."
                    wire:model.blur="filters.search">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.units.parent_unit') }}</label>
                <select class="form-select select2" name="filters.parent_id">
                    <option value="all" {{ ($filters['parent_id']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.units.all') }}</option>
                    <option value="0" {{ ($filters['parent_id']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.units.is_parent') }}</option>
                    @foreach($filterUnits as $parentUnit)
                        <option value="{{ $parentUnit->id }}" {{ ($filters['parent_id']??'all') == $parentUnit->id ? 'selected' : '' }}>{{ $parentUnit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.units.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.units.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.units.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.units.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.units.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.units.units')" icon="fa-balance-scale">
        <x-slot:actions>
            @adminCan('units.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.units.export') }}
                </button>
            @endadminCan
            @adminCan('units.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUnitModal" wire:click="$dispatch('unit-set-current',null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.units.new_unit') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.units.name') }}</th>
                <th>{{ __('general.pages.units.parent') }}</th>
                <th>{{ __('general.pages.units.count') }}</th>
                <th>{{ __('general.pages.units.status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.units.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($units as $unit)
            <tr>
                <td>{{ $unit->id }}</td>
                <td>{{ $unit->name }}</td>
                <td>{{ $unit->parent ? $unit->parent->name : __('general.pages.units.n_a') }}</td>
                <td>{{ $unit->count }}</td>
                <td>
                    <span class="badge bg-{{ $unit->active ? 'success' : 'danger' }}">
                        {{ $unit->active ? __('general.pages.units.active') : __('general.pages.units.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('units.update')
                        <button type="button"
                                class="btn btn-sm btn-primary me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editUnitModal"
                                wire:click="$dispatch('unit-set-current', {id : '{{ $unit->id }}'})">
                            <i class="fa fa-edit me-1"></i> {{ __('general.pages.units.edit') }}
                        </button>
                    @endadminCan
                    @adminCan('units.delete')
                        <button type="button"
                                class="btn btn-sm btn-danger"
                                wire:click="deleteAlert({{ $unit->id }})">
                            <i class="fa fa-trash me-1"></i> {{ __('general.pages.units.delete') }}
                        </button>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($units->hasPages())
            <x-slot:footer>
                {{ $units->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-hud.table-card>
</div>

@push('styles')
    @livewire('admin.units.unit-modal')
    @include('layouts.hud.partials.select2-script')
@endpush
