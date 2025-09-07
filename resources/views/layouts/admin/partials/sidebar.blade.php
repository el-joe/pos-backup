<aside class="sidebar">
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <div class="profile-image">
                    <img src="{{ admin()->image_path }}" alt="user-img" class="img-circle">
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
                    <a href="widgets.html" aria-expanded="false"><i class="fa fa-dashboard fa-fw"></i> <span class="hide-menu"> Dashboard </span></a>
                </li>

                <li>
                    <a href="widgets.html" aria-expanded="false"><i class="fa fa-credit-card fa-fw"></i> <span class="hide-menu"> POS </span></a>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-sitemap fa-fw"></i> <span class="hide-menu"> Branches </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"> <i class="fa fa-sitemap fa-fw"></i> Branches List</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-cube fa-fw"></i> <span class="hide-menu"> Products </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html">  <i class="fa fa-cube fa-fw"></i> Products List</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-th-list fa-fw"></i> Categories List</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-tags fa-fw"></i> Brands List</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-cube fa-fw"></i> Units List</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-cubes fa-fw"></i> <span class="hide-menu"> Inventory </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"> <i class="fa fa-cubes fa-fw"></i> Stock Levels</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-exchange fa-fw"></i> Stock Transfers</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-check-square fa-fw"></i> Stock Taking</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-line-chart fa-fw"></i> <span class="hide-menu"> Sales </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"> <i class="fa fa-line-chart fa-fw"></i> Sales List</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-cog fa-fw"></i> Sales Configuration</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-users fa-fw"></i> Customers</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-shopping-cart fa-fw"></i> <span class="hide-menu"> Purchases </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"> <i class="fa fa-shopping-cart fa-fw"></i> Purchase Orders</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-truck fa-fw"></i> Suppliers</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-money fa-fw"></i> <span class="hide-menu"> Expenses </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"> <i class="fa fa-money fa-fw"></i> Expense Categories</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-list fa-fw"></i> Expenses List</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-calculator fa-fw"></i> <span class="hide-menu"> Accounting </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html">  <i class="fa fa-cube fa-fw"></i> Payment Methods</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-th-list fa-fw"></i> Shipping Companies</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-tags fa-fw"></i> Transactions</a> </li>
                    </ul>
                </li>


                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-users fa-fw"></i> <span class="hide-menu"> Administrator </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"><i class="fa fa-user fa-fw"></i> User Management</a> </li>
                        <li> <a href="index4.html"><i class="fa fa-shield fa-fw"></i> Role Management</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Reports </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Sales Report</a> </li>
                        <li> <a href="index4.html"><i class="fa fa-bar-chart fa-fw"></i> Inventory Report</a> </li>
                        <li> <a href="index4.html"><i class="fa fa-money fa-fw"></i> Expenses Report</a> </li>
                        <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Customers Report</a> </li>
                        <li> <a href="index4.html"><i class="fa fa-truck fa-fw"></i> Suppliers Report</a> </li>
                        <li> <a href="index4.html"><i class="fa  fa-dollar fa-fw"></i> Netprofit Report</a> </li>
                    </ul>
                </li>

                <li>
                    <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-sliders fa-fw"></i> <span class="hide-menu"> Settings </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="index4.html"><i class="fa fa-percent fa-fw"></i> Discounts</a> </li>
                        <li> <a href="index4.html"> <i class="fa fa-money fa-fw"></i> Taxes</a> </li>
                        <li> <a href="index4.html"><i class="fa fa-cog fa-fw"></i> General Settings</a> </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
