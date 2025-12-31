<div>
   <div class="col-12">
    <div class="card shadow-sm  mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('general.pages.refunds.add_new_refund_order') }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">{{ __('general.pages.refunds.branch') }}</label>
                    @if(admin()->branch_id == null)
                    <div class="d-flex">
                        <select id="branch_id" name="data.branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.refunds.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id']??0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-theme me-2" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                    </div>
                    @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                    @endif
                </div>

                <div class="col-md-4">
                    <label for="order_type" class="form-label">{{ __('general.pages.refunds.order_type') }}</label>
                    <div class="d-flex">
                        <select id="order_type" name="order_type" class="form-select select2">
                            <option value="">{{ __('general.pages.refunds.select_type') }}</option>
                            <option value="sale" {{ $order_type == 'sale' ? 'selected' :'' }}>Sales</option>
                            <option value="purchase" {{ $order_type == 'purchase' ? 'selected' :'' }}>Purchases</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="order_id" class="form-label">{{ __('general.pages.refunds.orders') }}</label>
                    <div class="d-flex">
                        <select id="order_id" name="order_id" class="form-select select2">
                            <option value="">{{ __('general.pages.refunds.select_order') }}</option>
                            @foreach ($orders as $_order)
                                <option value="{{ $_order->id }}" {{ $_order->id == $order_id ? 'selected' : '' }}>#{{ $_order->id }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- add reason --}}
                <div class="col-md-12">
                    <label for="reason" class="form-label">{{ __('general.pages.refunds.reason') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-info-circle"></i></span>
                        <input type="text" id="reason" class="form-control" placeholder="{{ __('general.pages.refunds.reason') }}" wire:model="data.reason">
                    </div>
                </div>


                {{-- <div class="col-md-4">
                    <label for="ref_no" class="form-label">{{ __('general.pages.refunds.ref_no') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                        <input type="text" id="ref_no" class="form-control" placeholder="{{ __('general.pages.refunds.ref_no') }}" wire:model="data.ref_no">
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

    <div class="col-12">
        <div class="card shadow-sm  mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('general.pages.refunds.order_products') }}</h5>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="fa fa-cubes me-2"></i> {{ __('general.pages.sales.sale_products') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('general.pages.sales.product') }}</th>
                                    <th>{{ __('general.pages.sales.quantity') }}</th>
                                    <th>{{ __('general.pages.sales.refund_qty') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order?->{$order_type == 'sale' ? 'saleItems' : 'purchaseItems'} ?? [] as $item)
                                    <tr>
                                        <td><strong>{{ $item->product?->name }} - {{ $item->unit?->name }}</strong></td>
                                        <td>{{ $item->actual_qty }}</td>
                                        <td>
                                            <input type="number" class="form-control" min="0" max="{{ $item->actual_qty }}" wire:model="refundItems.{{ $item->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    {{-- save btn --}}
    <div class="col-12 text-end mb-4">
        <button class="btn btn-success" wire:click="saveRefund"><i class="fa fa-save me-2"></i> {{ __('general.pages.refunds.save_refund') }}</button>
    </div>

</div>

@push('scripts')
@livewire('admin.branches.branch-modal')
@include('layouts.hud.partials.select2-script')
@endpush
