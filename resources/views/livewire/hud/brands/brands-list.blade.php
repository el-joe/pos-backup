<div class="col-12">
    <x-hud.filter-card
        :title="__('general.pages.brands.filters')"
        icon="fa-filter"
        collapse-id="hudBrandFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudBrandFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.brands.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.brands.search') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.brands.search') }} ..."
                    wire:model.blur="filters.search">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.brands.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.brands.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.brands.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.brands.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.brands.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.pages.brands.brands')" icon="fa-tags">
        <x-slot:actions>
            @adminCan('brands.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.brands.export') }}
                </button>
            @endadminCan
            @adminCan('brands.create')
                <button class="btn btn-theme" data-bs-toggle="modal" data-bs-target="#editBrandModal" wire:click="$dispatch('brand-set-current', { id : null })">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.brands.new_brand') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>{{ __('general.pages.brands.name') }}</th>
                <th>{{ __('general.pages.brands.status') }}</th>
                <th class="text-nowrap text-end">{{ __('general.pages.brands.action') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>
                    <span class="badge bg-{{ $brand->active ? 'success' : 'danger' }}">
                        {{ $brand->active ? __('general.pages.brands.active') : __('general.pages.brands.inactive') }}
                    </span>
                </td>
                <td class="text-end text-nowrap">
                    @adminCan('brands.update')
                        <button class="btn btn-sm btn-outline-primary me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#editBrandModal"
                            wire:click="$dispatch('brand-set-current', { id : '{{ $brand->id }}'})"
                            title="{{ __('general.pages.brands.edit') }}">
                            <i class="fa fa-pencil"></i>
                        </button>
                    @endadminCan
                    @adminCan('brands.delete')
                        <button class="btn btn-sm btn-outline-danger"
                            wire:click="deleteAlert({{ $brand->id }})"
                            title="{{ __('general.pages.brands.delete') }}">
                            <i class="fa fa-times"></i>
                        </button>
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
                {{ $brands->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-hud.table-card>
</div>

@push('styles')
    @livewire('admin.brands.brand-modal')
    @include('layouts.hud.partials.select2-script')
@endpush
