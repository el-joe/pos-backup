<?php

return [
    [
        "title"     => "Dashboard",
        "icon"      => "fa fa-dashboard fa-fw",
        "route"     => 'admin.statistics'
    ],
    [
        "title"     => "Cash Register",
        "icon"      => "fa fa-credit-card fa-fw",
        "route"     => 'admin.cash.register.open'
    ],
    [
        "title"     => "POS",
        "icon"      => "fa fa-shopping-cart fa-fw",
        "route"     => 'admin.pos'
    ],
    [
        "title"     => "Branches",
        "icon"      => "fa fa-sitemap fa-fw",
        "route"     => 'admin.branches.list'
    ],
    [
        "title"     => "Products",
        "icon"      => "fa fa-cube fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Products",
                "route" => 'admin.products.list',
                "icon"  => "fa fa-cube fa-fw"
            ],
            [
                "title" => "Categories",
                "route" => 'admin.categories.list',
                "icon"  => "fa fa-th-list fa-fw"
            ],
            [
                "title" => "Brands",
                "route" => 'admin.brands.list',
                "icon"  => "fa fa-tags fa-fw"
            ],
            [
                "title" => "Units",
                "route" => 'admin.units.list',
                "icon"  => "fa fa-balance-scale fa-fw"
            ],
        ]
    ],
    [
        "title"     => "Inventory",
        "icon"      => "fa fa-shopping-cart fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Stock Transfers",
                "route" => 'admin.stocks.transfers.list',
                "icon"  => "fa fa-exchange fa-fw"
            ],
            [
                "title" => "Stock Adjustments",
                "route" => 'admin.stocks.adjustments.list',
                "icon"  => "fa fa-check-square fa-fw"
            ],
        ]
    ],
    [
        "title"     => "Sales",
        "icon"      => "fa fa-line-chart fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Orders",
                "route" => 'admin.sales.index',
                "icon"  => "fa fa-line-chart fa-fw"
            ],
            [
                "title" => "Customers",
                "route" => 'admin.users.list',
                'route_params' => 'customer',
                "icon"  => "fa fa-users fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Purchases",
        "icon"      => "fa fa-shopping-cart fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Orders",
                "route" => 'admin.purchases.list',
                "icon"  => "fa fa-shopping-cart fa-fw"
            ],
            [
                "title" => "Suppliers",
                "route" => 'admin.users.list',
                'route_params' => 'supplier',
                "icon"  => "fa fa-truck fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Expenses",
        "icon"      => "fa fa-money fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Expense Categories",
                "route" => 'admin.expense-categories.list',
                "icon"  => "fa fa-folder fa-fw"
            ],
            [
                "title" => "Expenses",
                "route" => 'admin.expenses.list',
                "icon"  => "fa fa-money fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Accounting",
        "icon"      => "fa fa-calculator fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Payment Methods",
                "route" => 'admin.payment-methods.list',
                "icon"  => "fa fa-cube fa-fw"
            ],
            [
                "title" => "Shipping Companies (SOON)",
                "route" => "#",
                "icon"  => "fa fa-th-list fa-fw"
            ],
            [
                "title" => "Transactions",
                "route" => 'admin.transactions.list',
                "icon"  => "fa fa-tags fa-fw"
            ]
        ],
    ],
    [
        "title"     => "Administrators",
        "icon"      => "fa fa-users fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Users",
                "route" => 'admin.admins.list',
                "icon"  => "fa fa-user fa-fw"
            ],
            [
                "title" => "Roles & Permissions",
                "route" => 'admin.roles.list',
                "icon"  => "fa fa-lock fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Reports",
        "icon"      => "fa fa-bar-chart fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Financial Reports",
                "route" => "#",
                "icon"  => "fa fa-bar-chart fa-fw",
                "children" => [
                    [
                        "title" => "Trial Balance",
                        "route" => 'admin.reports.financial.trail-balance',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Income Statement",
                        "route" => 'admin.reports.financial.income-statement',
                        "icon"  => "fa fa-bar-chart fa-fw"
                    ],
                    [
                        "title" => "Cash Flow Statement",
                        "route" => 'admin.reports.financial.cash-flow-statement',
                        "icon"  => "fa fa-money fa-fw"
                    ],
                    [
                        "title" => "General Ledger",
                        "route" => 'admin.reports.financial.general-ledger',
                        "icon"  => "fa fa-users fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Sales Reports",
                "route" => "#",
                "icon"  => "fa fa-bar-chart fa-fw",
                "children" => [
                    [
                        "title" => "Sales Summary",
                        "route" => 'admin.reports.sales.sales.summary',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Sales by Product",
                        "route" => 'admin.reports.sales.sales.product',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Sales by Branch",
                        "route" => 'admin.reports.sales.sales.branch',
                        "icon"  => "fa fa-money fa-fw"
                    ],
                    [
                        "title" => "Sales by Customer",
                        "route" => 'admin.reports.sales.sales.customer',
                        "icon"  => "fa fa-users fa-fw"
                    ],
                    [
                        "title" => "Sales Profit Report",
                        "route" => 'admin.reports.sales.sales.profit-loss',
                        "icon"  => "fa fa-bar-chart fa-fw"
                    ],
                    [
                        "title" => "VAT on Sales (VAT Payable)",
                        "route" => 'admin.reports.sales.sales.vat-report',
                        "icon"  => "fa fa-calculator fa-fw"
                    ],
                    [
                        "title" => "Sales Return Report",
                        "route" => 'admin.reports.sales.sales.returns',
                        "icon"  => "fa fa-undo fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Purchase Reports",
                "route" => "#",
                "icon"  => "fa fa-shopping-bag fa-fw",
                "children" => [
                    [
                        "title" => "Purchase Summary",
                        "route" => 'admin.reports.purchases.purchases.summary',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Purchases by Product",
                        "route" => 'admin.reports.purchases.purchases.product',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Purchases by Branch",
                        "route" => 'admin.reports.purchases.purchases.branch',
                        "icon"  => "fa fa-money fa-fw"
                    ],
                    [
                        "title" => "Purchases by Supplier",
                        "route" => 'admin.reports.purchases.purchases.supplier',
                        "icon"  => "fa fa-users fa-fw"
                    ],
                    [
                        "title" => "VAT on Purchases (VAT Receivable)",
                        "route" => 'admin.reports.purchases.purchases.vat-report',
                        "icon"  => "fa fa-calculator fa-fw"
                    ],
                    [
                        "title" => "Purchase Discount Report",
                        "route" => 'admin.reports.purchases.purchases.discounts',
                        "icon"  => "fa fa-bar-chart fa-fw"
                    ],
                    [
                        "title" => "Purchase Return Report",
                        "route" => 'admin.reports.purchases.purchases.returns',
                        "icon"  => "fa fa-undo fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Inventory & COGS Reports",
                "route" => "#",
                "icon"  => "fa fa-cubes fa-fw",
                "children" => [
                    [
                        "title" => "Inventory Valuation",
                        "route" => 'admin.reports.inventory.stock-valuation',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Stock Movement Report",
                        "route" => 'admin.reports.inventory.stock-movement',
                        "icon"  => "fa fa-bar-chart fa-fw",
                    ],
                    [
                        "title" => "COGS Report",
                        "route" => 'admin.reports.inventory.cogs-report',
                        "icon"  => "fa fa-calculator fa-fw"
                    ],
                    [
                        "title" => "Inventory Shortage Report",
                        "route" => 'admin.reports.inventory.shortage-report',
                        "icon"  => "fa fa-exclamation-triangle fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Performance & Analysis Reports",
                "route" => "#",
                "icon"  => "fa fa-bar-chart fa-fw",
                "children" => [
                    [
                        "title" => "Profit Margin by Product",
                        "route" => 'admin.reports.performance.product-profit-margin',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Customer Outstanding Report",
                        "route" => 'admin.reports.performance.customer-outstanding',
                        "icon"  => "fa fa-users fa-fw"
                    ],
                    [
                        "title" => "Supplier Payable Report",
                        "route" => 'admin.reports.performance.supplier-payable',
                        "icon"  => "fa fa-truck fa-fw"
                    ],
                    [
                        "title" => "Expense Breakdown",
                        "route" => 'admin.reports.performance.expense-breakdown',
                        "icon"  => "fa fa-money fa-fw"
                    ],
                    [
                        "title" => "Revenue Breakdown",
                        "route" => 'admin.reports.performance.revenue-breakdown-by-branch',
                        "icon"  => "fa fa-calculator fa-fw"
                    ],
                    [
                        "title" => "Discount Impact Report",
                        "route" => 'admin.reports.performance.discount-impact',
                        "icon"  => "fa fa-percent fa-fw"
                    ],
                    [
                        "title" => "Sales Threshold Report",
                        "route" => 'admin.reports.performance.sales-threshold',
                        "icon"  => "fa fa-trophy fa-fw"
                    ]
                ]
            ],
            [
                "title" => "Tax & Compliance Reports",
                "route" => "#",
                "icon"  => "fa fa-calculator fa-fw",
                "children" => [
                    [
                        "title" => "VAT Summary Report",
                        "route" => 'admin.reports.taxes.vat-summary',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "With holding Tax Report",
                        "route" => 'admin.reports.taxes.withholding-tax',
                        "icon"  => "fa fa-bar-chart fa-fw"
                    ],
                    [
                        "title" => "Audit Trail Report (Soon)",
                        "route" => "#",
                        "icon"  => "fa fa-book fa-fw"
                    ]
                ]
            ],
            [
                "title" => "Branch / User / Cash Reports",
                "route" => "#",
                "icon"  => "fa fa-bar-chart fa-fw",
                "children" => [
                    [
                        "title" => "Cash Register Summary",
                        "route" => 'admin.reports.cash.register.report',
                        "icon"  => "fa fa-line-chart fa-fw"
                    ],
                    [
                        "title" => "Branch Profitability",
                        "route" => 'admin.reports.branch.profitability',
                        "icon"  => "fa fa-users fa-fw"
                    ],
                    [
                        "title" => "User / Cashier Performance",
                        "route" => 'admin.reports.cashier.report',
                        "icon"  => "fa fa-credit-card fa-fw"
                    ]
                ]
            ]
        ]
    ],
    [
        "title"     => "Settings",
        "icon"      => "fa fa-sliders fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Discounts",
                "route" => 'admin.discounts.list',
                "icon"  => "fa fa-cog fa-fw"
            ],
            [
                "title" => "Taxes",
                "route" => 'admin.taxes.list',
                "icon"  => "fa fa-shopping-cart fa-fw"
            ],
            [
                "title" => "General Settings",
                "route" => "#",
                "icon"  => "fa fa-cog fa-fw"
            ]
        ]
    ]
];
