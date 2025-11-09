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
                                    <i class="fa fa-fw fa-utensils"></i> All Dishes
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-filter="meat">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-drumstick-bite"></i> Meat
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-filter="burger">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-hamburger"></i> Burger
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-filter="pizza">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-pizza-slice"></i> Pizza
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-filter="drinks">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-cocktail"></i> Drinks
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-filter="desserts">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-ice-cream"></i> Desserts
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
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-filter="snacks">
                            <div class="card">
                                <div class="card-body">
                                    <i class="fa fa-fw fa-cookie-bite"></i> Snacks
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="meat">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-1.jpg)"></div>
                                <div class="info">
                                    <div class="title">Grill Chicken Chop&reg;</div>
                                    <div class="desc">chicken, egg, mushroom, salad</div>
                                    <div class="price">$10.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="meat">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-2.jpg)"></div>
                                <div class="info">
                                    <div class="title">Grill Pork Chop&reg;</div>
                                    <div class="desc">pork, egg, mushroom, salad</div>
                                    <div class="price">$12.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="meat">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-3.jpg)"></div>
                                <div class="info">
                                    <div class="title">Capellini Tomato Sauce&reg;</div>
                                    <div class="desc">spaghetti, tomato, mushroom </div>
                                    <div class="price">$11.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="meat">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-4.jpg)"></div>
                                <div class="info">
                                    <div class="title">Vegan Salad Bowl&reg;</div>
                                    <div class="desc">apple, carrot, tomato </div>
                                    <div class="price">$6.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="pizza">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <div class="pos-product not-available">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-5.jpg)"></div>
                                <div class="info">
                                    <div class="title">Hawaiian Pizza&reg;</div>
                                    <div class="desc">pizza, crab meat, pineapple </div>
                                    <div class="price">$20.99</div>
                                </div>
                                <div class="not-available-text">
                                    <div>Not Available</div>
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
                    <!-- END card -->
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="burger">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-17.jpg)"></div>
                                <div class="info">
                                    <div class="title">Perfect Burger</div>
                                    <div class="desc">Dill pickle, cheddar cheese, tomato, red onion, ground chuck beef</div>
                                    <div class="price">$8.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="drinks">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-6.jpg)"></div>
                                <div class="info">
                                    <div class="title">Avocado Shake</div>
                                    <div class="desc">avocado, milk, vanilla</div>
                                    <div class="price">$3.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="drinks">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-7.jpg)"></div>
                                <div class="info">
                                    <div class="title">Coffee Latte</div>
                                    <div class="desc">espresso, milk</div>
                                    <div class="price">$2.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="drinks">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-8.jpg)"></div>
                                <div class="info">
                                    <div class="title">Vita C Detox Juice</div>
                                    <div class="desc">apricot, apple, carrot and ginger juice</div>
                                    <div class="price">$2.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="snacks">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-9.jpg)"></div>
                                <div class="info">
                                    <div class="title">Pancake</div>
                                    <div class="desc">Non dairy, egg, baking soda, sugar, all purpose flour</div>
                                    <div class="price">$5.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="snacks">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-10.jpg)"></div>
                                <div class="info">
                                    <div class="title">Mushroom soup</div>
                                    <div class="desc">Evaporated milk, marsala wine, beef cubes, chicken broth, butter</div>
                                    <div class="price">$3.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="snacks">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-11.jpg)"></div>
                                <div class="info">
                                    <div class="title">Baked chicken wing</div>
                                    <div class="desc">Chicken wings, a1 steak sauce, honey, cayenne pepper</div>
                                    <div class="price">$6.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="meat">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-12.jpg)"></div>
                                <div class="info">
                                    <div class="title">Veggie Spaghetti</div>
                                    <div class="desc">Yellow squash, pasta, roasted red peppers, zucchini</div>
                                    <div class="price">$12.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="desserts">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-13.jpg)"></div>
                                <div class="info">
                                    <div class="title">Vanilla Ice Cream</div>
                                    <div class="desc">Heavy whipping cream, white sugar, vanilla extract</div>
                                    <div class="price">$3.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="desserts">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-15.jpg)"></div>
                                <div class="info">
                                    <div class="title">Perfect Yeast Doughnuts</div>
                                    <div class="desc">Chocolate hazelnut spread, bread flour, doughnuts, quick rise yeast, butter</div>
                                    <div class="price">$2.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="desserts">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-14.jpg)"></div>
                                <div class="info">
                                    <div class="title">Macarons</div>
                                    <div class="desc">Almond flour, egg whites, heavy cream, food coloring, powdered sugar</div>
                                    <div class="price">$4.99</div>
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
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-4 col-sm-6 pb-4" data-type="desserts">
                    <!-- BEGIN card -->
                    <div class="card h-100">
                        <div class="card-body h-100 p-1">
                            <a href="#" class="pos-product" data-bs-toggle="modal" data-bs-target="#modalPosItem">
                                <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-16.jpg)"></div>
                                <div class="info">
                                    <div class="title">Perfect Vanilla Cupcake</div>
                                    <div class="desc">Baking powder, all purpose flour, plain kefir, vanilla extract</div>
                                    <div class="price">$2.99</div>
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
            </div>
        </div>
    </div>
    <!-- END pos-content -->

    <!-- BEGIN pos-sidebar -->
    <div class="pos-sidebar" id="pos-sidebar">
        <div class="d-flex flex-column p-0">
            <div class="pos-sidebar-header">
                <div class="back-btn">
                    <button type="button" data-toggle-class="pos-mobile-sidebar-toggled" data-toggle-target="#pos" class="btn">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                </div>
            </div>
            <!-- BEGIN pos-sidebar-body -->
            <div class="pos-sidebar-body tab-content" data-scrollbar="true">
                <!-- BEGIN #newOrderTab -->
                <div class="tab-pane fade show active" id="newOrderTab" style="height: 50vh;">
                    <!-- BEGIN pos-order -->
                    <div class="pos-order">
                        <div class="pos-order-product">
                            <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-2.jpg)"></div>
                            <div class="flex-1">
                                <div class="h6 mb-1">Grill Pork Chop</div>
                                <div class="small">$12.99</div>
                                <div class="small mb-2">- size: large</div>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-minus"></i></a>
                                    <input type="text" class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 text-center" value="01">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="pos-order-price">
                            $12.99
                        </div>
                    </div>
                    <!-- END pos-order -->
                    <!-- BEGIN pos-order -->
                    <div class="pos-order">
                        <div class="pos-order-product">
                            <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-8.jpg)"></div>
                            <div class="flex-1">
                                <div class="h6 mb-1">Orange Juice</div>
                                <div class="small">$5.00</div>
                                <div class="small mb-2">
                                    - size: large<br>
                                    - less ice
                                </div>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-minus"></i></a>
                                    <input type="text" class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 text-center" value="02">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="pos-order-price">
                            $10.00
                        </div>
                        <div class="pos-order-confirmation text-center d-flex flex-column justify-content-center">
                            <div class="mb-1">
                                <i class="bi bi-trash fs-36px lh-1"></i>
                            </div>
                            <div class="mb-2">Remove this item?</div>
                            <div>
                                <a href="#" class="btn btn-outline-default btn-sm ms-auto me-2 width-100px">No</a>
                                <a href="#" class="btn btn-outline-theme btn-sm width-100px">Yes</a>
                            </div>
                        </div>
                    </div>
                    <!-- END pos-order -->
                    <!-- BEGIN pos-order -->
                    <div class="pos-order">
                        <div class="pos-order-product">
                            <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-1.jpg)"></div>
                            <div class="flex-1">
                                <div class="h6 mb-1">Grill chicken chop</div>
                                <div class="small">$10.99</div>
                                <div class="small mb-2">
                                    - size: large<br>
                                    - spicy: medium
                                </div>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-minus"></i></a>
                                    <input type="text" class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 text-center" value="01">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="pos-order-price">
                            $10.99
                        </div>
                    </div>
                    <!-- END pos-order -->
                    <!-- BEGIN pos-order -->
                    <div class="pos-order">
                        <div class="pos-order-product">
                            <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-5.jpg)"></div>
                            <div class="flex-1">
                                <div class="h6 mb-1">Hawaiian Pizza</div>
                                <div class="small">$15.00</div>
                                <div class="small mb-2">
                                    - size: large<br>
                                    - more onion
                                </div>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-minus"></i></a>
                                    <input type="text" class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 text-center" value="01">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="pos-order-price">
                            $15.00
                        </div>
                    </div>
                    <!-- END pos-order -->
                    <!-- BEGIN pos-order -->
                    <div class="pos-order">
                        <div class="pos-order-product">
                            <div class="img" style="background-image: url({{ asset('hud/assets') }}/img/pos/product-10.jpg)"></div>
                            <div class="flex-1">
                                <div class="h6 mb-1">Mushroom Soup</div>
                                <div class="small">$3.99</div>
                                <div class="small mb-2">
                                    - size: large<br>
                                    - more cheese
                                </div>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-minus"></i></a>
                                    <input type="text" class="form-control w-50px form-control-sm mx-2 bg-white bg-opacity-25 text-center" value="01">
                                    <a href="#" class="btn btn-outline-theme btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="pos-order-price">
                            $3.99
                        </div>
                    </div>
                    <!-- END pos-order -->
                </div>
                <!-- END #orderHistoryTab -->
            </div>
            <!-- END pos-sidebar-body -->

            <!-- BEGIN pos-sidebar-footer -->
            <div class="pos-sidebar-footer">
                <div class="d-flex align-items-center mb-2">
                    <div>Subtotal</div>
                    <div class="flex-1 text-end h6 mb-0">$30.98</div>
                </div>
                <div class="d-flex align-items-center">
                    <div>Taxes (6%)</div>
                    <div class="flex-1 text-end h6 mb-0">$2.12</div>
                </div>
                <hr>
                <div class="d-flex align-items-center mb-2">
                    <div>Total</div>
                    <div class="flex-1 text-end h4 mb-0">$33.10</div>
                </div>
                <div class="mt-3">
                    <div class="btn-group d-flex">
                        <a href="#" class="btn btn-outline-default rounded-0 w-80px">
                            <i class="bi bi-bell fa-lg"></i><br>
                            <span class="small">Service</span>
                        </a>
                        <a href="#" class="btn btn-outline-default rounded-0 w-80px">
                            <i class="bi bi-receipt fa-fw fa-lg"></i><br>
                            <span class="small">Bill</span>
                        </a>
                        <a href="#" class="btn btn-outline-theme rounded-0 w-150px">
                            <i class="bi bi-send-check fa-lg"></i><br>
                            <span class="small">Submit Order</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END pos-sidebar-footer -->
        </div>
    </div>
    <!-- END pos-sidebar -->
</div>
