<aside class="sidebar">
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <div class="profile-image">
                    <img src="{{ asset('adminBoard') }}/plugins/images/users/hanna.jpg" alt="user-img" class="img-circle">
                    <a href="javascript:void(0);" class="dropdown-toggle u-dropdown text-blue" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="badge badge-danger">
                            <i class="fa fa-angle-down"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated flipInY">
                        {{-- <li><a href="javascript:void(0);"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a href="javascript:void(0);"><i class="fa fa-inbox"></i> Inbox</a></li> --}}
                        {{-- <li role="separator" class="divider"></li> --}}
                        {{-- <li><a href="javascript:void(0);"><i class="fa fa-cog"></i> Account Settings</a></li> --}}
                        {{-- <li role="separator" class="divider"></li> --}}
                        <li><a href="/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                    </ul>
                </div>
                <p class="profile-text m-t-15 font-16"><a href="javascript:void(0);"> {{ admin()->name }}</a></p>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul id="side-menu">
                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="icon-basket fa-fw"></i> <span class="hide-menu"> eCommerce </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html">Dashboard</a> </li>
                        <li> <a href="products.html">Products</a> </li>
                        <li> <a href="product-detail.html">Product Detail</a> </li>
                        <li> <a href="product-edit.html">Product Edit</a> </li>
                        <li> <a href="product-orders.html">Product Orders</a> </li>
                        <li> <a href="product-cart.html">Product Cart</a> </li>
                        <li> <a href="product-checkout.html">Product Checkout</a> </li>
                    </ul>
                </li>
                <li>
                    <a href="widgets.html" aria-expanded="false"><i class="icon-settings fa-fw"></i> <span class="hide-menu"> Widgets </span></a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
