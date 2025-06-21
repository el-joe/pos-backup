<div class="row grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="supplier_id">Supplier</label>
                    <select wire:model="data.supplier_id" class="form-control" id="supplier_id">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="branch_id">Branch</label>
                    <select wire:model="data.branch_id" class="form-control" id="branch_id">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="ref_no">Ref No</label>
                    <input type="text" wire:model="data.ref_no" class="form-control" id="ref_no" placeholder="Ref No">
                </div>
                <div class="form-group col-sm-3">
                    <label for="order_date">Date</label>
                    <input type="datetime-local" wire:model="data.order_date" class="form-control" id="order_date" placeholder="Date">
                </div>
                <div class="form-group col-sm-3">
                    <label for="status">Status</label>
                    <select wire:model="data.status" class="form-control" id="status">
                        <option value="">Select Status</option>
                        @foreach ($model::STATUS as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="form-group">
                <label for="">Sku</label>
                <input type="text" class="form-control" wire:keydown.debounce.500ms="searchSku($event.target.value)" placeholder="Search by sku" wire:model="sku">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="">Product Name</th>
                            <th class="">Quantity</th>
                            <th class="">Purchase Price / Unit</th>
                            <th class="">Discount %</th>
                            <th class="">Price (After Discount)</th>
                            <th class="">Total Price</th>
                            <th class="">Profit Margin</th>
                            <th class="">Sale Price / Unit</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($data['variables']??[]) as $variable)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    <span class="badge badge-info">{{$variable['product_variable_name']}}</span> -
                                    <span class="badge badge-info">{{$variable['last_child_unit_name']}} ({{$variable['product_variable_qty']}})</span>
                                </td>
                                <td>
                                    <select class="form-control more-width" wire:model="data.variables.{{$loop->index}}.unit_id">
                                        <option value="">Select Unit</option>
                                        @foreach ($variable['product_units'] as $unit)
                                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.qty" placeholder="Quantity">
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.purchase_price" placeholder="Purchase Price">
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.discount_percentage" placeholder="Discount">
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.price" placeholder="Price After Tax">
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.total" placeholder="Total Price">
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.x_margin" placeholder="Profit Margin">
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.sale_price" placeholder="Sale Price">
                                </td>
                                <td>
                                    @if(!$current)
                                        <button class="btn btn-danger" wire:click="removeVariable({{$loop->index}})">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    @else
                                        @if(($variable['returned']??0) == 0)
                                            <button class="btn btn-danger" wire:click="returnVariable({{$loop->index}})">
                                                Return
                                            </button>
                                        @else
                                            <span class="badge badge-warning">Returned</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Save Btn --}}
    <div class="card mt-4">
        <div class="card-body">
            <button class="btn btn-primary col-sm-4 offset-sm-4" wire:click="save">Save</button>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .more-width {
            width: 150px!important;
        }
    </style>

@endpush
