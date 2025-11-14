<?php

return [
    [
        "title"     => "Dashboard",
        "icon"      => "fa fa-tachometer-alt fa-fw",
        "route"     => 'admin.statistics'
    ],
    [
        "title"     => "Cash Register",
        "icon"      => "fa fa-cash-register fa-fw",
        "route"     => 'admin.cash.register.open'
    ],
    [
        "title"     => "POS",
        "icon"      => "fa fa-store fa-fw",
        "route"     => 'admin.pos'
    ],
    [
        "title"     => "Branches",
        "icon"      => "fa fa-code-branch fa-fw",
        "route"     => 'admin.branches.list'
    ],
    [
        "title"     => "Products",
        "icon"      => "fa fa-boxes fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Products",
                "route" => 'admin.products.list',
                "icon"  => "fa fa-box fa-fw"
            ],
            [
                "title" => "Categories",
                "route" => 'admin.categories.list',
                "icon"  => "fa fa-list fa-fw"
            ],
            [
                "title" => "Brands",
                "route" => 'admin.brands.list',
                "icon"  => "fa fa-tag fa-fw"
            ],
            [
                "title" => "Units",
                "route" => 'admin.units.list',
                "icon"  => "fa fa-ruler fa-fw"
            ],
        ]
    ],
    [
        "title"     => "Inventory",
        "icon"      => "fa fa-warehouse fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Stock Transfers",
                "route" => 'admin.stocks.transfers.list',
                "icon"  => "fa fa-exchange-alt fa-fw"
            ],
            [
                "title" => "Stock Adjustments",
                "route" => 'admin.stocks.adjustments.list',
                "icon"  => "fa fa-sliders-h fa-fw"
            ],
        ]
    ],
    [
        "title"     => "Sales",
        "icon"      => "fa fa-chart-line fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Orders",
                "route" => 'admin.sales.index',
                "icon"  => "fa fa-receipt fa-fw"
            ],
            [
                "title" => "Customers",
                "route" => 'admin.users.list',
                'route_params' => ['type'=>'customer'],
                "icon"  => "fa fa-user-friends fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Purchases",
        "icon"      => "fa fa-shopping-bag fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Orders",
                "route" => 'admin.purchases.list',
                "icon"  => "fa fa-file-invoice-dollar fa-fw"
            ],
            [
                "title" => "Suppliers",
                "route" => 'admin.users.list',
                'route_params' => ['type'=>'supplier'],
                "icon"  => "fa fa-shipping-fast fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Expenses",
        "icon"      => "fa fa-file-invoice fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Expense Categories",
                "route" => 'admin.expense-categories.list',
                "icon"  => "fa fa-folder-open fa-fw"
            ],
            [
                "title" => "Expenses",
                "route" => 'admin.expenses.list',
                "icon"  => "fa fa-money-bill-wave fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Accounting",
        "icon"      => "fa fa-file-invoice-dollar fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Payment Methods",
                "route" => 'admin.payment-methods.list',
                "icon"  => "fa fa-credit-card fa-fw"
            ],
            [
                "title" => "Shipping Companies (SOON)",
                "route" => "#",
                "icon"  => "fa fa-shipping-fast fa-fw"
            ],
            [
                "title" => "Transactions",
                "route" => 'admin.transactions.list',
                "icon"  => "fa fa-exchange-alt fa-fw"
            ]
        ],
    ],
    [
        "title"     => "Administrators",
        "icon"      => "fa fa-user-shield fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Users",
                "route" => 'admin.admins.list',
                "icon"  => "fa fa-user-tie fa-fw"
            ],
            [
                "title" => "Roles & Permissions",
                "route" => 'admin.roles.list',
                "icon"  => "fa fa-user-lock fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Reports",
        "icon"      => "fa fa-chart-bar fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Financial Reports",
                "route" => "#",
                "icon"  => "fa fa-file-invoice-dollar fa-fw",
                "children" => [
                    [
                        "title" => "Trial Balance",
                        "route" => 'admin.reports.financial.trail-balance',
                        "icon"  => "fa fa-balance-scale fa-fw"
                    ],
                    [
                        "title" => "Income Statement",
                        "route" => 'admin.reports.financial.income-statement',
                        "icon"  => "fa fa-file-invoice-dollar fa-fw"
                    ],
                    [
                        "title" => "Cash Flow Statement",
                        "route" => 'admin.reports.financial.cash-flow-statement',
                        "icon"  => "fa fa-water fa-fw"
                    ],
                    [
                        "title" => "General Ledger",
                        "route" => 'admin.reports.financial.general-ledger',
                        "icon"  => "fa fa-book fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Sales Reports",
                "route" => "#",
                "icon"  => "fa fa-chart-line fa-fw",
                "children" => [
                    [
                        "title" => "Sales Summary",
                        "route" => 'admin.reports.sales.sales.summary',
                        "icon"  => "fa fa-clipboard-list fa-fw"
                    ],
                    [
                        "title" => "Sales by Product",
                        "route" => 'admin.reports.sales.sales.product',
                        "icon"  => "fa fa-box fa-fw"
                    ],
                    [
                        "title" => "Sales by Branch",
                        "route" => 'admin.reports.sales.sales.branch',
                        "icon"  => "fa fa-code-branch fa-fw"
                    ],
                    [
                        "title" => "Sales by Customer",
                        "route" => 'admin.reports.sales.sales.customer',
                        "icon"  => "fa fa-user-friends fa-fw"
                    ],
                    [
                        "title" => "Sales Profit Report",
                        "route" => 'admin.reports.sales.sales.profit-loss',
                        "icon"  => "fa fa-percentage fa-fw"
                    ],
                    [
                        "title" => "VAT on Sales (VAT Payable)",
                        "route" => 'admin.reports.sales.sales.vat-report',
                        "icon"  => "fa fa-file-invoice fa-fw"
                    ],
                    [
                        "title" => "Sales Return Report",
                        "route" => 'admin.reports.sales.sales.returns',
                        "icon"  => "fa fa-undo-alt fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Purchase Reports",
                "route" => "#",
                "icon"  => "fa fa-file-invoice fa-fw",
                "children" => [
                    [
                        "title" => "Purchase Summary",
                        "route" => 'admin.reports.purchases.purchases.summary',
                        "icon"  => "fa fa-clipboard-list fa-fw"
                    ],
                    [
                        "title" => "Purchases by Product",
                        "route" => 'admin.reports.purchases.purchases.product',
                        "icon"  => "fa fa-box fa-fw"
                    ],
                    [
                        "title" => "Purchases by Branch",
                        "route" => 'admin.reports.purchases.purchases.branch',
                        "icon"  => "fa fa-code-branch fa-fw"
                    ],
                    [
                        "title" => "Purchases by Supplier",
                        "route" => 'admin.reports.purchases.purchases.supplier',
                        "icon"  => "fa fa-shipping-fast fa-fw"
                    ],
                    [
                        "title" => "VAT on Purchases (VAT Receivable)",
                        "route" => 'admin.reports.purchases.purchases.vat-report',
                        "icon"  => "fa fa-file-invoice fa-fw"
                    ],
                    [
                        "title" => "Purchase Discount Report",
                        "route" => 'admin.reports.purchases.purchases.discounts',
                        "icon"  => "fa fa-percentage fa-fw"
                    ],
                    [
                        "title" => "Purchase Return Report",
                        "route" => 'admin.reports.purchases.purchases.returns',
                        "icon"  => "fa fa-undo-alt fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Inventory & COGS Reports",
                "route" => "#",
                "icon"  => "fa fa-warehouse fa-fw",
                "children" => [
                    [
                        "title" => "Inventory Valuation",
                        "route" => 'admin.reports.inventory.stock-valuation',
                        "icon"  => "fa fa-balance-scale fa-fw"
                    ],
                    [
                        "title" => "Stock Movement Report",
                        "route" => 'admin.reports.inventory.stock-movement',
                        "icon"  => "fa fa-exchange-alt fa-fw",
                    ],
                    [
                        "title" => "COGS Report",
                        "route" => 'admin.reports.inventory.cogs-report',
                        "icon"  => "fa fa-file-invoice-dollar fa-fw"
                    ],
                    [
                        "title" => "Inventory Shortage Report",
                        "route" => 'admin.reports.inventory.shortage-report',
                        "icon"  => "fa fa-box-open fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Performance & Analysis Reports",
                "route" => "#",
                "icon"  => "fa fa-chart-pie fa-fw",
                "children" => [
                    [
                        "title" => "Profit Margin by Product",
                        "route" => 'admin.reports.performance.product-profit-margin',
                        "icon"  => "fa fa-percentage fa-fw"
                    ],
                    [
                        "title" => "Customer Outstanding Report",
                        "route" => 'admin.reports.performance.customer-outstanding',
                        "icon"  => "fa fa-user-clock fa-fw"
                    ],
                    [
                        "title" => "Supplier Payable Report",
                        "route" => 'admin.reports.performance.supplier-payable',
                        "icon"  => "fa fa-shipping-fast fa-fw"
                    ],
                    [
                        "title" => "Expense Breakdown",
                        "route" => 'admin.reports.performance.expense-breakdown',
                        "icon"  => "fa fa-file-invoice fa-fw"
                    ],
                    [
                        "title" => "Revenue Breakdown",
                        "route" => 'admin.reports.performance.revenue-breakdown-by-branch',
                        "icon"  => "fa fa-chart-bar fa-fw"
                    ],
                    [
                        "title" => "Discount Impact Report",
                        "route" => 'admin.reports.performance.discount-impact',
                        "icon"  => "fa fa-percentage fa-fw"
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
                "icon"  => "fa fa-file-invoice fa-fw",
                "children" => [
                    [
                        "title" => "VAT Summary Report",
                        "route" => 'admin.reports.taxes.vat-summary',
                        "icon"  => "fa fa-file-invoice fa-fw"
                    ],
                    [
                        "title" => "With holding Tax Report",
                        "route" => 'admin.reports.taxes.withholding-tax',
                        "icon"  => "fa fa-file-invoice-dollar fa-fw"
                    ],
                    [
                        "title" => "Audit Trail Report (Soon)",
                        "route" => "#",
                        "icon"  => "fa fa-user-secret fa-fw"
                    ]
                ]
            ],
            [
                "title" => "Branch / User / Cash Reports",
                "route" => "#",
                "icon"  => "fa fa-chart-bar fa-fw",
                "children" => [
                    [
                        "title" => "Cash Register Summary",
                        "route" => 'admin.reports.cash.register.report',
                        "icon"  => "fa fa-cash-register fa-fw"
                    ],
                    [
                        "title" => "Branch Profitability",
                        "route" => 'admin.reports.branch.profitability',
                        "icon"  => "fa fa-code-branch fa-fw"
                    ],
                    [
                        "title" => "User / Cashier Performance",
                        "route" => 'admin.reports.cashier.report',
                        "icon"  => "fa fa-user-tie fa-fw"
                    ]
                ]
            ]
        ]
    ],
    [
        "title"     => "Settings",
        "icon"      => "fa fa-cogs fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Discounts",
                "route" => 'admin.discounts.list',
                "icon"  => "fa fa-percentage fa-fw"
            ],
            [
                "title" => "Taxes",
                "route" => 'admin.taxes.list',
                "icon"  => "fa fa-file-invoice-dollar fa-fw"
            ],
            [
                "title" => "General Settings",
                "route" => "#",
                "icon"  => "fa fa-cogs fa-fw"
            ]
        ]
    ]
];
