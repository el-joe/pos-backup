<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.taxes.filters')"
        icon="fa-filter"
        collapse-id="adminTaxFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminTaxFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.taxes.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.taxes.search') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.taxes.search_placeholder') }}"
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.taxes.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.taxes.all') }}</option>
                    <option value="1">{{ __('general.pages.taxes.active') }}</option>
                    <option value="0">{{ __('general.pages.taxes.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.taxes.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.titles.taxes')" icon="fa-percent">
        <x-slot:actions>
            @adminCan('taxes.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.taxes.export') }}
                </button>
            @endadminCan
            @adminCan('taxes.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editTaxModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.taxes.new_tax') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.taxes.id') }}</th>
                <th>{{ __('general.pages.taxes.name') }}</th>
                <th>{{ __('general.pages.taxes.tax_registeration_number') }}</th>
                <th>{{ __('general.pages.taxes.percentage') }}</th>
                <th>{{ __('general.pages.taxes.status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.taxes.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($taxes as $tax)
            <tr>
                <td>{{ $tax->id }}</td>
                <td>{{ $tax->name }}</td>
                <td>{{ $tax->vat_number }}</td>
                <td>{{ $tax->rate ?? 0 }}%</td>
                <td>
                    <span class="badge badge-{{ $tax->active ? 'success' : 'danger' }}">
                        {{ $tax->active ? __('general.pages.taxes.active') : __('general.pages.taxes.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('taxes.update')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editTaxModal" wire:click="setCurrent({{ $tax->id }})" data-original-title="{{ __('general.pages.taxes.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan('taxes.delete')
                        <a href="javascript:void(0)" data-original-title="{{ __('general.pages.taxes.delete') }}" wire:click="deleteAlert({{ $tax->id }})">
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

        @if($taxes->hasPages())
            <x-slot:footer>
                {{ $taxes->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for taxes page --}}
    <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="editTaxModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Tax</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="taxName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="taxName" placeholder="Enter tax name">
                    </div>
                    <div class="form-group">
                        <label for="taxVatNumber">VAT Number</label>
                        <input type="text" class="form-control" wire:model="data.vat_number" id="taxVatNumber" placeholder="Enter tax registration number">
                    </div>
                    <div class="form-group">
                        <label for="taxRate">Rate</label>
                        <input type="number" class="form-control" wire:model="data.rate" id="taxRate" placeholder="Enter tax rate">
                    </div>
                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="taxActive" wire:model="data.active">
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
