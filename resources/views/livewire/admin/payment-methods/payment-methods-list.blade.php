<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.payment-methods.filters')"
        icon="fa-filter"
        collapse-id="adminPaymentMethodFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminPaymentMethodFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.payment-methods.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.payment-methods.search') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.payment-methods.search_placeholder') }}"
                       wire:model.live="filters.name">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.payment-methods.branch') }}</label>
                <select class="form-control" wire:model.live="filters.branch_id">
                    <option value="">{{ __('general.layout.all_branches') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.payment-methods.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.payment-methods.all') }}</option>
                    <option value="1">{{ __('general.pages.payment-methods.active') }}</option>
                    <option value="0">{{ __('general.pages.payment-methods.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.payment-methods.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.titles.payment-methods')" icon="fa-credit-card">
        <x-slot:actions>
            @adminCan('payment_methods.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.payment-methods.export') }}
                </button>
            @endadminCan
            @adminCan('payment_methods.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPaymentMethodModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.payment-methods.new_payment_method') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.payment-methods.id') }}</th>
                <th>{{ __('general.pages.payment-methods.name') }}</th>
                <th>{{ __('general.pages.payment-methods.branch_name') }}</th>
                <th>{{ __('general.pages.payment-methods.status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.payment-methods.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($paymentMethods as $paymentMethod)
            <tr>
                <td>{{ $paymentMethod->id }}</td>
                <td>{{ $paymentMethod->name }}</td>
                <td>{{ $paymentMethod->branch?->name ?? __('general.layout.all_branches') }}</td>
                <td>
                    <span class="badge badge-{{ $paymentMethod->active ? 'success' : 'danger' }}">
                        {{ $paymentMethod->active ? __('general.pages.payment-methods.active') : __('general.pages.payment-methods.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @if(!in_array($paymentMethod->slug, ['cash', 'bank-transfer', 'check']))
                        @adminCan('payment_methods.update')
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editPaymentMethodModal" wire:click="setCurrent({{ $paymentMethod->id }})" data-original-title="{{ __('general.pages.payment-methods.edit') }}">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                        @endadminCan
                        @adminCan('payment_methods.delete')
                            <a href="javascript:void(0)" data-original-title="{{ __('general.pages.payment-methods.delete') }}" wire:click="deleteAlert({{ $paymentMethod->id }})">
                                <i class="fa fa-close text-danger m-r-10"></i>
                            </a>
                        @endadminCan
                    @else
                        <i class="fa fa-lock text-muted m-r-10" title="{{ __('general.pages.payment-methods.lock_tooltip') }}"></i>
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
                {{ $paymentMethods->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for payment methods --}}
    <div class="modal fade" id="editPaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentMethodModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentMethodModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Payment Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-6">
                        <label for="expenseCategoryName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="expenseCategoryName" placeholder="Enter expense category name">
                    </div>
                    <div class="form-group col-6">
                        <label for="branch_id">Branch</label>
                        <select class="form-control" id="branch_id" wire:model="data.branch_id">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branch->id === ($data['branch_id']??'') ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="expenseCategoryActive" wire:model="data.active">
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
