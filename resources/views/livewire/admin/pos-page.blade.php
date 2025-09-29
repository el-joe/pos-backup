<div>
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
                            <div class="thumbnail">
                                <img src="{{ $product->image_path }}" alt="Product Image" class="img-responsive product-img">
                                <div class="caption text-center">
                                    <h5>{{ $product->name }}</h5>
                                    <p class="text-muted">${{ $product->stock_sell_price }}</p>
                                    <p>
                                        @if($product->unit->children->count() > 0)
                                            <button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#addToCartModal" wire:click="setCurrentProduct({{ $product->id }})">Add to Cart</button>
                                        @else
                                            <button class="btn btn-primary btn-sm btn-block" wire:click="addToCart({{ $product->id }})">Add to Cart</button>
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
                            <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">${{ $dataProduct['subtotal'] }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">No items in cart</li>
                    @endforelse
                </ul>

                <!-- Coupon -->
                <div class="input-group" style="margin-bottom:10px;">
                    <input type="text" class="form-control" placeholder="Coupon Code">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Apply</button>
                    </span>
                </div>

                {{-- <div class="alert alert-success" id="coupon-applied" style="margin-top:10px; display:block;">
                    <strong>Coupon Applied:</strong> SAVE10
                    <span class="pull-right">
                        <span class="text-primary"><strong>10% Off</strong></span>
                        <span style="margin-left:10px;">- $10.00</span>
                        <span style="margin-left:15px;">
                            <button type="button" class="close" aria-label="Remove" title="Remove Coupon">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </span>
                    </span>
                </div> --}}


                <!-- Totals -->
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="text-primary">Subtotal</span>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">${{ $subTotal }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Discount</span>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">- ${{ $discount }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Tax (10%)</span>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">${{ $tax }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong class="text-primary">Total</strong>
                        <span class="badge text-primary" style="background-color:transparent; color:#337ab7;">${{ $total }}</span>
                    </li>
                </ul>

                <!-- Checkout Button -->
                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#checkoutModal">
                    Checkout
                </button>

            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="checkoutModalLabel">Complete Payment</h4>
            </div>

            <div class="modal-body">

                <!-- Order Total -->
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
                    <tr>
                    <td>
                        <select class="form-control">
                        <option>Cash</option>
                        <option>Bank Transfer</option>
                        <option>Vodafone Cash</option>
                        <option>Other</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control" placeholder="Amount" value="60.50">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-payment">&times;</button>
                    </td>
                    </tr>
                </tbody>
                </table>

                <!-- Add Payment Button -->
                <button type="button" class="btn btn-default" id="addPaymentBtn">
                + Add Payment
                </button>

                <hr>

                {{-- <!-- Payment Status -->
                <div class="form-group">
                <label><input type="radio" name="paymentStatus" value="full" checked> Full Paid</label>
                <label style="margin-left:15px;"><input type="radio" name="paymentStatus" value="partial"> Partial Paid</label>
                </div> --}}

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success">Confirm Payment</button>
            </div>

            </div>
        </div>
    </div>

    {{-- addToCartModal --}}
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
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->stock($currentProduct->id) ?? 0 }} $)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="quantityInput">Quantity:</label>
                                <input type="number" class="form-control" id="quantityInput" wire:model="selectedQuantity" max="{{ $maxQuantity }}" step="any">
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
        /* adjust as needed */
        overflow-y: auto;
    }

    .product-img {
        border-radius: 4px;
        /* small rounded corners, like a page */
        max-height: 180px;
        /* keep images consistent */
        object-fit: cover;
        /* crop instead of stretch */
    }

</style>

<script>
$(document).ready(function(){
  // Add new payment row
  $('#addPaymentBtn').on('click', function(){
    var row = `
      <tr>
        <td>
          <select class="form-control">
            <option>Cash</option>
            <option>Bank Transfer</option>
            <option>Vodafone Cash</option>
            <option>Other</option>
          </select>
        </td>
        <td>
          <input type="number" class="form-control" placeholder="Amount">
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-sm remove-payment">&times;</button>
        </td>
      </tr>
    `;
    $('#paymentsTable tbody').append(row);
  });

  // Remove payment row
  $(document).on('click', '.remove-payment', function(){
    $(this).closest('tr').remove();
  });
});
</script>
@endpush
