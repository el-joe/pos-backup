<div class="row grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="customer_id">Customer</label>
                    <select wire:model="data.customer_id" class="form-control" id="customer_id">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
                            <th class="">Sale Price / Unit</th>
                            <th class="">Discount</th>
                            <th class="">Total Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($data['variables']??[]) as $variable)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    <span class="badge badge-dark">{{$variable['product_variable_name']}}</span> -
                                    <span class="badge badge-dark">{{$variable['last_child_unit_name']}} ({{$variable['product_variable_qty']}})</span>
                                </td>
                                <td>
                                    <select class="form-control more-width" wire:model="data.variables.{{$loop->index}}.unit_id" {{ $current ? 'disabled' : '' }}>
                                        <option value="">Select Unit</option>
                                        @foreach ($variable['product_units'] as $unit)
                                            @php
                                                $currentStokeQtyIntoSmallPiece = $variable['product_variable_qty']??0;
                                                $unitQtyIntoProductVariable = $unit->unitQtyIntoProductVariable();
                                            @endphp
                                            <option value="{{$unit->id}}">{{$unit->name}} - ({{number_format($currentStokeQtyIntoSmallPiece/$unitQtyIntoProductVariable,3,'.','')}})</option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="form-control more-width" wire:model="data.variables.{{$loop->index}}.qty" placeholder="Quantity" {{ $current ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <input type="number" class="form-control more-width" wire:model.live="data.variables.{{$loop->index}}.sale_price" placeholder="Sale Price" {{ $current ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <select class="form-control more-width" wire:model.live="data.variables.{{$loop->index}}.discount_type" {{ $current ? 'disabled' : '' }}>
                                        <option value="amount">Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    <input type="number" class="form-control more-width" wire:model.live="data.variables.{{$loop->index}}.discount" placeholder="Discount" {{ $current ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    {{-- Sale Price After Discount --}}
                                    @if($data['variables'][$loop->index]['discount_type'] == 'amount')
                                        {{ ($data['variables'][$loop->index]['sale_price'] - $data['variables'][$loop->index]['discount']) * $data['variables'][$loop->index]['qty'] }}
                                    @else
                                    {{ ($data['variables'][$loop->index]['sale_price'] - ($data['variables'][$loop->index]['sale_price']*$data['variables'][$loop->index]['discount'])/100) * $data['variables'][$loop->index]['qty'] }}
                                    @endif
                                </td>
                                <td>
                                    @if(!$current)
                                        <button class="btn btn-danger" wire:click="removeVariable({{$loop->index}})">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    @else
                                        @if(($variable['refunded']??0) == 0)
                                            <button class="btn btn-danger" wire:click="refundVariable({{$loop->index}})">
                                                Refund
                                            </button>
                                        @else
                                            <span class="badge badge-warning">Refunded</span>
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
            height: 35px!important;
            padding: 5px!important;
            margin : 10px 0px!important;
            font-size: 12px!important;
        }
    </style>

@endpush
