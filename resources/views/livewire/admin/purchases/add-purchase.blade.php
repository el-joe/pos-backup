<div>
    <div class="white-box">
        <h3 class="box-title m-b-0">Purchase Details</h3>
        <p class="text-muted m-b-30 font-13"></p>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    <label for="branch_id">Branch</label>
                    <select id="branch_id" wire:model.change="data.branch_id" class="form-control">
                        <option value="" selected disabled>Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- supplier then category --}}
                <div class="form-group col-sm-4">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" wire:model.change="data.supplier_id" class="form-control">
                        <option value="" selected disabled>Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="status_id">Status</label>
                    <select id="status_id" wire:model.change="data.status_id" class="form-control">
                        <option value="">Select Status</option>
                        @foreach ($purchaseStatuses as $status)
                            <option value="{{ $status->value }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-4">
                    <label for="ref_no">Ref NO.</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-product-hunt"></i></div>
                        <input type="text" class="form-control" id="ref_no" placeholder="Ref NO." wire:model="data.ref_no">
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label for="order_date">Order Date</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-barcode"></i></div>
                        <input type="date" class="form-control" id="order_date" placeholder="Order Date" wire:model="data.order_date">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- new white-box for order products --}}

    <div class="white-box">
        <h3 class="box-title m-b-0">Order Products</h3>
        <p class="text-muted m-b-30 font-13"></p>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    {{-- <label for="product_search">Search Product</label> --}}
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                        <input
                            type="text" class="form-control" id="product_search"
                            placeholder="Search Product by name/code"
                            wire:model.live.debounce.1000ms="product_search"
                            x-data
                            @reset-search-input.window="$el.value=''"
                        >
                    </div>
                </div>
            </div>
        </div>
        {{-- products table --}}
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Unit Cost (before discount)</th>
                                <th>Discount %</th>
                                <th>Unit Cost (before tax)</th>
                                <th>Tax %</th>
                                <th>Sub Total</th>
                                <th>X Margin %</th>
                                <th>Sell Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderProducts??[] as $product)
                                <tr>
                                    <td>{{ $product['name'] }}</td>
                                    <td>
                                        <select name="unit_id" id="unit_id" wire:model.change="orderProducts.{{ $product['id'] }}.unit_id" class="form-control">
                                            <option value="">Select Unit</option>
                                            @foreach ($product['units'] as $unit)
                                                {{recursiveChildrenForOptions($unit,'children','id','name',0,true,$this->data['unit_id'] ?? null)}}
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.qty" min="1" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.purchase_price" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.discount_percentage" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        {{ number_format($product['unit_cost_after_discount'], 2) }}
                                    </td>
                                    <td>
                                        <select name="tax_percentage" id="tax_percentage" wire:model.change="orderProducts.{{ $product['id'] }}.tax_percentage" class="form-control">
                                            <option value="">Select Tax</option>
                                            @foreach ($taxes as $tax)
                                                <option value="{{ $tax->rate }}" {{ $product['tax_percentage'] == $tax->rate ? 'selected' : '' }}>{{ $tax->name }} - {{ $tax->rate }}%</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ number_format($product['sub_total'], 2) }}
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.blur="orderProducts.{{ $product['id'] }}.x_margin" step="0.01" min="0" placeholder="0.00">
                                    </td>
                                    <td>
                                        {{ number_format($product['sell_price'], 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($product['total'], 2) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" wire:click="return({{ $product['id'] }})">Return</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Net Total</th>
                                <th>{{ number_format($netTotalAmount??0, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
