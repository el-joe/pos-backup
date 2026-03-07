<div class="col-12">
    <x-hud.filter-card
        :title="__('general.pages.payment-methods.filters')"
        icon="fa-filter"
        collapse-id="hudPaymentMethodFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#hudPaymentMethodFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.payment-methods.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.payment-methods.search') }}</label>
                <input type="text" class="form-control"
                    placeholder="{{ __('general.pages.payment-methods.search_placeholder') }}"
                    wire:model.blur="filters.name">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.payment-methods.branch') }}</label>
                <select class="form-select select2" name="filters.branch_id">
                    <option value="all">{{ __('general.layout.all_branches') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.payment-methods.status') }}</label>
                <select class="form-select select2" name="filters.active">
                    <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.payment-methods.all') }}</option>
                    <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.payment-methods.active') }}</option>
                    <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.payment-methods.inactive') }}</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.payment-methods.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.payment-methods')" icon="fa-credit-card">
        <x-slot:actions>
            @adminCan('payment_methods.export')
                <button class="btn btn-outline-success" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.payment-methods.export') }}
                </button>
            @endadminCan
            @adminCan('payment_methods.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPaymentMethodModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.payment-methods.new_payment_method') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.payment-methods.id') }}</th>
                <th>{{ __('general.pages.payment-methods.name') }}</th>
                <th>{{ __('general.pages.payment-methods.branch_name') }}</th>
                <th>{{ __('general.pages.payment-methods.status') }}</th>
                <th class="text-center">{{ __('general.pages.payment-methods.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($paymentMethods as $paymentMethod)
            <tr>
                <td>{{ $paymentMethod->id }}</td>
                <td>{{ $paymentMethod->name }}</td>
                <td>{{ $paymentMethod->branch?->name ?? __('general.layout.all_branches') }}</td>
                <td>
                    <span class="badge bg-{{ $paymentMethod->active ? 'success' : 'danger' }}">
                        {{ $paymentMethod->active ? __('general.pages.payment-methods.active') : __('general.pages.payment-methods.inactive') }}
                    </span>
                </td>
                <td class="text-center">
                    @if(!in_array($paymentMethod->slug, ['cash', 'bank-transfer','check']))
                        @adminCan('payment_methods.update')
                            <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                data-bs-target="#editPaymentMethodModal"
                                wire:click="setCurrent({{ $paymentMethod->id }})" title="{{ __('general.pages.payment-methods.edit') }}">
                                <i class="fa fa-edit"></i>
                            </button>
                        @endadminCan
                        @adminCan('payment_methods.delete')
                            <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $paymentMethod->id }})" title="{{ __('general.pages.payment-methods.delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        @endadminCan
                    @else
                        <i class="fa fa-lock text-muted" title="{{ __('general.pages.payment-methods.lock_tooltip') }}"></i>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($paymentMethods->hasPages())
            <x-slot:footer>
                {{ $paymentMethods->links('pagination::default5') }}
            </x-slot:footer>
        @endif
    </x-hud.table-card>

    <!-- Modal -->
    <div class="modal fade" id="editPaymentMethodModal" tabindex="-1" aria-labelledby="editPaymentMethodModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $current?->id ? __('general.pages.payment-methods.edit_payment_method') : __('general.pages.payment-methods.new_payment_method') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label for="expenseCategoryName" class="form-label">{{ __('general.pages.payment-methods.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="expenseCategoryName" placeholder="{{ __('general.pages.payment-methods.enter_payment_method_name') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="branch_id" class="form-label">{{ __('general.pages.payment-methods.branch') }}</label>
                        @if(admin()->branch_id == null)
                        <select class="form-select select2" id="branch_id" name="data.branch_id">
                            <option value="">{{ __('general.layout.all_branches') }}</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                        @endif
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="expenseCategoryActive" wire:model="data.active">
                            <label class="form-check-label" for="expenseCategoryActive">{{ __('general.pages.payment-methods.is_active') }}</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.payment-methods.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.payment-methods.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
