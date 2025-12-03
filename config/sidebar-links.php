<?php

return [
    [
        "title"     => "Dashboard",
        'translated_title' => 'general.titles.statistics',
        "icon"      => "fa fa-tachometer-alt fa-fw",
        "route"     => 'admin.statistics'
    ],
    [
        "title"     => "Cash Register",
        'translated_title' => 'general.titles.cash-register',
        "icon"      => "fa fa-cash-register fa-fw",
        "route"     => 'admin.cash.register.open'
    ],
    [
        "title"     => "POS",
        'translated_title' => 'general.titles.pos',
        "icon"      => "fa fa-store fa-fw",
        "route"     => 'admin.pos'
    ],
    [
        "title"     => "Branches",
        'translated_title' => 'general.titles.branches',
        "icon"      => "fa fa-code-branch fa-fw",
        "route"     => 'admin.branches.list'
    ],
    [
        "title"     => "Products",
        'translated_title' => 'general.titles.products',
        "icon"      => "fa fa-boxes fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Products",
                'translated_title' => 'general.titles.products',
                "route" => 'admin.products.list',
                "icon"  => "fa fa-box fa-fw"
            ],
            [
                "title" => "Categories",
                'translated_title' => 'general.titles.categories',
                "route" => 'admin.categories.list',
                "icon"  => "fa fa-list fa-fw"
            ],
            [
                "title" => "Brands",
                'translated_title' => 'general.titles.brands',
                "route" => 'admin.brands.list',
                "icon"  => "fa fa-tag fa-fw"
            ],
            [
                "title" => "Units",
                'translated_title' => 'general.titles.units',
                "route" => 'admin.units.list',
                "icon"  => "fa fa-ruler fa-fw"
            ],
        ]
    ],
    [
        "title"     => "Inventory",
        'translated_title' => 'general.titles.inventory',
        "icon"      => "fa fa-warehouse fa-fw",
        "route"     => "#",
        'subscription_check' => 'inventory',
        "children"  => [
            [
                "title" => "Stock Transfers",
                'translated_title' => 'general.titles.stock-transfers',
                "route" => 'admin.stocks.transfers.list',
                "icon"  => "fa fa-exchange-alt fa-fw"
            ],
            [
                "title" => "Stock Adjustments",
                'translated_title' => 'general.titles.stock-adjustments',
                "route" => 'admin.stocks.adjustments.list',
                "icon"  => "fa fa-sliders-h fa-fw"
            ],
        ]
    ],
    [
        "title"     => "Sales",
        'translated_title' => 'general.titles.sales',
        "icon"      => "fa fa-chart-line fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Orders",
                'translated_title' => 'general.titles.orders',
                "route" => 'admin.sales.index',
                "icon"  => "fa fa-receipt fa-fw"
            ],
            [
                "title" => "Customers",
                'translated_title' => 'general.titles.customers',
                "route" => 'admin.users.list',
                'route_params' => ['type'=>'customer'],
                "icon"  => "fa fa-user-friends fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Purchases",
        'translated_title' => 'general.titles.purchases',
        "icon"      => "fa fa-shopping-bag fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Orders",
                'translated_title' => 'general.titles.orders',
                "route" => 'admin.purchases.list',
                "icon"  => "fa fa-file-invoice-dollar fa-fw"
            ],
            [
                "title" => "Suppliers",
                'translated_title' => 'general.titles.suppliers',
                "route" => 'admin.users.list',
                'route_params' => ['type'=>'supplier'],
                "icon"  => "fa fa-shipping-fast fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Expenses",
        'translated_title' => 'general.titles.expenses',
        "icon"      => "fa fa-file-invoice fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Expense Categories",
                'translated_title' => 'general.titles.expense-categories',
                "route" => 'admin.expense-categories.list',
                "icon"  => "fa fa-folder-open fa-fw"
            ],
            [
                "title" => "Expenses",
                'translated_title' => 'general.titles.expenses',
                "route" => 'admin.expenses.list',
                "icon"  => "fa fa-money-bill-wave fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Accounting",
        'translated_title' => 'general.titles.accounting',
        "icon"      => "fa fa-file-invoice-dollar fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Payment Methods",
                'translated_title' => 'general.titles.payment-methods',
                "route" => 'admin.payment-methods.list',
                "icon"  => "fa fa-credit-card fa-fw"
            ],
            [
                "title" => "Shipping Companies (SOON)",
                'translated_title' => 'general.titles.shipping-companies',
                "route" => "#",
                "icon"  => "fa fa-shipping-fast fa-fw"
            ],
            [
                "title" => "Transactions",
                'translated_title' => 'general.titles.transactions',
                "route" => 'admin.transactions.list',
                "icon"  => "fa fa-exchange-alt fa-fw",
                'subscription_check' => 'double_entry_accounting'
            ]
        ],
    ],
    [
        "title"     => "Administrators",
        'translated_title' => 'general.titles.administrators',
        "icon"      => "fa fa-user-shield fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Users",
                'translated_title' => 'general.titles.admins',
                "route" => 'admin.admins.list',
                "icon"  => "fa fa-user-tie fa-fw",
                ''
            ],
            [
                "title" => "Roles & Permissions",
                'translated_title' => 'general.titles.roles-permissions',
                "route" => 'admin.roles.list',
                "icon"  => "fa fa-user-lock fa-fw"
            ],
        ],
    ],
    [
        "title"     => "Reports",
        'translated_title' => 'general.titles.reports',
        "icon"      => "fa fa-chart-bar fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Financial Reports",
                'translated_title' => 'general.titles.financial-reports',
                "route" => "#",
                "icon"  => "fa fa-file-invoice-dollar fa-fw",
                'subscription_check' => 'advanced_reports',
                "children" => [
                    [
                        "title" => "Trial Balance",
                        'translated_title' => 'general.titles.trial-balance',
                        "route" => 'admin.reports.financial.trail-balance',
                        "icon"  => "fa fa-balance-scale fa-fw"
                    ],
                    [
                        "title" => "Income Statement",
                        'translated_title' => 'general.titles.income-statement',
                        "route" => 'admin.reports.financial.income-statement',
                        "icon"  => "fa fa-file-invoice-dollar fa-fw"
                    ],
                    [
                        "title" => "Cash Flow Statement",
                        'translated_title' => 'general.titles.cash-flow-statement',
                        "route" => 'admin.reports.financial.cash-flow-statement',
                        "icon"  => "fa fa-water fa-fw"
                    ],
                    [
                        "title" => "General Ledger",
                        'translated_title' => 'general.titles.general-ledger',
                        "route" => 'admin.reports.financial.general-ledger',
                        "icon"  => "fa fa-book fa-fw"
                    ],
                ],
            ],
            [
                "title" => "Sales Reports",
                'translated_title' => 'general.titles.sales-reports',
                "route" => "#",
                "icon"  => "fa fa-chart-line fa-fw",
                "children" => [
                    [
                        "title" => "Sales Summary",
                        'translated_title' => 'general.titles.sales-summary',
                        "route" => 'admin.reports.sales.sales.summary',
                        "icon"  => "fa fa-clipboard-list fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Sales by Product",
                        'translated_title' => 'general.titles.sales-by-product',
                        "route" => 'admin.reports.sales.sales.product',
                        "icon"  => "fa fa-box fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Sales by Branch",
                        'translated_title' => 'general.titles.sales-by-branch',
                        "route" => 'admin.reports.sales.sales.branch',
                        "icon"  => "fa fa-code-branch fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Sales by Customer",
                        'translated_title' => 'general.titles.sales-by-customer',
                        "route" => 'admin.reports.sales.sales.customer',
                        "icon"  => "fa fa-user-friends fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Sales Profit Report",
                        'translated_title' => 'general.titles.sales-profit-report',
                        "route" => 'admin.reports.sales.sales.profit-loss',
                        "icon"  => "fa fa-percentage fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                    [
                        "title" => "VAT on Sales (VAT Payable)",
                        'translated_title' => 'general.titles.vat-on-sales',
                        "route" => 'admin.reports.sales.sales.vat-report',
                        "icon"  => "fa fa-file-invoice fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                    [
                        "title" => "Sales Return Report",
                        'translated_title' => 'general.titles.sales-return-report',
                        "route" => 'admin.reports.sales.sales.returns',
                        "icon"  => "fa fa-undo-alt fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                ],
            ],
            [
                "title" => "Purchase Reports",
                'translated_title' => 'general.titles.purchase-reports',
                "route" => "#",
                "icon"  => "fa fa-file-invoice fa-fw",
                "children" => [
                    [
                        "title" => "Purchase Summary",
                        'translated_title' => 'general.titles.purchase-summary',
                        "route" => 'admin.reports.purchases.purchases.summary',
                        "icon"  => "fa fa-clipboard-list fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Purchases by Product",
                        'translated_title' => 'general.titles.purchases-by-product',
                        "route" => 'admin.reports.purchases.purchases.product',
                        "icon"  => "fa fa-box fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Purchases by Branch",
                        'translated_title' => 'general.titles.purchases-by-branch',
                        "route" => 'admin.reports.purchases.purchases.branch',
                        "icon"  => "fa fa-code-branch fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Purchases by Supplier",
                        'translated_title' => 'general.titles.purchases-by-supplier',
                        "route" => 'admin.reports.purchases.purchases.supplier',
                        "icon"  => "fa fa-shipping-fast fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "VAT on Purchases (VAT Receivable)",
                        'translated_title' => 'general.titles.vat-on-purchases',
                        "route" => 'admin.reports.purchases.purchases.vat-report',
                        "icon"  => "fa fa-file-invoice fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                    [
                        "title" => "Purchase Discount Report",
                        'translated_title' => 'general.titles.purchase-discount-report',
                        "route" => 'admin.reports.purchases.purchases.discounts',
                        "icon"  => "fa fa-percentage fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                    [
                        "title" => "Purchase Return Report",
                        'translated_title' => 'general.titles.purchase-return-report',
                        "route" => 'admin.reports.purchases.purchases.returns',
                        "icon"  => "fa fa-undo-alt fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                ],
            ],
            [
                "title" => "Inventory & COGS Reports",
                'translated_title' => 'general.titles.inventory-cogs-reports',
                "route" => "#",
                "icon"  => "fa fa-warehouse fa-fw",
                "children" => [
                    [
                        "title" => "Inventory Valuation",
                        'translated_title' => 'general.titles.inventory-valuation',
                        "route" => 'admin.reports.inventory.stock-valuation',
                        "icon"  => "fa fa-balance-scale fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Stock Movement Report",
                        'translated_title' => 'general.titles.stock-movement-report',
                        "route" => 'admin.reports.inventory.stock-movement',
                        "icon"  => "fa fa-exchange-alt fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "COGS Report",
                        'translated_title' => 'general.titles.cogs-report',
                        "route" => 'admin.reports.inventory.cogs-report',
                        "icon"  => "fa fa-file-invoice-dollar fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                    [
                        "title" => "Inventory Shortage Report",
                        'translated_title' => 'general.titles.inventory-shortage-report',
                        "route" => 'admin.reports.inventory.shortage-report',
                        "icon"  => "fa fa-box-open fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                ],
            ],
            [
                "title" => "Performance & Analysis Reports",
                'translated_title' => 'general.titles.performance-analysis-reports',
                "route" => "#",
                "icon"  => "fa fa-chart-pie fa-fw",
                'subscription_check' => 'advanced_reports',
                "children" => [
                    [
                        "title" => "Profit Margin by Product",
                        'translated_title' => 'general.titles.profit-margin-by-product',
                        "route" => 'admin.reports.performance.product-profit-margin',
                        "icon"  => "fa fa-percentage fa-fw"
                    ],
                    [
                        "title" => "Customer Outstanding Report",
                        'translated_title' => 'general.titles.customer-outstanding-report',
                        "route" => 'admin.reports.performance.customer-outstanding',
                        "icon"  => "fa fa-user-clock fa-fw"
                    ],
                    [
                        "title" => "Supplier Payable Report",
                        'translated_title' => 'general.titles.supplier-payable-report',
                        "route" => 'admin.reports.performance.supplier-payable',
                        "icon"  => "fa fa-shipping-fast fa-fw"
                    ],
                    [
                        "title" => "Expense Breakdown",
                        'translated_title' => 'general.titles.expense-breakdown',
                        "route" => 'admin.reports.performance.expense-breakdown',
                        "icon"  => "fa fa-file-invoice fa-fw"
                    ],
                    [
                        "title" => "Revenue Breakdown",
                        'translated_title' => 'general.titles.revenue-breakdown',
                        "route" => 'admin.reports.performance.revenue-breakdown-by-branch',
                        "icon"  => "fa fa-chart-bar fa-fw"
                    ],
                    [
                        "title" => "Discount Impact Report",
                        'translated_title' => 'general.titles.discount-impact-report',
                        "route" => 'admin.reports.performance.discount-impact',
                        "icon"  => "fa fa-percentage fa-fw"
                    ],
                    [
                        "title" => "Sales Threshold Report",
                        'translated_title' => 'general.titles.sales-threshold-report',
                        "route" => 'admin.reports.performance.sales-threshold',
                        "icon"  => "fa fa-trophy fa-fw"
                    ]
                ]
            ],
            [
                "title" => "Tax & Compliance Reports",
                'translated_title' => 'general.titles.tax-compliance-reports',
                "route" => "#",
                "icon"  => "fa fa-file-invoice fa-fw",
                'subscription_check' => 'advanced_reports',
                "children" => [
                    [
                        "title" => "VAT Summary Report",
                        'translated_title' => 'general.titles.vat-summary-report',
                        "route" => 'admin.reports.taxes.vat-summary',
                        "icon"  => "fa fa-file-invoice fa-fw"
                    ],
                    [
                        "title" => "With holding Tax Report",
                        'translated_title' => 'general.titles.withholding-tax-report',
                        "route" => 'admin.reports.taxes.withholding-tax',
                        "icon"  => "fa fa-file-invoice-dollar fa-fw"
                    ],
                    [
                        "title" => "Audit Trail Report (Soon)",
                        'translated_title' => 'general.titles.audit-trail-report',
                        "route" => "#",
                        "icon"  => "fa fa-user-secret fa-fw"
                    ]
                ]
            ],
            [
                "title" => "Branch / User / Cash Reports",
                'translated_title' => 'general.titles.branch-user-cash-reports',
                "route" => "#",
                "icon"  => "fa fa-chart-bar fa-fw",
                "children" => [
                    [
                        "title" => "Cash Register Summary",
                        'translated_title' => 'general.titles.cash-register',
                        "route" => 'admin.reports.cash.register.report',
                        "icon"  => "fa fa-cash-register fa-fw",
                        'subscription_check' => 'basic_reports'
                    ],
                    [
                        "title" => "Branch Profitability",
                        'translated_title' => 'general.titles.branch-profitability',
                        "route" => 'admin.reports.branch.profitability',
                        "icon"  => "fa fa-code-branch fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ],
                    [
                        "title" => "User / Cashier Performance",
                        'translated_title' => 'general.titles.cashier-performance',
                        "route" => 'admin.reports.cashier.report',
                        "icon"  => "fa fa-user-tie fa-fw",
                        'subscription_check' => 'advanced_reports'
                    ]
                ]
            ]
        ]
    ],
    [
        'title'    => "Plans & Subscription",
        'translated_title' => 'general.titles.plans-subscriptions',
        'icon'     => 'fa fa-clipboard-list fa-fw',
        'route'    => '#',
        'children' => [
            [
                'title' => 'Plans',
                'translated_title' => 'general.titles.plans',
                'route' => 'admin.plans.list',
                'icon'  => 'fa fa-list fa-fw'
            ],
            [
                'title' => 'Subscriptions',
                'translated_title' => 'general.titles.subscriptions',
                'route' => 'admin.subscriptions.list',
                'icon'  => 'fa fa-file-contract fa-fw'
            ]
        ]
    ],
    [
        "title"     => "Settings",
        'translated_title' => 'general.titles.settings',
        "icon"      => "fa fa-cogs fa-fw",
        "route"     => "#",
        "children"  => [
            [
                "title" => "Discounts",
                'translated_title' => 'general.titles.discounts',
                "route" => 'admin.discounts.list',
                "icon"  => "fa fa-percentage fa-fw",
                'subscription_check' => 'discounts'
            ],
            [
                "title" => "Taxes",
                'translated_title' => 'general.titles.taxes',
                "route" => 'admin.taxes.list',
                "icon"  => "fa fa-file-invoice-dollar fa-fw",
                'subscription_check' => 'taxes'
            ],
            [
                "title" => "General Settings",
                'translated_title' => 'general.titles.general-settings',
                "route" => "#",
                "icon"  => "fa fa-cogs fa-fw"
            ]
        ]
    ]
];
