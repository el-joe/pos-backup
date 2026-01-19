<?php

return [
    [
        "title"     => "Dashboard",
        'translated_title' => 'general.titles.statistics',
        "icon"      => "fa fa-tachometer-alt fa-fw",
        "route"     => 'admin.statistics',
        'can' => 'statistics.show'
    ],
    [
        "title"     => "Cash Register",
        'translated_title' => 'general.titles.cash-register',
        "icon"      => "fa fa-cash-register fa-fw",
        "route"     => 'admin.cash.register.open',
        'can' => 'cash_register.create'
    ],
    [
        "title"     => "POS",
        'translated_title' => 'general.titles.pos',
        "icon"      => "fa fa-store fa-fw",
        "route"     => 'admin.pos',
        'can' => 'pos.create'
    ],
    [
        "title"     => "Imports",
        'translated_title' => 'general.titles.imports',
        "icon"      => "fa fa-file-import fa-fw",
        "route"     => 'admin.imports',
        // 'can' => 'imports.create'
    ],
    [
        "title"     => "Branches",
        'translated_title' => 'general.titles.branches',
        "icon"      => "fa fa-code-branch fa-fw",
        "route"     => 'admin.branches.list',
        'can' => 'branches.list,branches.create,branches.update,branches.delete,branches.export,branches.switch'
    ],
    [
        "title"     => "Products",
        'translated_title' => 'general.titles.products',
        "icon"      => "fa fa-boxes fa-fw",
        "route"     => "#",
        'can' => 'products.list,products.create,products.update,products.delete,products.export,categories.list,categories.create,categories.update,categories.delete,categories.export,brands.list,brands.create,brands.update,brands.delete,brands.export,units.list,units.create,units.update,units.delete,units.export',
        "children"  => [
            [
                "title" => "Products",
                'translated_title' => 'general.titles.products',
                "route" => 'admin.products.list',
                "icon"  => "fa fa-box fa-fw",
                'can' => 'products.list,products.create,products.update,products.delete,products.export'
            ],
            [
                "title" => "Categories",
                'translated_title' => 'general.titles.categories',
                "route" => 'admin.categories.list',
                "icon"  => "fa fa-list fa-fw",
                'can' => 'categories.list,categories.create,categories.update,categories.delete,categories.export',
                'enabled' => 'enable_categories'
            ],
            [
                "title" => "Brands",
                'translated_title' => 'general.titles.brands',
                "route" => 'admin.brands.list',
                "icon"  => "fa fa-tag fa-fw",
                'can' => 'brands.list,brands.create,brands.update,brands.delete,brands.export',
                'enabled' => 'enable_brands'
            ],
            [
                "title" => "Units",
                'translated_title' => 'general.titles.units',
                "route" => 'admin.units.list',
                "icon"  => "fa fa-ruler fa-fw",
                'can' => 'units.list,units.create,units.update,units.delete,units.export'
            ],
        ]
    ],
    [
        "title"     => "Inventory",
        'translated_title' => 'general.titles.inventory',
        "icon"      => "fa fa-warehouse fa-fw",
        "route"     => "#",
        'subscription_check' => 'inventory',
        'can' => 'stock_transfers.list,stock_transfers.show,stock_transfers.create,stock_transfers.update,stock_transfers.delete,stock_transfers.export,stock_adjustments.list,stock_adjustments.show,stock_adjustments.create,stock_adjustments.update,stock_adjustments.delete,stock_adjustments.export',
        "children"  => [
            [
                "title" => "Stock Transfers",
                'translated_title' => 'general.titles.stock-transfers',
                "route" => 'admin.stocks.transfers.list',
                "icon"  => "fa fa-exchange-alt fa-fw",
                'can' => 'stock_transfers.list,stock_transfers.show,stock_transfers.create,stock_transfers.update,stock_transfers.delete,stock_transfers.export'
            ],
            [
                "title" => "Stock Adjustments",
                'translated_title' => 'general.titles.stock-adjustments',
                "route" => 'admin.stocks.adjustments.list',
                "icon"  => "fa fa-sliders-h fa-fw",
                'can' => 'stock_adjustments.list,stock_adjustments.show,stock_adjustments.create,stock_adjustments.update,stock_adjustments.delete,stock_adjustments.export'
            ],
        ]
    ],
    [
        "title"     => "Sales",
        'translated_title' => 'general.titles.sales',
        "icon"      => "fa fa-chart-line fa-fw",
        "route"     => "#",
        'can' => 'sales.list,sales.show,sales.update,sales.delete,sales.pay,sales.export,customers.list,customers.show,customers.create,customers.update,customers.delete,customers.export',
        "children"  => [
            [
                "title" => "Orders",
                'translated_title' => 'general.titles.sales_orders',
                "route" => 'admin.sales.index',
                "icon"  => "fa fa-receipt fa-fw",
                'can' => 'sales.list,sales.show,sales.update,sales.delete,sales.pay,sales.export'
            ],
            [
                "title" => "Requests",
                'translated_title' => 'general.titles.sale-requests',
                "route" => 'admin.sale-requests.list',
                "icon"  => "fa fa-file-alt fa-fw",
                'can' => 'sales.list,sales.show,sales.update,sales.delete'
            ],
            [
                "title" => "Customers",
                'translated_title' => 'general.titles.customers',
                "route" => 'admin.users.list',
                'route_params' => ['type'=>'customer'],
                "icon"  => "fa fa-user-friends fa-fw",
                'can' => 'customers.list,customers.show,customers.create,customers.update,customers.delete,customers.export'
            ],
            [
                "title" => "Refunds",
                'translated_title' => 'general.titles.refunds',
                "route" => 'admin.refunds.list',
                'request_params' => ['order_type' => 'sale'],
                "icon"  => "fa fa-undo-alt fa-fw",
                'can' => 'refunds.list,refunds.show,refunds.create,refunds.delete,refunds.export'
            ]
        ],
    ],
    [
        "title"     => "Purchases",
        'translated_title' => 'general.titles.purchases',
        "icon"      => "fa fa-shopping-bag fa-fw",
        "route"     => "#",
        'can' => 'purchases.list,purchases.show,purchases.create,purchases.delete,purchases.pay,purchases.export,suppliers.list,suppliers.show,suppliers.create,suppliers.update,suppliers.delete,suppliers.export',
        "children"  => [
            [
                "title" => "Orders",
                'translated_title' => 'general.titles.purchases_orders',
                "route" => 'admin.purchases.list',
                "icon"  => "fa fa-file-invoice-dollar fa-fw",
                'can' => 'purchases.list,purchases.show,purchases.create,purchases.delete,purchases.pay,purchases.export'
            ],
            [
                "title" => "Requests",
                'translated_title' => 'general.titles.purchase-requests',
                "route" => 'admin.purchase-requests.list',
                "icon"  => "fa fa-file-alt fa-fw",
                'can' => 'purchases.list,purchases.show,purchases.create,purchases.delete'
            ],
            [
                "title" => "Suppliers",
                'translated_title' => 'general.titles.suppliers',
                "route" => 'admin.users.list',
                'route_params' => ['type'=>'supplier'],
                "icon"  => "fa fa-shipping-fast fa-fw",
                'can' => 'suppliers.list,suppliers.show,suppliers.create,suppliers.update,suppliers.delete,suppliers.export'
            ],
            [
                "title" => "Refunds",
                'translated_title' => 'general.titles.refunds',
                "route" => 'admin.refunds.list',
                'request_params' => ['order_type' => 'purchase'],
                "icon"  => "fa fa-undo-alt fa-fw",
                'can' => 'refunds.list,refunds.show,refunds.create,refunds.delete,refunds.export'
            ]
        ],
    ],
    [
        "title"     => "Expenses",
        'translated_title' => 'general.titles.expenses',
        "icon"      => "fa fa-file-invoice fa-fw",
        "route"     => "#",
        'can' => 'expense_categories.list,expense_categories.create,expense_categories.update,expense_categories.delete,expense_categories.export,expenses.list,expenses.create,expenses.update,expenses.delete,expenses.export',
        "children"  => [
            [
                "title" => "Expense Categories",
                'translated_title' => 'general.titles.expense-categories',
                "route" => 'admin.expense-categories.list',
                "icon"  => "fa fa-folder-open fa-fw",
                'can' => 'expense_categories.list,expense_categories.create,expense_categories.update,expense_categories.delete,expense_categories.export'
            ],
            [
                "title" => "Expenses",
                'translated_title' => 'general.titles.expenses',
                "route" => 'admin.expenses.list',
                "icon"  => "fa fa-money-bill-wave fa-fw",
                'can' => 'expenses.list,expenses.create,expenses.update,expenses.delete,expenses.export'
            ],
        ],
    ],
    [
        "title"     => "Accounting",
        'translated_title' => 'general.titles.accounting',
        "icon"      => "fa fa-file-invoice-dollar fa-fw",
        "route"     => "#",
        'can' => 'payment_methods.list,payment_methods.create,payment_methods.update,payment_methods.delete,payment_methods.export,transactions.list,transactions.export',
        "children"  => [
            [
                "title" => "Payment Methods",
                'translated_title' => 'general.titles.payment-methods',
                "route" => 'admin.payment-methods.list',
                "icon"  => "fa fa-credit-card fa-fw",
                'can' => 'payment_methods.list,payment_methods.create,payment_methods.update,payment_methods.delete,payment_methods.export'
            ],
            [
                "title" => "Fixed Assets",
                'translated_title' => 'general.titles.fixed-assets',
                "route" => 'admin.fixed-assets.list',
                "icon"  => "fa fa-building fa-fw",
                'subscription_check' => 'double_entry_accounting',
                'can' => 'fixed_assets.list,fixed_assets.show,fixed_assets.create,fixed_assets.update,fixed_assets.delete,fixed_assets.export'
            ],
            [
                "title" => "Depreciation Expenses",
                'translated_title' => 'general.titles.depreciation-expenses',
                "route" => 'admin.depreciation-expenses.list',
                "icon"  => "fa fa-chart-area fa-fw",
                'subscription_check' => 'double_entry_accounting',
                'can' => 'depreciation_expenses.list,depreciation_expenses.show,depreciation_expenses.create,depreciation_expenses.update,depreciation_expenses.delete,depreciation_expenses.export'
            ],
            [
                "title" => "Shipping Companies (SOON)",
                'translated_title' => 'general.titles.shipping-companies',
                "route" => "#",
                "icon"  => "fa fa-shipping-fast fa-fw",
                //'can' => 'shipping_companies.list,shipping_companies.create,shipping_companies.update,shipping_companies.delete,shipping_companies.export'
            ],
            [
                "title" => "Transactions",
                'translated_title' => 'general.titles.transactions',
                "route" => 'admin.transactions.list',
                "icon"  => "fa fa-exchange-alt fa-fw",
                'subscription_check' => 'double_entry_accounting',
                'can' => 'transactions.list,transactions.export'
            ]
        ],
    ],
    [
        "title"     => "Administrators",
        'translated_title' => 'general.titles.administrators',
        "icon"      => "fa fa-user-shield fa-fw",
        "route"     => "#",
        'can' => 'user_management.list,user_management.create,user_management.update,user_management.delete,user_management.export,role_management.list,role_management.create,role_management.update,role_management.delete,role_management.export',
        "children"  => [
            [
                "title" => "Users",
                'translated_title' => 'general.titles.admins',
                "route" => 'admin.admins.list',
                "icon"  => "fa fa-user-tie fa-fw",
                'can' => 'user_management.list,user_management.create,user_management.update,user_management.delete,user_management.export'
            ],
            [
                "title" => "Roles & Permissions",
                'translated_title' => 'general.titles.roles-permissions',
                "route" => 'admin.roles.list',
                "icon"  => "fa fa-user-lock fa-fw",
                'can' => 'role_management.list,role_management.create,role_management.update,role_management.delete,role_management.export'
            ],
        ],
    ],
    [
        "title"     => "Reports",
        'translated_title' => 'general.titles.reports',
        "icon"      => "fa fa-chart-bar fa-fw",
        "route"     => "#",
        'can' => 'reports.list,reports.export',
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
                    [
                        "title" => "Fixed Assets Report",
                        'translated_title' => 'general.titles.fixed-assets-report',
                        "route" => 'admin.reports.financial.fixed-assets',
                        "icon"  => "fa fa-building fa-fw"
                    ],
                    [
                        "title" => "Depreciation Expenses Report",
                        'translated_title' => 'general.titles.depreciation-expenses-report',
                        "route" => 'admin.reports.financial.depreciation-expenses',
                        "icon"  => "fa fa-chart-area fa-fw"
                    ],
                    [
                        "title" => "Balance Sheet",
                        'translated_title' => 'general.titles.balance-sheet',
                        "route" => 'admin.reports.financial.balance-sheet',
                        "icon"  => "fa fa-balance-scale fa-fw"
                    ],
                    [
                        "title" => "Audit Trail Report",
                        'translated_title' => 'general.titles.audit-trail-report',
                        "route" => "admin.reports.audit.report",
                        "icon"  => "fa fa-user-secret fa-fw"
                    ]
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
        'can'      => 'plans.list,assign,subscriptions.list,subscriptions.cancel',
        'children' => [
            [
                'title' => 'Plans',
                'translated_title' => 'general.titles.plans',
                'route' => 'admin.plans.list',
                'icon'  => 'fa fa-list fa-fw',
                'can' => 'plans.list,plans.assign'
            ],
            [
                'title' => 'Subscriptions',
                'translated_title' => 'general.titles.subscriptions',
                'route' => 'admin.subscriptions.list',
                'icon'  => 'fa fa-file-contract fa-fw',
                'can' => 'subscriptions.list,subscriptions.cancel',
            ]
        ]
    ],
    [
        "title"     => "Settings",
        'translated_title' => 'general.titles.settings',
        "icon"      => "fa fa-cogs fa-fw",
        "route"     => "#",
        'can' => 'discounts.list,discounts.create,discounts.update,discounts.delete,discounts.export,taxes.list,taxes.create,taxes.update,taxes.delete,taxes.export,general_settings.update',
        "children"  => [
            [
                "title" => "Discounts",
                'translated_title' => 'general.titles.discounts',
                "route" => 'admin.discounts.list',
                "icon"  => "fa fa-percentage fa-fw",
                'subscription_check' => 'discounts',
                'can' => 'discounts.list,discounts.create,discounts.update,discounts.delete,discounts.export'
            ],
            [
                "title" => "Taxes",
                'translated_title' => 'general.titles.taxes',
                "route" => 'admin.taxes.list',
                "icon"  => "fa fa-file-invoice-dollar fa-fw",
                'subscription_check' => 'taxes',
                'can' => 'taxes.list,taxes.create,taxes.update,taxes.delete,taxes.export'
            ],
            [
                "title" => "General Settings",
                'translated_title' => 'general.titles.general-settings',
                "route" => "admin.settings",
                "icon"  => "fa fa-cogs fa-fw",
                'can' => 'general_settings.update'
            ]
        ]
    ]
];
