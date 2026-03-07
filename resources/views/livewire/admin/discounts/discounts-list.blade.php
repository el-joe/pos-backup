<div class="col-sm-12">
    <x-admin.filter-card
        :title="__('general.pages.discounts.filters')"
        icon="fa-filter"
        collapse-id="adminDiscountFilterCollapse"
        :collapsed="$collapseFilters"
    >
        <x-slot:actions>
            <button type="button"
                    class="btn btn-default btn-sm"
                    wire:click="$toggle('collapseFilters')"
                    data-toggle="collapse"
                    data-target="#adminDiscountFilterCollapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}">
                <i class="fa fa-filter"></i> {{ __('general.pages.discounts.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.discounts.search_label') }}</label>
                <input type="text"
                       class="form-control"
                       placeholder="{{ __('general.pages.discounts.search_placeholder') }}"
                       wire:model.live="filters.search">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.discounts.start_date') }}</label>
                <input type="date" class="form-control" wire:model.live="filters.start_date">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.discounts.end_date') }}</label>
                <input type="date" class="form-control" wire:model.live="filters.end_date">
            </div>

            <div class="col-md-4 form-group">
                <label>{{ __('general.pages.discounts.status') }}</label>
                <select class="form-control" wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.discounts.all') }}</option>
                    <option value="1">{{ __('general.pages.discounts.active') }}</option>
                    <option value="0">{{ __('general.pages.discounts.inactive') }}</option>
                </select>
            </div>

            <div class="col-xs-12 text-right">
                <button type="button" class="btn btn-default btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.discounts.reset') }}
                </button>
            </div>
        </div>
    </x-admin.filter-card>

    <x-admin.table-card :title="__('general.titles.discounts')" icon="fa-percent">
        <x-slot:actions>
            @adminCan('discounts.export')
                <button type="button" class="btn btn-success btn-sm" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel-o"></i> {{ __('general.pages.discounts.export') }}
                </button>
            @endadminCan
            @adminCan('discounts.create')
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editDiscountModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.discounts.new_discount') }}
                </a>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.discounts.id') }}</th>
                <th>{{ __('general.pages.discounts.name') }}</th>
                <th>{{ __('general.pages.discounts.code') }}</th>
                <th>{{ __('general.pages.discounts.value') }}</th>
                <th>{{ __('general.pages.discounts.start_date') }}</th>
                <th>{{ __('general.pages.discounts.end_date') }}</th>
                <th>{{ __('general.pages.discounts.status') }}</th>
                <th class="text-nowrap">{{ __('general.pages.discounts.actions') }}</th>
            </tr>
        </x-slot:head>

        @forelse ($discounts as $discount)
            <tr>
                <td>{{ $discount->id }}</td>
                <td>{{ $discount->name }}</td>
                <td>{{ $discount->code }}</td>
                <td>{{ $discount->value }} {{ $discount->type === 'rate' ? '%' : currency()->symbol }}</td>
                <td>{{ dateTimeFormat($discount->start_date, true, false) }}</td>
                <td>{{ dateTimeFormat($discount->end_date, true, false) }}</td>
                <td>
                    <span class="badge badge-{{ $discount->active ? 'success' : 'danger' }}">
                        {{ $discount->active ? __('general.pages.discounts.active') : __('general.pages.discounts.inactive') }}
                    </span>
                </td>
                <td class="text-nowrap">
                    @adminCan('discounts.update')
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#editDiscountModal" wire:click="setCurrent({{ $discount->id }})" data-original-title="{{ __('general.pages.discounts.edit') }}">
                            <i class="fa fa-pencil text-primary m-r-10"></i>
                        </a>
                    @endadminCan
                    @adminCan('discounts.delete')
                        <a href="javascript:void(0)" data-original-title="{{ __('general.pages.discounts.delete') }}" wire:click="deleteAlert({{ $discount->id }})">
                            <i class="fa fa-close text-danger m-r-10"></i>
                        </a>
                    @endadminCan
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#historyModal" data-original-title="{{ __('general.pages.discounts.history') }}" wire:click="setCurrent({{ $discount->id }})">
                        <i class="fa fa-history text-info"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse

        @if($discounts->hasPages())
            <x-slot:footer>
                {{ $discounts->links() }}
            </x-slot:footer>
        @endif
    </x-admin.table-card>

    {{-- add edit modal for discounts page --}}
    <div class="modal fade" id="editDiscountModal" tabindex="-1" role="dialog" aria-labelledby="editDiscountModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDiscountModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-6">
                        <label for="discountName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="discountName" placeholder="Enter discount name">
                    </div>
                    <div class="form-group col-6">
                        <label for="discountCode">Code</label>
                        <input type="text" class="form-control" wire:model="data.code" id="discountCode" placeholder="Enter discount code">
                    </div>
                    <div class="form-group col-6">
                        <label for="discountBranch">Branch</label>
                        <select class="form-control" wire:model.live="data.branch_id" id="discountBranch">
                            <option value="">All Branches</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="discountType">Type</label>
                        <select class="form-control" wire:model.live="data.type" id="discountType">
                            <option value="">Select Type</option>
                            <option value="rate">Rate</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="discountValue">Value</label>
                        <div class="input-group m-t-10">
                            <span class="input-group-addon">
                                <i class="fa fa-{{ ($data['type']??false) == 'fixed' ? 'dollar' : 'percent' }}"></i>
                            </span>
                            <input type="number" step="any" wire:model="data.value" id="example-input3-group1" name="example-input3-group1" class="form-control" placeholder="..">
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                    {{-- Start & end Dates --}}
                    <div class="form-group col-6">
                        <label for="discountStartDate">Start Date</label>
                        <input type="date" class="form-control" wire:model="data.start_date" id="discountStartDate">
                    </div>
                    <div class="form-group col-6">
                        <label for="discountEndDate">End Date</label>
                        <input type="date" class="form-control" wire:model="data.end_date" id="discountEndDate">
                    </div>
                    @isset($data['type'])
                        @if($data['type'] == 'rate')
                            <div class="form-group col-6">
                                <label for="discountMaxAmount">Max Discount Amount</label>
                                <input type="number" class="form-control" wire:model="data.max_discount_amount" id="discountMaxAmount" placeholder="Enter max discount amount">
                            </div>
                        @else
                            {{-- sales threshold --}}
                            {{-- add description for sales threshold --}}
                            <div class="form-group col-6">
                                <label for="discountSalesThreshold">Sales Threshold </label>
                                <input type="number" class="form-control" wire:model="data.sales_threshold" id="discountSalesThreshold" placeholder="Enter sales threshold amount">
                                <small class="form-text text-danger">Set a sales threshold for this discount to be applicable.</small>
                            </div>
                        @endif
                    @endisset
                    {{-- usage limit --}}
                    <div class="form-group col-6">
                        <label for="discountUsageLimit">Usage Limit</label>
                        <input type="number" class="form-control" wire:model="data.usage_limit" id="discountUsageLimit" placeholder="Enter usage limit">
                    </div>
                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="discountActive" wire:model="data.active">
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

    {{-- history modal --}}
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Discount History - {{ $current?->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Target Type</th>
                                <th>Target ID</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($current?->history??[] as $history)
                            <tr>
                                <td>{{ $history->id }}</td>
                                <td>{{ App\Models\Tenant\DiscountHistory::$relatedWith[$history->target_type] ?? $history->target_type }}</td>
                                <td>{{ $history->target_id }}</td>
                                <td>{{ dateTimeFormat($history->created_at, true, false) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
@endpush
