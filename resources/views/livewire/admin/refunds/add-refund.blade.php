<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Create Refund</h3>
            </div>
            <div class="col-xs-6 text-right">
                <a class="btn btn-default" href="{{ route('admin.refunds.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-4">
                <label class="control-label">Order Type</label>
                <select class="form-control" wire:model.defer="data.order_type">
                    <option value="">-- Select --</option>
                    @foreach($orderTypes as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
                @error('order_type') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-sm-4">
                <label class="control-label">Order ID</label>
                <input type="number" class="form-control" wire:model.defer="data.order_id" placeholder="Order ID">
                @error('order_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="form-group col-sm-4">
                <label class="control-label">Total</label>
                <input type="number" step="0.01" class="form-control" wire:model.defer="data.total" placeholder="Total">
                @error('total') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">Reason</label>
            <textarea class="form-control" wire:model.defer="data.reason" rows="3" placeholder="Optional"></textarea>
            @error('reason') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <hr />

        <div class="row">
            <div class="form-group col-sm-6">
                <label class="control-label">Add Product (search)</label>
                <input type="text" class="form-control" wire:model="product_search" placeholder="Search product by name / barcode">
            </div>
        </div>

        @error('refundItems') <span class="text-danger small">{{ $message }}</span> @enderror

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th style="width:180px;">Unit</th>
                        <th style="width:140px;">Qty</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refundItems as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item['name'] ?? '' }}</td>
                            <td>
                                <select class="form-control" wire:model="refundItems.{{ $i }}.unit_id">
                                    <option value="">-- Select --</option>
                                    @foreach(($item['units'] ?? []) as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                                @error('refundItems.'.$i.'.unit_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </td>
                            <td>
                                <input type="number" step="0.0001" class="form-control" wire:model="refundItems.{{ $i }}.qty">
                                @error('refundItems.'.$i.'.qty') <span class="text-danger small">{{ $message }}</span> @enderror
                            </td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-danger btn-sm" wire:click="deleteItem({{ $i }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No items yet. Search a product to add.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-success btn-block" wire:click="saveRefund">
            <i class="glyphicon glyphicon-ok"></i> Save Refund
        </button>
    </div>
</div>
