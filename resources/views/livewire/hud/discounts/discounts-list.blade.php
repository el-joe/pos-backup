<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.pages.discounts.filters') }}</h5>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}"
                    wire:click="$toggle('collapseFilters')"
                    data-bs-target="#branchFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.discounts.show_hide') }}
            </button>
        </div>

        <div class="collapse {{ $collapseFilters ? 'show' : '' }}" id="branchFilterCollapse">
            <div class="card-body">
                <div class="row g-3">

                    <!-- Filter by Name -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.discounts.search_label') }}</label>
                        <input type="text" class="form-control"
                            placeholder="{{ __('general.pages.discounts.search_placeholder') }}"
                            wire:model.blur="filters.search">
                    </div>

                    {{-- Filter By Start Date --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.discounts.start_date') }}</label>
                        <input type="date" class="form-control"
                            wire:model.live="filters.start_date">
                    </div>

                    {{-- Filter By End Date --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.discounts.end_date') }}</label>
                        <input type="date" class="form-control"
                            wire:model.live="filters.end_date">
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.discounts.status') }}</label>
                        <select class="form-select select2" name="filters.active">
                            <option value="all" {{ ($filters['active']??'') == 'all' ? 'selected' : '' }}>{{ __('general.pages.discounts.all') }}</option>
                            <option value="1" {{ ($filters['active']??'') == '1' ? 'selected' : '' }}>{{ __('general.pages.discounts.active') }}</option>
                            <option value="0" {{ ($filters['active']??'') == '0' ? 'selected' : '' }}>{{ __('general.pages.discounts.inactive') }}</option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm"
                                wire:click="resetFilters">
                            <i class="fa fa-undo me-1"></i> {{ __('general.pages.discounts.reset') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.discounts') }}</h5>
            <div class="d-flex align-items-center gap-2">
                @adminCan('discounts.export')
                <!-- Export Button -->
                <button class="btn btn-outline-success"
                        wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel me-1"></i> {{ __('general.pages.discounts.export') }}
                </button>
                @endadminCan

                @adminCan('discounts.create')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.discounts.new_discount') }}
                </button>
                @endadminCan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.pages.discounts.id') }}</th>
                            <th>{{ __('general.pages.discounts.name') }}</th>
                            <th>{{ __('general.pages.discounts.code') }}</th>
                            <th>{{ __('general.pages.discounts.value') }}</th>
                            <th>{{ __('general.pages.discounts.start_date') }}</th>
                            <th>{{ __('general.pages.discounts.end_date') }}</th>
                            <th>{{ __('general.pages.discounts.status') }}</th>
                            <th class="text-nowrap text-center">{{ __('general.pages.discounts.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $discount)
                        <tr>
                            <td>{{ $discount->id }}</td>
                            <td>{{ $discount->name }}</td>
                            <td>{{ $discount->code }}</td>
                            <td>{{ $discount->value }} {{ $discount->type === 'rate' ? '%' : currency()->symbol }}</td>
                            <td>{{ dateTimeFormat($discount->start_date,true,false) }}</td>
                            <td>{{ dateTimeFormat($discount->end_date,true,false) }}</td>
                            <td>
                                <span class="badge bg-{{ $discount->active ? 'success' : 'danger' }}">
                                    {{ $discount->active ? __('general.pages.discounts.active') : __('general.pages.discounts.inactive') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @adminCan('discounts.update')
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent({{ $discount->id }})" title="{{ __('general.pages.discounts.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                @endadminCan
                                @adminCan('discounts.delete')
                                <button class="btn btn-sm btn-danger me-1" wire:click="deleteAlert({{ $discount->id }})" title="{{ __('general.pages.discounts.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endadminCan
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#historyModal" wire:click="setCurrent({{ $discount->id }})" title="{{ __('general.pages.discounts.history') }}">
                                    <i class="fa fa-history"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $discounts->links() }}
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>


    <!-- Edit Discount Modal -->
    <div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDiscountModalLabel">{{ $current?->id ? __('general.pages.discounts.edit_discount') : __('general.pages.discounts.new_discount') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="discountName" class="form-label">{{ __('general.pages.discounts.name') }}</label>
                            <input type="text" class="form-control" wire:model="data.name" id="discountName" placeholder="{{ __('general.pages.discounts.enter_discount_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="discountCode" class="form-label">{{ __('general.pages.discounts.code') }}</label>
                            <input type="text" class="form-control" wire:model="data.code" id="discountCode" placeholder="{{ __('general.pages.discounts.enter_discount_code') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="discountBranch" class="form-label">{{ __('general.pages.discounts.branch') }}</label>
                            <select class="form-select select2" name="data.branch_id" id="discountBranch">
                                <option value="">{{ __('general.pages.discounts.all_branches_option') }}</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discountType" class="form-label">{{ __('general.pages.discounts.type') }}</label>
                            <select class="form-select select2" name="data.type" id="discountType">
                                <option value="">{{ __('general.pages.discounts.select_type') }}</option>
                                <option value="rate" {{ ($data['type']??'') == 'rate' ? 'selected' : '' }}>{{ __('general.pages.discounts.rate') }}</option>
                                <option value="fixed" {{ ($data['type']??'') == 'fixed' ? 'selected' : '' }}>{{ __('general.pages.discounts.fixed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discountValue" class="form-label">{{ __('general.pages.discounts.value') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-{{ ($data['type']??false) == 'fixed' ? 'dollar' : 'percent' }}"></i>
                                </span>
                                <input type="number" step="any" wire:model="data.value" id="discountValue" class="form-control" placeholder="{{ __('general.pages.discounts.enter_value') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="discountStartDate" class="form-label">{{ __('general.pages.discounts.start_date') }}</label>
                            <input type="date" class="form-control" wire:model="data.start_date" id="discountStartDate">
                        </div>
                        <div class="col-md-6">
                            <label for="discountEndDate" class="form-label">{{ __('general.pages.discounts.end_date') }}</label>
                            <input type="date" class="form-control" wire:model="data.end_date" id="discountEndDate">
                        </div>

                        @isset($data['type'])
                            @if($data['type'] == 'rate')
                                <div class="col-md-6">
                                    <label for="discountMaxAmount" class="form-label">{{ __('general.pages.discounts.max_discount_amount') }}</label>
                                    <input type="number" class="form-control" wire:model="data.max_discount_amount" id="discountMaxAmount" placeholder="{{ __('general.pages.discounts.enter_max_discount_amount') }}">
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label for="discountSalesThreshold" class="form-label">{{ __('general.pages.discounts.sales_threshold') }}</label>
                                    <input type="number" class="form-control" wire:model="data.sales_threshold" id="discountSalesThreshold" placeholder="{{ __('general.pages.discounts.enter_sales_threshold_amount') }}">
                                    <small class="text-danger">{{ __('general.pages.discounts.sales_threshold_note') }}</small>
                                </div>
                            @endif
                        @endisset

                        <div class="col-md-6">
                            <label for="discountUsageLimit" class="form-label">{{ __('general.pages.discounts.usage_limit') }}</label>
                            <input type="number" class="form-control" wire:model="data.usage_limit" id="discountUsageLimit" placeholder="{{ __('general.pages.discounts.enter_usage_limit') }}">
                        </div>

                        <div class="col-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="discountActive" wire:model="data.active">
                                <label class="form-check-label" for="discountActive">{{ __('general.pages.discounts.is_active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.discounts.close') }}</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.discounts.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">{{ __('general.pages.discounts.discount_history') }} - {{ $current?->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.pages.discounts.id') }}</th>
                                <th>{{ __('general.pages.discounts.target_type') }}</th>
                                <th>{{ __('general.pages.discounts.target_id') }}</th>
                                <th>{{ __('general.pages.discounts.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($current?->history??[] as $history)
                            <tr>
                                <td>{{ $history->id }}</td>
                                <td>{{ App\Models\Tenant\DiscountHistory::$relatedWith[$history->target_type] ?? $history->target_type }}</td>
                                <td>{{ $history->target_id }}</td>
                                <td>{{ formattedDate($history->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    @include('layouts.hud.partials.select2-script')
@endpush
