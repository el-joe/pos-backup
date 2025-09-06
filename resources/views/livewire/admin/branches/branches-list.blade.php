<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Branches</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit branch --}}
                <a  class="btn btn-primary" data-toggle="modal" data-target="#editBranchModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Branch
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Active</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branches as $branch)
                        <tr>
                            <td>{{ $branch->id }}</td>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->phone }}</td>
                            <td>{{ $branch->email }}</td>
                            <td>{{ $branch->address }}</td>
                            <td>
                                <span class="badge badge-{{ $branch->active ? 'success' : 'danger' }}">
                                    {{ $branch->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#editBranchModal" wire:click="setCurrent({{ $branch->id }})" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $branch->id }})">
                                    <i class="fa fa-close text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $branches->links() }}
            </div>
        </div>
    </div>

    {{-- add edit modal for branches page --}}
    <div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBranchModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Branch</h5>
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
                            <label for="branchPhone">Phone</label>
                            <input type="text" class="form-control" wire:model="data.phone" id="branchPhone" placeholder="Enter branch phone">
                        </div>
                        <div class="form-group">
                            <label for="branchEmail">Email</label>
                            <input type="email" class="form-control" wire:model="data.email" id="branchEmail" placeholder="Enter branch email">
                        </div>
                        <div class="form-group">
                            <label for="branchAddress">Address</label>
                            <input type="text" class="form-control" wire:model="data.address" id="branchAddress" placeholder="Enter branch address">
                        </div>
                        <div class="form-group">
                            <label for="branchWebsite">Website</label>
                            <input type="text" class="form-control" wire:model="data.website" id="branchWebsite" placeholder="Enter branch website">
                        </div>
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
