<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.units.filters')"
        icon="fa-filter"
        collapse-id="adminUnitFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminUnitFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.units.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.units.search') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.units.search') }} ..."
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.units.parent_unit') }}</label>
                <select class="form-control" wire:model.live="filters.parent_id">
                    <option value="">{{ __('general.pages.units.all') }}</option>
                    <option value="0">{{ __('general.pages.units.is_parent') }}</option>
                    @foreach($filterUnits as $parentUnit)
                        <option value="{{ $parentUnit->id }}">{{ $parentUnit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.units.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.units.all') }}</option>
                    <option value="1">{{ __('general.pages.units.active') }}</option>
                    <option value="0">{{ __('general.pages.units.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.units.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.units.units')" icon="fa-balance-scale">
        <x-slot:actions>
            @adminCan('units.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.units.export') }}
                </button>
            @endadminCan
            @adminCan('units.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUnitModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.units.new_unit') }}
                </a>
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
                    <span class="badge badge-{{ $unit->active ? 'success' : 'danger' }}">
                        {{ $unit->active ? __('general.pages.units.active') : __('general.pages.units.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('units.update')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editUnitModal" wire:click="setCurrent({{ $unit->id }})" data-original-title="{{ __('general.pages.units.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan('units.delete')
                        <a href="javascript:void(0)" data-original-title="{{ __('general.pages.units.delete') }}" wire:click="deleteAlert({{ $unit->id }})">
                            <i class="fa fa-close text-danger"></i>
                        </a>
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
                {{ $units->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    <div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-labelledby="editUnitModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoryName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="Enter category name">
                    </div>
                    <div class="form-group">
                        <label for="categoryParent">Parent Unit</label>
                        <select id="parent_id" wire:model.change="data.parent_id" class="form-control">
                            <option value="0">Is Parent</option>
                            @foreach ($parents as $parent)
                            {{recursiveChildrenForOptions($parent,'children','id','name',0)}}
                            @endforeach
                        </select>
                    </div>
                    @if(($data['parent_id']??0) != 0)
                        <div class="form-group">
                            <label for="count">Count</label>
                            <input type="number" step="any" wire:model="data.count" id="count" class="form-control">
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="categoryActive" wire:model="data.active">
                            <span class="checkmark"></span> Is Active
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
