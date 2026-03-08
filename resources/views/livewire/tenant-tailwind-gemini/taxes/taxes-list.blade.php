<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.taxes.filters')"
        icon="fa-filter"
        collapse-id="hudTaxFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudTaxFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.taxes.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.taxes.search') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.taxes.search_placeholder') }}"
                    wire:model.blur="filters.search">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.taxes.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.taxes.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.taxes.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.taxes.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.taxes.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.taxes')" icon="fa-percent">
        <x-slot:actions>
            @adminCan('taxes.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.taxes.export') }}
                </button>
            @endadminCan
            @adminCan('taxes.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTaxModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.taxes.new_tax') }}
                </button>
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
                    <span class="badge bg-{{ $tax->active ? 'success' : 'danger' }}">
                        {{ $tax->active ? __('general.pages.taxes.active') : __('general.pages.taxes.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('taxes.update')
                        <button type="button"
                                class="btn btn-sm btn-primary me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editTaxModal"
                                wire:click="setCurrent({{ $tax->id }})">
                            <i class="fa fa-edit me-1"></i> {{ __('general.pages.taxes.edit') }}
                        </button>
                    @endadminCan
                    @adminCan('taxes.delete')
                        <button type="button"
                                class="btn btn-sm btn-danger"
                                wire:click="deleteAlert({{ $tax->id }})">
                            <i class="fa fa-trash me-1"></i> {{ __('general.pages.taxes.delete') }}
                        </button>
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
                {{ $taxes->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    <!-- Modal -->
    <div class="modal fade" id="editTaxModal" tabindex="-1" aria-labelledby="editTaxModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxModalLabel">{{ $current?->id ? __('general.pages.taxes.edit_tax') : __('general.pages.taxes.new_tax') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taxName" class="form-label">{{ __('general.pages.taxes.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="taxName" placeholder="{{ __('general.pages.taxes.enter_tax_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="taxVatNumber" class="form-label">{{ __('general.pages.taxes.tax_registeration_number') }}</label>
                        <input type="text" class="form-control" wire:model="data.vat_number" id="taxVatNumber" placeholder="{{ __('general.pages.taxes.enter_tax_registration_number') }}">
                    </div>

                    <div class="mb-3">
                        <label for="taxRate" class="form-label">{{ __('general.pages.taxes.rate') }}</label>
                        <input type="number" class="form-control" wire:model="data.rate" id="taxRate" placeholder="{{ __('general.pages.taxes.enter_tax_rate') }}">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="taxActive" wire:model="data.active">
                        <label class="form-check-label" for="taxActive">{{ __('general.pages.taxes.is_active') }}</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.taxes.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.taxes.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
