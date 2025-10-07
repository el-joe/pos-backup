<div class="row">
    <!-- Customer Selection -->
    <div class="col-md-12">
        <div class="panel panel-primary" style="margin-bottom:20px;">
            <div class="panel-heading">
                <h4 class="panel-title">Select Customer</h4>
            </div>
            <div class="panel-body">
                <div class="form-group col-sm-4" style="margin-bottom:0;">
                    <label for="customerSelect" style="font-weight:600;">Customer:</label>
                    <select class="form-control customer-select" id="customerSelect" wire:model="selectedCustomerId">
                        <option value="">-- Choose Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} @if($customer->phone) - {{ $customer->phone }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('selectedCustomerId')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {{-- order_date --}}
                <div class="form-group col-sm-4" style="margin-bottom:0;">
                    <label for="orderDate" style="font-weight:600;">Order Date:</label>
                    <input type="date" class="form-control" id="orderDate" wire:model="data.order_date">
                    @error('data.order_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {{-- invoice_number --}}
                <div class="form-group col-sm-4" style="margin-bottom:0;">
                    <label for="invoiceNumber" style="font-weight:600;">Invoice Number:</label>
                    <input type="text" class="form-control" id="invoiceNumber" wire:model="data.invoice_number">
                    <small class="text-primary">Leave blank for auto-generated</small>
                </div>
                {{-- full col payment_note --}}
                <div class="form-group col-sm-12" style="margin-bottom:0;">
                    <label for="paymentNote" style="font-weight:600;">Payment Note:</label>
                    <textarea class="form-control" id="paymentNote" wire:model="data.payment_note"></textarea>
                    @error('data.payment_note')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Products List -->
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Products</h4>
            </div>
            <div class="panel-body products-scroll">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-sm-4">
                            <div class="thumbnail product-card">
                                <img src="{{ $product->image_path }}" alt="Product Image" class="img-responsive product-img">
                                <div class="caption text-center">
                                    <h5>{{ $product->name }}</h5>
                                    <p class="text-muted">${{ $product->stock_sell_price }}</p>
                                    <p>
                                        @if($product->unit->children->count() > 0)
                                            <button class="btn btn-primary btn-sm btn-block"
                                                data-toggle="modal" data-target="#addToCartModal"
                                                wire:click="setCurrentProduct({{ $product->id }})">
                                                Add to Cart
                                            </button>
                                        @else
                                            <button class="btn btn-primary btn-sm btn-block"
                                                wire:click="addToCart({{ $product->id }})">
                                                Add to Cart
                                            </button>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart / Checkout -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Cart</h4>
            </div>
            <div class="panel-body">

                <!-- Cart Items -->
                <ul class="list-group">
                    @forelse (($data['products'] ?? []) as $key=>$dataProduct)
                        <li class="list-group-item">
                            {{ $dataProduct['name'] }} (x{{ $dataProduct['quantity'] }})
                            <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">
                                ${{ $dataProduct['subtotal'] }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item">No items in cart</li>
                    @endforelse
                </ul>

                <!-- Coupon -->
                @if(!($data['discount'] ?? false))
                    <div class="input-group" style="margin-bottom:10px;">
                        <input type="text" class="form-control" placeholder="Coupon Code" wire:model="discountCode">
                        <span class="input-group-btn">
                            <button class="btn btn-default" wire:click="validateDiscountCode" type="button">Apply</button>
                        </span>
                    </div>
                @else
                    <div class="alert alert-info coupon-applied-box" style="margin-top:10px; padding:15px; border-radius:8px; border:1px solid #ddd;">
                        <div class="row">
                            <div class="col-xs-8">
                                <div style="line-height:1.4;">
                                    <strong>Coupon Applied:</strong>
                                    <span style="font-weight:600;">{{ $data['discount']['code'] ?? '' }}</span>
                                    <br>
                                    <small>
                                        <strong>{{ $data['discount']['value'] ?? 0 }}% Off</strong>
                                        @if($data['discount']['max_discount_amount'] ?? 0)
                                            (Max: ${{ $data['discount']['max_discount_amount'] ?? 0 }})
                                        @endif
                                        - <strong>-${{ $discount }}</strong>
                                    </small>
                                </div>
                            </div>
                            <div class="col-xs-4 text-right">
                                <button type="button" class="btn btn-sm" wire:click="removeCoupon"
                                        style="padding:4px 8px; border:1px solid #ddd; background:transparent; border-radius:4px;"
                                        title="Remove Coupon">
                                    <i class="fa fa-times"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Totals -->
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="text-primary">Subtotal</span>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">
                            ${{ $subTotal }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Discount</span>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">
                            - ${{ $discount }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Tax ({{ $taxPercentage }}%)</span>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">
                            ${{ $tax }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong class="text-primary">Total</strong>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">
                            ${{ $total }}
                        </span>
                    </li>
                </ul>

                <!-- Checkout Button -->
                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#checkoutModal">
                    Checkout
                </button>

            </div>
        </div>
    </div>


<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" wire:ignore.self>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="checkoutModalLabel">Complete Payment</h4>
            </div>

            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Order Total:</strong> ${{ $total }}
                </div>

                <!-- Payment Splits -->
                <table class="table table-bordered" id="paymentsTable">
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th style="width: 50px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $index=>$payment)
                            <tr>
                                <td>
                                    <select class="form-control" wire:model="payments.{{ $index }}.account_id">
                                        <option value="">-- Select Payment Method --</option>
                                        @foreach ($selectedCustomer?->accounts ?? [] as $account)
                                            <option value="{{ $account->id }}" {{ $payment['account_id'] == $account->id ? 'selected' : '' }}>{{ $account->paymentMethod?->name }} - {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" placeholder="Amount" wire:model="payments.{{ $index }}.amount" step="any" min="0" max="{{ $total }}">
                                </td>
                                <td>
                                    <button type="button" wire:click="removePayment({{ $index }})" class="btn btn-danger btn-sm remove-payment">&times;</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Click + Add Payment to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <button type="button" class="btn btn-default" id="addPaymentBtn" wire:click="addPayment">
                    + Add Payment
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" wire:click="confirmPayment">Confirm Payment</button>
            </div>

        </div>
    </div>
</div>

<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="addToCartModalLabel">Select Unit & Quantity</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    @if($currentProduct)
                        <div class="form-group col-sm-6">
                            <label for="unitSelect">Unit:</label>
                            <select class="form-control" id="unitSelect" wire:model.live="selectedUnitId">
                                <option value="">-- Select Unit --</option>
                                @foreach($currentProduct->units() as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->name }} ({{ number_format($unit->stock($currentProduct->id)?->sell_price ?? 0, 3) }} $)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="quantityInput">Quantity:</label>
                            <input type="number" class="form-control" id="quantityInput"
                                   wire:model="selectedQuantity" max="{{ $maxQuantity }}" step="any">
                            <small class="text-danger">Max: {{ $maxQuantity ?? 0 }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" wire:click="addToCart">Add to Cart</button>
            </div>
        </div>
    </div>
</div>
</div>


@push('styles')
<style>
    .products-scroll {
        max-height: 600px;
        overflow-y: auto;
    }

    .product-img {
        border-radius: 4px;
        max-height: 180px;
        object-fit: cover;
    }

    .product-card {
        transition: box-shadow 0.2s ease-in-out;
    }

    .product-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .customer-select {
        border: 2px solid #337ab7;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .customer-select:focus {
        border-color: #286090;
        box-shadow: 0 0 5px rgba(51, 122, 183, 0.5);
    }

    #checkoutModal .modal-header .close {
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 28px;
    }

    #checkoutModal .modal-title {
        font-weight: 600;
        text-align: center;
    }
</style>

<script>
$(document).ready(function(){

    $(document).on('click', '.remove-payment', function(){
        $(this).closest('tr').remove();
    });
});
</script>
@endpush
