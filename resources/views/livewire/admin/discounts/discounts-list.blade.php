<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Discounts</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit discount --}}
                <a class="btn btn-primary" data-toggle="modal" data-target="#editDiscountModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Discount
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Value</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
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
                            <span class="badge badge-{{ $discount->active ? 'success' : 'danger' }}">
                                {{ $discount->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editDiscountModal" wire:click="setCurrent({{ $discount->id }})" data-toggle="tooltip" data-original-title="Edit">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $discount->id }})">
                                <i class="fa fa-close text-danger m-r-10"></i>
                            </a>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#historyModal" data-toggle="tooltip" data-original-title="History" wire:click="setCurrent({{ $discount->id }})">
                                <i class="fa fa-history text-info"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $discounts->links() }}
            </div>
        </div>
    </div>

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
@push('styles')
@endpush
