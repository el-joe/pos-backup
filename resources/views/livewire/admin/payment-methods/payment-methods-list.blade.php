<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Payment Methods</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit payment method --}}
                <a class="btn btn-primary" data-toggle="modal" data-target="#editPaymentMethodModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Payment Method
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentMethods as $paymentMethod)
                    <tr>
                        <td>{{ $paymentMethod->id }}</td>
                        <td>{{ $paymentMethod->name }}</td>
                        <td>{{ $paymentMethod->branch?->name ?? 'All Branches'}}</td>
                        <td>
                            <span class="badge badge-{{ $paymentMethod->active ? 'success' : 'danger' }}">{{ $paymentMethod->active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="text-nowrap">
                            @if(!in_array($paymentMethod->slug, ['cash', 'bank-transfer']))
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editPaymentMethodModal" wire:click="setCurrent({{ $paymentMethod->id }})" data-toggle="tooltip" data-original-title="Edit">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $paymentMethod->id }})">
                                <i class="fa fa-close text-danger m-r-10"></i>
                            </a>
                            @else
                            <i class="fa fa-lock text-muted m-r-10" title="This payment method cannot be edited or deleted"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $paymentMethods->links() }}
            </div>
        </div>
    </div>

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
