<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.discounts') }}</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus me-1"></i> New Discount
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Value</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th class="text-nowrap text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $discount)
                        <tr>
                            <td>{{ $discount->id }}</td>
                            <td>{{ $discount->name }}</td>
                            <td>{{ $discount->code }}</td>
                            <td>{{ $discount->value }} {{ $discount->type === 'rate' ? '%' : '' }}</td>
                            <td>{{ formattedDate($discount->start_date) }}</td>
                            <td>{{ formattedDate($discount->end_date) }}</td>
                            <td>
                                <span class="badge bg-{{ $discount->active ? 'success' : 'danger' }}">
                                    {{ $discount->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editDiscountModal" wire:click="setCurrent({{ $discount->id }})" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger me-1" wire:click="deleteAlert({{ $discount->id }})" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#historyModal" wire:click="setCurrent({{ $discount->id }})" title="History">
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
                    <h5 class="modal-title" id="editDiscountModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Discount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="discountName" class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="discountName" placeholder="Enter discount name">
                        </div>
                        <div class="col-md-6">
                            <label for="discountCode" class="form-label">Code</label>
                            <input type="text" class="form-control" wire:model="data.code" id="discountCode" placeholder="Enter discount code">
                        </div>
                        <div class="col-md-6">
                            <label for="discountBranch" class="form-label">Branch</label>
                            <select class="form-select" wire:model.live="data.branch_id" id="discountBranch">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discountType" class="form-label">Type</label>
                            <select class="form-select" wire:model.live="data.type" id="discountType">
                                <option value="">Select Type</option>
                                <option value="rate">Rate</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discountValue" class="form-label">Value</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa fa-{{ ($data['type']??false) == 'fixed' ? 'dollar' : 'percent' }}"></i>
                                </span>
                                <input type="number" step="any" wire:model="data.value" id="discountValue" class="form-control" placeholder="Enter value">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="discountStartDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" wire:model="data.start_date" id="discountStartDate">
                        </div>
                        <div class="col-md-6">
                            <label for="discountEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" wire:model="data.end_date" id="discountEndDate">
                        </div>

                        @isset($data['type'])
                            @if($data['type'] == 'rate')
                                <div class="col-md-6">
                                    <label for="discountMaxAmount" class="form-label">Max Discount Amount</label>
                                    <input type="number" class="form-control" wire:model="data.max_discount_amount" id="discountMaxAmount" placeholder="Enter max discount amount">
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label for="discountSalesThreshold" class="form-label">Sales Threshold</label>
                                    <input type="number" class="form-control" wire:model="data.sales_threshold" id="discountSalesThreshold" placeholder="Enter sales threshold amount">
                                    <small class="text-danger">Set a sales threshold for this discount to be applicable.</small>
                                </div>
                            @endif
                        @endisset

                        <div class="col-md-6">
                            <label for="discountUsageLimit" class="form-label">Usage Limit</label>
                            <input type="number" class="form-control" wire:model="data.usage_limit" id="discountUsageLimit" placeholder="Enter usage limit">
                        </div>

                        <div class="col-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="discountActive" wire:model="data.active">
                                <label class="form-check-label" for="discountActive">Is Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Discount History - {{ $current?->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
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
