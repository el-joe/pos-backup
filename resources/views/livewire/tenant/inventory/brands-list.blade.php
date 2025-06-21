<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="mb-5 h4 col-sm-6">Brands List <code>({{ $brands->total() }})</code></div>
                <div class="col-sm-6">
                    <button class="btn btn-info float-right"  data-toggle="modal" data-target=".add-edit-modal" wire:click="setCurrent(0)">
                        <i class="icon-circle-plus"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $b)
                            <tr>
                                <td>{{$b->id}}</td>
                                <td>{{$b->name}}</td>
                                <td>
                                    <span class="badge badge-{{$b->active == 0 ? 'danger' : 'info'}}">
                                        {{$b->active ? 'YES' : 'NO'}}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-dark btn-xs" data-toggle="modal" data-target=".add-edit-modal" wire:click="setCurrent({{$b->id}})">
                                        <i class="icon-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs" wire:click="setCurrent({{$b->id}})" data-toggle="modal" data-target=".delete-modal">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="modal fade add-edit-modal" tabindex="-1" wire:ignore.self role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">{{ isset($current) ? 'Edit' : 'Add New' }} Brand</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="name">Name</label>
                                <input type="text" wire:model="data.name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <p class="mb-2">Active</p>
                                <label class="toggle-switch toggle-switch-info">
                                    <input type="checkbox" wire:model="data.active" {{($data['active']??0) == 1 ? 'checked' : ''}} >
                                    <span class="toggle-slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                    </div>
                  </div>
                </div>
            </div>

            <div class="modal fade delete-modal" tabindex="-1" wire:ignore.self role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header justify-content-center">
                      <h5 class="modal-title">Are you Sure?!</h5>
                    </div>
                    <div class="modal-footer justify-content-around">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                  </div>
                </div>
            </div>

        </div>
    </div>
</div>
