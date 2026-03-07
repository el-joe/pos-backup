<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.brands.filters')"
        icon="fa-filter"
        collapse-id="adminBrandFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminBrandFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.brands.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.brands.search') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.brands.search') }} ..."
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.brands.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.brands.all') }}</option>
                    <option value="1">{{ __('general.pages.brands.active') }}</option>
                    <option value="0">{{ __('general.pages.brands.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.brands.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.pages.brands.brands')" icon="fa-tags">
        <x-slot:actions>
            @adminCan('brands.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.brands.export') }}
                </button>
            @endadminCan
            @adminCan('brands.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editBrandModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.brands.new_brand') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.brands.name') }}</th>
                <th>{{ __('general.pages.brands.status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.brands.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>
                    <span class="badge badge-{{ $brand->active ? 'success' : 'danger' }}">
                        {{ $brand->active ? __('general.pages.brands.active') : __('general.pages.brands.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('brands.update')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editBrandModal" wire:click="setCurrent({{ $brand->id }})" data-original-title="{{ __('general.pages.brands.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan('brands.delete')
                        <a href="javascript:void(0)" data-original-title="{{ __('general.pages.brands.delete') }}" wire:click="deleteAlert({{ $brand->id }})">
                            <i class="fa fa-close text-danger"></i>
                        </a>
                    @endadminCan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($brands->hasPages())
            <x-slot:footer>
                {{ $brands->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for brands page --}}
    <div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBrandModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="categoryName">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="Enter category name">
                        </div>
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="categoryActive" wire:model="data.active">
                                <span class="checkmark"></span> Is Active
                            </label>
                        </div>
                    </form>
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
