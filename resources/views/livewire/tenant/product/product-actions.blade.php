<div class="row grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" wire:model="data.name">
                </div>
                <div class="form-group col-sm-4">
                    <label for="sku">Sku</label>
                    <input type="text" class="form-control" id="sku" wire:model="data.sku">
                </div>
                <div class="form-group col-sm-4">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" wire:model="data.image">
                </div>
                <div class="form-group col-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="4" wire:model="data.description"></textarea>
                </div>
                <div class="form-group col-sm-3">
                    <label for="brand">Brand</label>
                    <select class="form-control" id="brand" wire:model="data.brand_id">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="unit">Unit</label>
                    <select class="form-control" id="unit" wire:model="data.unit_id">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label for="parent_id">Category</label>
                    <select id="parent_id" wire:model="data.category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            {{recursiveChildrenForOptions($category,'children','id','name',0,true)}}
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="weight">Weight/gram</label>
                    <input type="number" step="any" class="form-control" id="weight" wire:model="data.weight">
                </div>
                <div class="form-group col-sm-4">
                    <label for="alert_qty">Alert Quantity</label>
                    <input type="number" class="form-control" id="alert_qty" wire:model="data.alert_qty">
                </div>
                <div class="form-group col-4 text-center mt-3">
                    <p class="mb-2">Active</p>
                    <label class="toggle-switch toggle-switch-info">
                        <input type="checkbox" wire:model="data.active" {{($data['active']??0) == 1 ? 'checked' : ''}}>
                        <span class="toggle-slider round"></span>
                    </label>
                </div>

                <div class="form-group col-sm-4">
                    <label for="type">Type</label>
                    <select class="form-control" id="type" wire:model.live="data.type">
                        <option value="">Select Type</option>
                        <option value="single">Single</option>
                        <option value="multiple">Multiple</option>
                    </select>
                </div>

                <div class="form-group col-sm-3">
                    <label for="tax_id">Tax</label>
                    <select class="form-control" id="tax_id" wire:model="data.tax_id">
                        <option value="">Select Tax</option>
                        @foreach($taxes as $tax)
                            <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-4">
                    <label for="price_type">sell Price Tax Type</label>
                    <select class="form-control" id="price_type" wire:model="data.price_type">
                        <option value="">Select Type</option>
                        <option value="inc_tax">Inclusive</option>
                        <option value="ex_tax">Exclusive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            {{-- <div class="form-group">
                <label for="">Sku</label>
                <input type="text" class="form-control" wire:keydown.debounce.500ms="searchSku($event.target.value)" placeholder="Search by sku" wire:model="sku">
            </div> --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Name (Value)</th>
                            <th>Default Purchase Price</th>
                            <th>x Margin (%)</th>
                            <th>Default sell Price</th>
                            <th>Image</th>
                            <th>
                                @if(($data['type']??'') == 'multiple')
                                    <button class="btn btn-sm btn-primary" wire:click="addNewVariable">+</button>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($data['variables']??[]) as $variable)
                            <tr>
                                <td>
                                    <input type="text" class="form-control" {{($data['type']??'') == 'single' ? "readonly" : ''}} wire:model="data.variables.{{$loop->index}}.sku">
                                    @if($variable['id'])
                                        Quantity : <span class="badge badge-info">{{$variable['stoke_total_qty']}}</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="text" class="form-control" {{($data['type']??'') == 'single' ? "readonly" : ''}} wire:model="data.variables.{{$loop->index}}.name">
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label for="">Ex Tax</label>
                                        <input type="number" step="any" class="form-control" wire:model="data.variables.{{$loop->index}}.purchase_price_ex_tax" placeholder="Ex Tax">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Inc Tax</label>
                                        <input type="number" step="any" class="form-control" wire:model="data.variables.{{$loop->index}}.purchase_price_inc_tax" placeholder="Inc Tax">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control" wire:model="data.variables.{{$loop->index}}.x_margin">
                                </td>
                                <td>
                                    @if(($data['price_type']??'inc_tax') == 'inc_tax')
                                        <div class="form-group">
                                            <label for="">Inc Tax</label>
                                            <input type="number" class="form-control" wire:model="data.variables.{{$loop->index}}.sell_price_inc_tax">
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="">Ex Tax</label>
                                            <input type="number" class="form-control" wire:model="data.variables.{{$loop->index}}.sell_price_ex_tax">
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <input type="file" class="form-control" wire:model="data.variables.{{$loop->index}}.image">
                                </td>
                                <td>
                                    @if($current && !isset($data['variables'][$loop->index]['id']) || $data['type'] != 'multiple')
                                        <button class="btn btn-sm btn-primary" wire:click="saveVariable({{$loop->index}})">Save</button>
                                    @else
                                        <button class="btn btn-sm btn-danger" wire:click="$set('currentIndex',{{$loop->index}})" data-toggle="modal" data-target=".delete-modal">-</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <button type="button" class="btn btn-danger" wire:click="removeVariable()">Delete</button>
            </div>
          </div>
        </div>
    </div>

    {{-- Save Btn --}}
    <div class="col-12 mt-4">
        <button class="btn btn-primary col-4 offset-4" wire:click="save">Save</button>

        {{-- @if($product_id)
            <button class="btn btn-danger" wire:click="deleteProduct">Delete</button>
        @endif --}}
    </div>
</div>
