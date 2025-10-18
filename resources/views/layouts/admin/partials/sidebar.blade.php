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
                    <a href="widgets.html" aria-expanded="false"><i class="fa fa-credit-card fa-fw"></i> <span class="hide-menu"> Cash Register </span></a>
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
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Financial Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Trial Balance</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-bar-chart fa-fw"></i> Income Statement</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-money fa-fw"></i> Cash Flow Statement</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> General Ledger</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Sales Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Sales Summary</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Sales by Product</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-bar-chart fa-fw"></i> Sales by Category</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-money fa-fw"></i> Sales by Branch</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Sales by Payment Method</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Sales by Customer</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Sales Profit Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> VAT on Sales (VAT Payable)</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Sales Return Report</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Purchase Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Purchase Summary</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Purchase by Supplier</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-bar-chart fa-fw"></i> Purchase by Category</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Purchase Return Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Purchase Discount Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> VAT on Purchases (VAT Receivable)</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Inventory & COGS Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-cubes fa-fw"></i> Stock Valuation Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-exchange fa-fw"></i> Stock Movement Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-calculator fa-fw"></i> COGS Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-warning fa-fw"></i> Inventory Shortage Report</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Performance & Analysis Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-line-chart fa-fw"></i> Profit Margin by Product</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Customer Outstanding Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-users fa-fw"></i> Supplier Payable Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-money fa-fw"></i> Expense Breakdown</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-money fa-fw"></i> Revenue Breakdown</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-percent fa-fw"></i> Discount Impact Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-trophy fa-fw"></i> Sales Threshold (Discount Trigger) Report</a> </li>
                            </ul>
                        </li>
                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Tax & Compliance Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-percent fa-fw"></i> VAT Summary</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-file-text fa-fw"></i> Withholding Tax Report</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-book fa-fw"></i> Audit Trail Report</a> </li>
                            </ul>
                        </li>

                        <li>
                            <a class="waves-effect" href="javascript:void(0);" aria-expanded="false"><i class="fa fa-bar-chart fa-fw"></i> <span class="hide-menu"> Branch / User / Cash Reports </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li> <a href="index4.html"><i class="fa fa-money fa-fw"></i> Cash Register Summary</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-building fa-fw"></i> Branch Profitability</a> </li>
                                <li> <a href="index4.html"><i class="fa fa-user fa-fw"></i> User / Cashier Performance</a> </li>
                            </ul>
                        </li>

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
