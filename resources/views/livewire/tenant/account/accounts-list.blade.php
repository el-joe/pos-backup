<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="mb-5 h4 col-sm-6">Account Types List <code>({{ $accounts->total() }})</code></div>
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
                            <th>Code</th>
                            <th>Account Type</th>
                            <th>Related To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $acc)
                            <tr>
                                <td>{{$acc->code}}</td>
                                <td>{{$acc->type?->group?->name ?? ""}} -> {{$acc->type?->name ?? ""}}</td>
                                <td>{{ucfirst($acc->related_to??'')}} -> {{$acc->model?->name??'None'}}</td>
                                <td>
                                    <button type="button" class="btn btn-dark btn-xs" data-toggle="modal" data-target=".add-edit-modal" wire:click="setCurrent({{$acc->id}})">
                                        <i class="icon-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs" wire:click="setCurrent({{$acc->id}})" data-toggle="modal" data-target=".delete-modal">
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
                      <h5 class="modal-title">{{ isset($current) ? 'Edit' : 'Add New' }} Accounts</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="account_type_id">Account Type</label>
                            <select  id="account_type_id" class="form-control" wire:model="data.account_type_id">
                                <option value="">Select ...</option>
                                @foreach ($groups as $group)
                                    <optgroup label="{{$group->name}}">
                                    @foreach ($group->accountTypes as $type)
                                    {{recursiveChildrenForOptions($type,'children','id','name',0,false)}}
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="model_type">Related To</label>
                                <select id="model_type" class="form-control" wire:model.change="data.model_type">
                                    <option value="">Select ...</option>
                                    @foreach (\App\Models\Tenant\Account::RELATED_TO as $key => $model)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="model_id">Related Name</label>
                                <select id="model_id" class="form-control" wire:model="data.model_id">
                                    <option value="">Select ...</option>
                                    @if(isset($data['model_type']))
                                        @foreach (\App\Models\Tenant\Account::modelList($data['model_type']) as $model)
                                            <option value="{{$model->id}}" {{isset($data['model_id']) && $data['model_id'] == $model->id ? 'selected' : ''}}>{{$model->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" wire:model="data.code" id="code" class="form-control">
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
