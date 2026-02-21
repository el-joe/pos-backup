<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Units</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit unit --}}
                <a class="btn btn-primary" data-toggle="modal" data-target="#editUnitModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> New Unit
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Parent</th>
                        <th>Count</th>
                        <th>Status</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                    <tr>
                        <td>{{ $unit->id }}</td>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->parent ? $unit->parent->name : 'N/A' }}</td>
                        <td>{{ $unit->count }}</td>
                        <td>
                            <span class="badge badge-{{ $unit->active ? 'success' : 'danger' }}">
                                {{ $unit->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#editUnitModal" wire:click="setCurrent({{ $unit->id }})" data-toggle="tooltip" data-original-title="Edit">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $unit->id }})">
                                <i class="fa fa-close text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $units->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-labelledby="editUnitModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUnitModalLabel">{{ $current?->id ? 'Edit' : 'New' }} Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoryName">Name</label>
                        <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="Enter category name">
                    </div>
                    <div class="form-group">
                        <label for="categoryParent">Parent Unit</label>
                        <select id="parent_id" wire:model.change="data.parent_id" class="form-control">
                            <option value="0">Is Parent</option>
                            @foreach ($parents as $parent)
                            {{recursiveChildrenForOptions($parent,'children','id','name',0)}}
                            @endforeach
                        </select>
                    </div>
                    @if(($data['parent_id']??0) != 0)
                        <div class="form-group">
                            <label for="count">Count</label>
                            <input type="number" step="any" wire:model="data.count" id="count" class="form-control">
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" id="categoryActive" wire:model="data.active">
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
