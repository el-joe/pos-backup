<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Taxes</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit tax --}}
                <a  class="btn btn-primary" data-toggle="modal" data-target="#editTaxModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Tax
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taxes as $tax)
                        <tr>
                            <td>{{ $tax->id }}</td>
                            <td>{{ $tax->name }}</td>
                            <td>{{ $tax->rate ?? 0 }}%</td>
                            <td>
                                <span class="badge badge-{{ $tax->active ? 'success' : 'danger' }}">
                                    {{ $tax->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#editTaxModal" wire:click="setCurrent({{ $tax->id }})" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary m-r-10"></i>
                                </a>
                                <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $tax->id }})">
                                    <i class="fa fa-close text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $taxes->links() }}
            </div>
        </div>
    </div>

    {{-- add edit modal for taxes page --}}
    <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="editTaxModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Tax</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="taxName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="taxName" placeholder="Enter tax name">
                    </div>
                    <div class="form-group">
                        <label for="taxRate">Rate</label>
                        <input type="number" class="form-control" wire:model="data.rate" id="taxRate" placeholder="Enter tax rate">
                    </div>
                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="taxActive" wire:model="data.active">
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
