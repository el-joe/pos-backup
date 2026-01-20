<div class="pos-container card-body">
    <!-- BEGIN pos-menu -->
    <div class="pos-menu">
        <!-- BEGIN nav-container -->
        <div class="nav-container">
            <div data-scrollbar="true" data-skip-mobile="true">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-filter="all">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-hamburger"></i> {{ __('general.pages.pos-page.all') }}
                                </div>
                                <div class="card-arrow">
                                    <div class="card-arrow-top-left"></div>
                                    <div class="card-arrow-top-right"></div>
                                    <div class="card-arrow-bottom-left"></div>
                                    <div class="card-arrow-bottom-right"></div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="cat-{{ $category->id }}">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="fa fa-fw {{ $category->icon }}"></i> {{ $category->name }}
                                    </div>
                                    <div class="card-arrow">
                                        <div class="card-arrow-top-left"></div>
                                        <div class="card-arrow-top-right"></div>
                                        <div class="card-arrow-bottom-left"></div>
                                        <div class="card-arrow-bottom-right"></div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- END nav-container -->
    </div>
    <!-- END pos-menu -->

    <!-- BEGIN pos-content -->
    <div class="pos-content">
        <div class="pos-content-container p-4" data-scrollbar="true" data-height="90vh">
            <div class="row gx-4">
                @foreach ($products as $product)
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="cat-{{ $product->category_id }}">
                        <!-- BEGIN card -->
                        <div class="card h-100">
                            <div class="card-body h-100 p-1">
                                <a href="javascript:" class="pos-product" @if($product->unit->children->count() > 0) data-bs-toggle="modal" data-bs-target="#modalPosItem" wire:click="setCurrentProduct({{ $product->id }})" @else  wire:click="addToCart({{ $product->id }})"  @endif>
                                    <div class="img" style="background-image: url({{ $product->image_path }})"></div>
                                    <div class="info">
                                        <div class="title">{{ $product->name }}</div>
                                        <div class="desc">{{ $product->category?->name }}</div>
                                        <div class="price">{{ currencyFormat($product->stockSellPrice($this->data['branch_id'] ?? null), true) }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="card-arrow">
                                <div class="card-arrow-top-left"></div>
                                <div class="card-arrow-top-right"></div>
                                <div class="card-arrow-bottom-left"></div>
                                <div class="card-arrow-bottom-right"></div>
                            </div>
                        </div>
                        <!-- END card -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- END pos-content -->

    <!-- BEGIN pos-sidebar -->
    <div class="pos-sidebar" id="pos-sidebar">
        <div class="d-flex flex-column p-0">
            <div class="pos-sidebar-header">
                <div class="back-btn">
                    <button type="button" onclick="$('#pos').toggleClass('pos-mobile-sidebar-toggled');" class="btn">
                        <i class="bi bi-chevron-left"></i>
                    </button>
					<div class="title">{{ __('general.pages.pos-page.cart') }}</div>
                </div>
            </div>
            <!-- BEGIN pos-sidebar-body -->
            <div class="pos-sidebar-body tab-content" data-scrollbar="true">
                <!-- BEGIN #newOrderTab -->
                <div class="tab-pane fade show active" id="newOrderTab" style="height: 50vh;">
                    @forelse (($data['products'] ?? []) as $key=>$dataProduct)
                        <div class="pos-order">
                            <div class="pos-order-product">
                                <div class="img" style="background-image: url({{ $dataProduct['image'] }})"></div>
                                <div class="flex-1">
                                    <div class="h6 mb-1">{{ $dataProduct['product_name'] }}</div>
                                    <div class="small">{{ currencyFormat($dataProduct['sell_price'], true) }}</div>
                                    <div class="small mb-2">- {{ __('general.pages.pos-page.unit') }}: {{ $dataProduct['unit_name'] }}</div>
                                    <div class="d-flex">
                                        <a href="#" class="btn btn-outline-theme btn-sm" wire:click="updateQty({{ $key }} , -1)"><i class="fa fa-minus"></i></a>
                                        <input type="text" class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 text-center" wire:model="data.products.{{ $key }}.quantity" >
                                        <a href="#" class="btn btn-outline-theme btn-sm" wire:click="updateQty({{ $key }} ,  1)"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="pos-order-price">
                                {{ currencyFormat($dataProduct['subtotal'], true) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-danger">{{ __('general.pages.pos-page.no_items_in_cart') }}</div>
                    @endforelse
                </div>
                <!-- END #orderHistoryTab -->
            </div>
            <!-- END pos-sidebar-body -->

            <!-- BEGIN pos-sidebar-footer -->
            <div class="pos-sidebar-footer">
                <div class="d-flex align-items-center mb-2">
                    <div>{{ __('general.pages.pos-page.subtotal') }}</div>
                    <div class="flex-1 text-end h6 mb-0">{{ currencyFormat($subTotal, true) }}</div>
                </div>
                @if(!$discount || $discount == 0)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0 fw-semibold">
                                    <i class="fa fa-percent me-1 text-theme"></i> {{ __('general.pages.pos-page.discount') }}
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm text-end me-2"
                                    placeholder="{{ __('general.pages.pos-page.enter_code_or_amount') }}"
                                    wire:model="discountCode">
                                <button class="btn btn-theme btn-sm" wire:click="validateDiscountCode">
                                    <i class="fa fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                @else
                    <div class="card border-success bg-success-subtle shadow-sm mb-3">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold text-success">
                                        <i class="fa fa-tag me-1"></i> {{ __('general.pages.pos-page.discount_applied') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ __('general.pages.pos-page.code') }}: <strong>{{ $data['discount']['code'] ?? 'N/A' }}</strong> —
                                        <span class="text-success">{{ $data['discount']['value'] ?? 0 }}% Off</span>
                                        @if($data['discount']['max_discount_amount'] ?? 0)
                                            <span class="text-muted">({{ __('general.pages.pos-page.max') }}: {{ currencyFormat($data['discount']['max_discount_amount'] ?? 0, true) }})</span>
                                        @endif
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold h6 mb-0 text-success">{{ currencyFormat($discount, true) }}</div>
                                    <button class="btn btn-danger btn-sm mt-1" wire:click="removeDiscount">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                @endif

                @if($deferredMode ?? false)
                    <div class="card border-warning bg-warning-subtle shadow-sm mb-3">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-clock me-2 text-warning fa-lg"></i>
                                <div class="flex-1">
                                    <div class="fw-semibold text-warning">
                                        {{ __('general.pages.pos-page.deferred_order') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ __('general.pages.pos-page.deferred_order_hint') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                @endif
                <div class="d-flex align-items-center">
                    <div>{{ __('general.pages.pos-page.taxes') }} ({{ $taxPercentage }}%)</div>
                    <div class="flex-1 text-end h6 mb-0">{{ currencyFormat($tax, true) }}</div>
                </div>
                <hr>
                <div class="d-flex align-items-center mb-2">
                    <div>{{ __('general.pages.pos-page.total') }}</div>
                    <div class="flex-1 text-end h4 mb-0">{{ currencyFormat($total, true) }}</div>
                </div>
                <div class="mt-3">
                    <div class="btn-group d-flex">
                        <a href="#" class="btn btn-outline-default rounded-0 w-80px" wire:click="$set('step', 1)">
                            <i class="bi bi-arrow-left-circle fa-lg"></i><br>
                            <span class="small">{{ __('general.pages.pos-page.previous') }}</span>
                        </a>
                        <a href="#" class="btn btn-outline-theme rounded-0 w-150px" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="bi bi-send-check fa-lg"></i><br>
                            <span class="small">{{ __('general.pages.pos-page.submit_order') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END pos-sidebar-footer -->
        </div>
    </div>
    <!-- END pos-sidebar -->
</div>
