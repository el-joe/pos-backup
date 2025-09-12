<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Accounts</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit account --}}
                <a  class="btn btn-primary" data-toggle="modal" data-target="#editAccountModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Account
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
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->id }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->code }}</td>
                            <td><p class="badge badge-{{ $account->type->color() }}">{{ $account->type->label() }}</p></td>
                            <td>{{ $account->branch?->name ?? '----' }}</td>
                            <td>{{ $account->paymentMethod?->name ?? '----' }}</td>
                            <td>
                                <span class="badge badge-{{ $account->active ? 'success' : 'danger' }}">
                                    {{ $account->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#editAccountModal" wire:click="setCurrent({{ $account->id }})" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $account->id }})">
                                    <i class="fa fa-close text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $accounts->links() }}
            </div>
        </div>
    </div>

    {{-- add edit modal for accounts page --}}
    <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="branchName">Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="branchName" placeholder="Enter branch name">
                        </div>
                        <div class="form-group">
                            <label for="branchCode">Code</label>
                            <input type="text" class="form-control" wire:model="data.code" id="branchCode" placeholder="Enter branch code">
                        </div>
                        @if(!(($filters['model_type'] ?? false) == \App\Models\Tenant\User::class && ($filters['model_id'] ?? false)))
                            {{-- select branch --}}
                            <div class="form-group">
                                <label for="branchSelect">Branch</label>
                                <select class="form-control" wire:model.live="data.branch_id" id="branchSelect">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id === ($data['branch_id']??false) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        {{-- select payment method --}}
                        <div class="form-group">
                            <label for="paymentMethodSelect">Payment Method</label>
                            <select class="form-control" wire:model="data.payment_method_id" id="paymentMethodSelect">
                                <option value="">Select Payment Method</option>
                                @foreach($paymenthMethods as $method)
                                    <option value="{{ $method->id }}" {{ $method->id === ($data['payment_method_id']??false) ? 'selected' : '' }}>{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(!(($filters['model_type'] ?? false) == \App\Models\Tenant\User::class && ($filters['model_id'] ?? false)))
                            <div class="form-group">
                                <label for="branchType">Type</label>
                                <select class="form-control" wire:model="data.type" id="branchType">
                                    <option value="">Select Type</option>
                                    @foreach($accountTypes as $type)
                                        <option value="{{ $type->value }}" {{ $type->value === ($data['type']??false) ? 'selected' : '' }} {{ $type->isInvalided() ? 'disabled' : '' }}>{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="branchActive" wire:model="data.active">
                                <span class="checkmark"></span> Is Active
                            </label>
                        </div>
                    </form>
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
