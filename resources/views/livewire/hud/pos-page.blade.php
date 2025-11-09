<div id="wizardLayout1" class="mb-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="nav-wizards-container mb-3">
                <nav class="nav nav-wizards-1 mb-2">
                    <div class="nav-item col" wire:click="$set('step', 1)">
                        <a class="nav-link {{ $step > 0 ? 'completed' : '' }}" href="javascript:void(0);">
                            <div class="nav-no">1</div>
                            <div class="nav-text">Order Details</div>
                        </a>
                    </div>
                    <div class="nav-item col" wire:click="$set('step', 2)">
                        <a class="nav-link {{ $step > 1 ? 'completed' : '' }}" href="javascript:void(0);">
                            <div class="nav-no">2</div>
                            <div class="nav-text">Order Products</div>
                        </a>
                    </div>
                </nav>
            </div>

            <div class="pos card shadow-sm" id="pos">
                @if($step == 1)
                <div class="card-body row g-3">
                    <div class="col-sm-3">
                        <label for="branchSelect" class="fw-semibold">Branch:</label>
                        <select class="form-select" id="branchSelect" wire:model.live="data.branch_id">
                            <option value="">-- Choose Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->name }} @if($branch->phone) - {{ $branch->phone }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('data.branch_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-3">
                        <label for="customerSelect" class="fw-semibold">Customer:</label>
                        <select class="form-select" id="customerSelect" wire:model="selectedCustomerId">
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

                    <div class="col-sm-3">
                        <label for="orderDate" class="fw-semibold">Order Date:</label>
                        <input type="date" class="form-control" id="orderDate" wire:model="data.order_date">
                        @error('data.order_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-3">
                        <label for="invoiceNumber" class="fw-semibold">Invoice Number:</label>
                        <input type="text" class="form-control" id="invoiceNumber" wire:model="data.invoice_number">
                        <small class="text-primary">Leave blank for auto-generated</small>
                    </div>

                    <div class="col-12">
                        <label for="paymentNote" class="fw-semibold">Payment Note:</label>
                        <textarea class="form-control" id="paymentNote" wire:model="data.payment_note"></textarea>
                        @error('data.payment_note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary" wire:click="$set('step', 2)">
                            Next <i class="bi bi-arrow-right-circle fa-lg"></i>
                        </button>
                        {{-- orders list --}}
                        <a onclick="redirectTo('{{ route('admin.sales.index') }}')" href="javascript:" class="btn btn-secondary">
                            Orders List <i class="bi bi-list-ul fa-lg"></i>
                        </a>
                    </div>
                </div>
                @elseif($step == 2)
                    @include('hud.pos-page.products')
                @endif

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>

            <a href="#" class="pos-mobile-sidebar-toggler" data-bs-toggle-class="pos-mobile-sidebar-toggled" data-bs-target="#pos">
                <i class="bi bi-bag"></i>
                <span class="badge bg-danger">5</span>
            </a>

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="checkoutModalLabel">Complete Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Order Total:</strong> ${{ $total }}
                    </div>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th class="text-center" style="width: 50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $index=>$payment)
                                <tr>
                                    <td>
                                        <select class="form-select" wire:model="payments.{{ $index }}.account_id">
                                            <option value="">-- Select Payment Method --</option>
                                            @foreach ($selectedCustomer?->accounts ?? [] as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->paymentMethod?->name }} - {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="Amount"
                                            wire:model="payments.{{ $index }}.amount" step="any" min="0" max="{{ $total }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" wire:click="removePayment({{ $index }})" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Click + Add Payment to get started.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-secondary" wire:click="addPayment">
                        <i class="fa fa-plus"></i> Add Payment
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="confirmPayment">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Cart Modal -->
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addToCartModalLabel">Select Unit & Quantity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        @if($currentProduct)
                            <div class="col-sm-6">
                                <label for="unitSelect" class="fw-semibold">Unit:</label>
                                <select class="form-select" id="unitSelect" wire:model.live="selectedUnitId">
                                    <option value="">-- Select Unit --</option>
                                    @foreach($currentProduct->units() as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }} ({{ number_format($unit->stock($currentProduct->id, $this->data['branch_id']??null)?->sell_price ?? 0, 3) }} $)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="quantityInput" class="fw-semibold">Quantity:</label>
                                <input type="number" class="form-control" id="quantityInput"
                                       wire:model="selectedQuantity" max="{{ $maxQuantity }}" step="any">
                                <small class="text-danger">Max: {{ $maxQuantity ?? 0 }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="addToCart">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('hud/assets/plugins/@highlightjs/cdn-assets/highlight.min.js') }}"></script>
<script src="{{ asset('hud/assets/js/demo/highlightjs.demo.js') }}"></script>
<script src="{{ asset('hud/assets/js/demo/sidebar-scrollspy.demo.js') }}"></script>
<script src="{{ asset('hud/assets/js/demo/pos-customer-order.demo.js') }}"></script>

<script>
    function redirectTo(url){
        // confirmation
        if(confirm('Are you sure you want to leave this page? Unsaved changes will be lost.')){
            window.location.href = url;
        }
    }
</script>
@endpush
