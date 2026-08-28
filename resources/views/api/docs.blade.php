<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tenant REST API Documentation</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { font-family: ui-sans-serif, system-ui, -apple-system, sans-serif; }
  code { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
  pre { white-space: pre-wrap; word-break: break-word; }
</style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-2">Tenant REST API</h1>
    <p class="text-slate-600 mb-8">
        Base URL: <code class="bg-slate-100 px-2 py-0.5 rounded">/api/v1</code> &middot;
        Authenticate every request by sending header <code class="bg-slate-100 px-2 py-0.5 rounded">X-API-Token: your_token</code>
        (or <code class="bg-slate-100 px-2 py-0.5 rounded">?api_token=your_token</code>).
        Generate/regenerate your token from Settings &rarr; API in the admin panel.
    </p>

    <div class="bg-white border border-slate-200 rounded-xl p-5 mb-10">
        <h2 class="text-lg font-semibold mb-2">Response envelope</h2>
        <p class="text-sm text-slate-600 mb-3">Success:</p>
        <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-4 mb-3">{
  "success": true,
  "data": ...,
  "meta": { "current_page": 1, "last_page": 3, "per_page": 20, "total": 57 }
}</pre>
        <p class="text-sm text-slate-600 mb-3">(<code>meta</code> is present only on paginated list endpoints.) Error:</p>
        <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-4">{
  "success": false,
  "message": "The given data was invalid.",
  "errors": { "name": ["The name field is required."] }
}</pre>

        <h3 class="text-sm font-semibold mt-5 mb-2">Common error responses</h3>
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="text-left border-b border-slate-200">
                    <th class="py-1.5 pr-4">Status</th><th class="py-1.5">Meaning</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-slate-100"><td class="py-1.5 pr-4 font-mono">401</td><td class="py-1.5">Unauthorized — missing/invalid <code>X-API-Token</code></td></tr>
                <tr class="border-b border-slate-100"><td class="py-1.5 pr-4 font-mono">403</td><td class="py-1.5">Forbidden — token valid, but the admin lacks the required permission</td></tr>
                <tr class="border-b border-slate-100"><td class="py-1.5 pr-4 font-mono">404</td><td class="py-1.5">Not Found — record does not exist</td></tr>
                <tr><td class="py-1.5 pr-4 font-mono">422</td><td class="py-1.5">Validation error — see <code>errors</code> object</td></tr>
            </tbody>
        </table>
    </div>

    @php
        $badge = fn($m) => match($m) {
            'GET' => 'bg-emerald-100 text-emerald-700',
            'POST' => 'bg-blue-100 text-blue-700',
            'PUT' => 'bg-amber-100 text-amber-700',
            'DELETE' => 'bg-rose-100 text-rose-700',
        };
    @endphp

    @php
        $endpoints = [
            [
                'group' => 'Products',
                'items' => [
                    [
                        'method' => 'GET', 'url' => '/api/v1/products', 'auth' => true,
                        'desc' => 'List products (paginated).',
                        'params' => [
                            ['search', 'string', 'no', 'Filter by name/sku/code'],
                            ['category_id', 'integer', 'no', 'Filter by category'],
                            ['brand_id', 'integer', 'no', 'Filter by brand'],
                            ['page', 'integer', 'no', 'Page number, default 1'],
                            ['per_page', 'integer', 'no', 'Items per page, default 20, max 100'],
                        ],
                        'response' => '{"success":true,"data":[{"id":1,"nameEn":"Coffee","nameAr":"Coffee","code":"P-001","sku":"SKU1","sellPrice":12.5,"qtyInStock":40,"active":true,"category":{"id":1,"name":"Drinks"},"brand":{"id":2,"name":"Acme"},"unit":{"id":1,"name":"Pcs"},"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                    ],
                    ['method' => 'GET', 'url' => '/api/v1/products/{id}', 'auth' => true, 'desc' => 'Show a single product.', 'params' => [['id','integer','yes','Product id (path)']], 'response' => '{"success":true,"data":{"id":1,"nameEn":"Coffee", "...":"..."}}'],
                    ['method' => 'POST', 'url' => '/api/v1/products', 'auth' => true, 'desc' => 'Create a product.', 'params' => [
                        ['name','string','yes','Product name'],
                        ['sku','string','yes','Unique SKU'],
                        ['code','string','no','Internal code'],
                        ['description','string','no',''],
                        ['unit_id','integer','no','Existing unit id'],
                        ['category_id','integer','no','Existing category id'],
                        ['brand_id','integer','no','Existing brand id'],
                        ['weight','number','no',''],
                        ['alert_qty','integer','no','Low stock threshold'],
                        ['active','boolean','no','Default true'],
                    ], 'body' => '{"name":"Coffee","sku":"SKU1","category_id":1,"brand_id":2,"unit_id":1}', 'response' => '{"success":true,"data":{"id":1,"nameEn":"Coffee","...":"..."}}'],
                    ['method' => 'PUT', 'url' => '/api/v1/products/{id}', 'auth' => true, 'desc' => 'Update a product.', 'params' => [['id','integer','yes','Product id (path)']], 'body' => '{"name":"Coffee (updated)"}', 'response' => '{"success":true,"data":{"id":1,"nameEn":"Coffee (updated)","...":"..."}}'],
                    ['method' => 'DELETE', 'url' => '/api/v1/products/{id}', 'auth' => true, 'desc' => 'Soft-delete a product.', 'params' => [['id','integer','yes','Product id (path)']], 'response' => '{"success":true,"data":null}'],
                ],
            ],
            [
                'group' => 'Customers',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/customers', 'auth' => true, 'desc' => 'List customers (users where type=customer).', 'params' => [
                        ['search','string','no','Filter by name/email/phone/address'],
                        ['active','string','no','1, 0, or all'],
                        ['page','integer','no',''],
                        ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"name":"John Doe","email":"john@example.com","phone":"0100000000","address":null,"type":"customer","active":true,"balance":150.5,"salesCount":4,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}'],
                    ['method' => 'GET', 'url' => '/api/v1/customers/{id}', 'auth' => true, 'desc' => 'Show a single customer.', 'params' => [['id','integer','yes','Customer id (path)']], 'response' => '{"success":true,"data":{"id":1,"name":"John Doe","...":"..."}}'],
                    ['method' => 'POST', 'url' => '/api/v1/customers', 'auth' => true, 'desc' => 'Create a customer.', 'params' => [
                        ['name','string','yes',''], ['email','string','no',''], ['phone','string','no',''], ['address','string','no',''], ['active','boolean','no','Default true'],
                    ], 'body' => '{"name":"John Doe","phone":"0100000000"}', 'response' => '{"success":true,"data":{"id":1,"name":"John Doe","...":"..."}}'],
                    ['method' => 'PUT', 'url' => '/api/v1/customers/{id}', 'auth' => true, 'desc' => 'Update a customer.', 'params' => [['id','integer','yes','Customer id (path)']], 'body' => '{"name":"John D."}', 'response' => '{"success":true,"data":{"id":1,"name":"John D.","...":"..."}}'],
                ],
            ],
            [
                'group' => 'Suppliers',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/suppliers', 'auth' => true, 'desc' => 'List suppliers (users where type=supplier).', 'params' => [
                        ['search','string','no',''], ['active','string','no','1, 0, or all'], ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":2,"name":"Acme Supplies","type":"supplier","balance":320,"salesCount":6,"...":"..."}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}'],
                    ['method' => 'GET', 'url' => '/api/v1/suppliers/{id}', 'auth' => true, 'desc' => 'Show a single supplier.', 'params' => [['id','integer','yes','Supplier id (path)']], 'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies","...":"..."}}'],
                    ['method' => 'POST', 'url' => '/api/v1/suppliers', 'auth' => true, 'desc' => 'Create a supplier.', 'params' => [
                        ['name','string','yes',''], ['email','string','no',''], ['phone','string','no',''], ['address','string','no',''], ['active','boolean','no','Default true'],
                    ], 'body' => '{"name":"Acme Supplies"}', 'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies","...":"..."}}'],
                    ['method' => 'PUT', 'url' => '/api/v1/suppliers/{id}', 'auth' => true, 'desc' => 'Update a supplier.', 'params' => [['id','integer','yes','Supplier id (path)']], 'body' => '{"name":"Acme Supplies Ltd"}', 'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies Ltd","...":"..."}}'],
                ],
            ],
            [
                'group' => 'Sales',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/sales', 'auth' => true, 'desc' => 'List sales (paginated).', 'params' => [
                        ['from_date','date','no','YYYY-MM-DD'], ['to_date','date','no','YYYY-MM-DD'],
                        ['status','string','no','paid or unpaid'], ['customer_id','integer','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":10,"invoiceNumber":"INV-000010","customer":{"id":1,"name":"John Doe"},"branchId":1,"date":"2026-08-28T00:00:00+00:00","total":120,"paidAmount":120,"dueAmount":0,"status":"paid"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}'],
                    ['method' => 'GET', 'url' => '/api/v1/sales/{id}', 'auth' => true, 'desc' => 'Show a sale with its items.', 'params' => [['id','integer','yes','Sale id (path)']], 'response' => '{"success":true,"data":{"id":10,"invoiceNumber":"INV-000010","items":[{"id":1,"productId":5,"productName":"Coffee","unitId":1,"qty":2,"sellPrice":12.5,"taxable":false}]}}'],
                    ['method' => 'POST', 'url' => '/api/v1/sales', 'auth' => true, 'desc' => 'Create a POS sale (reuses SellService — deducts stock, posts accounting entries).', 'params' => [
                        ['customer_id','integer','no','Nullable for walk-in'],
                        ['branch_id','integer','yes',''],
                        ['order_date','date','no','Defaults to now'],
                        ['discount_type','string','no','fixed or percentage'],
                        ['discount_value','number','no',''],
                        ['is_deferred','boolean','no','Defer inventory/accounting posting'],
                        ['payments','array','no','[{account_id, amount}]'],
                        ['products','array','yes','[{id, unit_id, quantity, sell_price, unit_cost, taxable}]'],
                    ], 'body' => '{"customer_id":1,"branch_id":1,"products":[{"id":5,"unit_id":1,"quantity":2,"sell_price":12.5}],"payments":[{"account_id":1,"amount":25}]}', 'response' => '{"success":true,"data":{"id":11,"invoiceNumber":"INV-000011","total":25,"paidAmount":25,"dueAmount":0}}'],
                ],
            ],
            [
                'group' => 'Purchases',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/purchases', 'auth' => true, 'desc' => 'List purchases (paginated).', 'params' => [
                        ['from_date','date','no',''], ['to_date','date','no',''], ['status','string','no','pending, partial_paid, full_paid'],
                        ['supplier_id','integer','no',''], ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":4,"refNo":"PUR-004","supplier":{"id":2,"name":"Acme Supplies"},"total":500,"paidAmount":500,"dueAmount":0,"status":"full_paid"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}'],
                    ['method' => 'GET', 'url' => '/api/v1/purchases/{id}', 'auth' => true, 'desc' => 'Show a purchase with its items.', 'params' => [['id','integer','yes','Purchase id (path)']], 'response' => '{"success":true,"data":{"id":4,"refNo":"PUR-004","items":[{"id":1,"productId":5,"qty":10,"purchasePrice":8}]}}'],
                    ['method' => 'POST', 'url' => '/api/v1/purchases', 'auth' => true, 'desc' => 'Create a purchase (reuses PurchaseService — updates stock and accounting entries).', 'params' => [
                        ['supplier_id','integer','yes',''],
                        ['branch_id','integer','yes',''],
                        ['ref_no','string','no',''],
                        ['payment_status','string','no','pending, partial_paid, full_paid'],
                        ['orderProducts','array','yes','[{id, unit_id, qty, purchase_price, sell_price, discount_percentage, tax_percentage, x_margin}]'],
                    ], 'body' => '{"supplier_id":2,"branch_id":1,"orderProducts":[{"id":5,"unit_id":1,"qty":10,"purchase_price":8,"sell_price":12.5,"discount_percentage":0,"tax_percentage":0,"x_margin":0}]}', 'response' => '{"success":true,"data":{"id":5,"refNo":"PUR-005","total":80}}'],
                ],
            ],
            [
                'group' => 'Expenses',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/expenses', 'auth' => true, 'desc' => 'List expenses (paginated).', 'params' => [
                        ['branch_id','integer','no',''], ['expense_category_id','integer','no',''],
                        ['from_date','date','no',''], ['to_date','date','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"amount":100,"total":100,"expenseDate":"2026-08-28","category":{"id":1,"name":"Rent"}}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}'],
                    ['method' => 'GET', 'url' => '/api/v1/expenses/{id}', 'auth' => true, 'desc' => 'Show a single expense.', 'params' => [['id','integer','yes','Expense id (path)']], 'response' => '{"success":true,"data":{"id":1,"amount":100,"category":{"id":1,"name":"Rent"}}}'],
                    ['method' => 'POST', 'url' => '/api/v1/expenses', 'auth' => true, 'desc' => 'Create an expense.', 'params' => [
                        ['expense_category_id','integer','yes',''], ['amount','number','yes',''], ['expense_date','date','yes',''],
                        ['branch_id','integer','no',''], ['tax_percentage','number','no',''], ['note','string','no',''],
                    ], 'body' => '{"expense_category_id":1,"amount":100,"expense_date":"2026-08-28"}', 'response' => '{"success":true,"data":{"id":2,"amount":100}}'],
                    ['method' => 'PUT', 'url' => '/api/v1/expenses/{id}', 'auth' => true, 'desc' => 'Update an expense.', 'params' => [['id','integer','yes','Expense id (path)']], 'body' => '{"amount":150}', 'response' => '{"success":true,"data":{"id":2,"amount":150}}'],
                    ['method' => 'DELETE', 'url' => '/api/v1/expenses/{id}', 'auth' => true, 'desc' => 'Soft-delete an expense.', 'params' => [['id','integer','yes','Expense id (path)']], 'response' => '{"success":true,"data":null}'],
                ],
            ],
            [
                'group' => 'Statistics',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/statistics/summary', 'auth' => true, 'desc' => "Today's totals for sales, purchases and expenses.", 'params' => [], 'response' => '{"success":true,"data":{"date":"2026-08-28","salesTotal":250,"salesCount":3,"purchasesTotal":80,"purchasesCount":1,"expensesTotal":100}}'],
                    ['method' => 'GET', 'url' => '/api/v1/statistics/daily', 'auth' => true, 'desc' => 'Daily breakdown for the last N days.', 'params' => [['days','integer','no','Default 30, max 365']], 'response' => '{"success":true,"data":[{"date":"2026-08-01","salesTotal":100,"purchasesTotal":0,"expensesTotal":0}]}'],
                ],
            ],
            [
                'group' => 'Settings',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/settings', 'auth' => true, 'desc' => 'Return all tenant settings as key/value pairs.', 'params' => [], 'response' => '{"success":true,"data":{"business_name":"My Store","currency_id":1}}'],
                    ['method' => 'POST', 'url' => '/api/v1/settings/regenerate-token', 'auth' => true, 'desc' => "Regenerate the authenticated admin's API token. The new token is returned once — store it immediately.", 'params' => [], 'response' => '{"success":true,"data":{"token":"5f4dcc3b5aa765d61d8327deb882cf99..."}}'],
                ],
            ],
            [
                'group' => 'Documentation',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/docs', 'auth' => false, 'desc' => 'This documentation page (no token required).', 'params' => [], 'response' => 'text/html'],
                ],
            ],
        ];
    @endphp

    <div class="space-y-10">
        @foreach($endpoints as $group)
            <section>
                <h2 class="text-xl font-bold mb-4 border-b border-slate-200 pb-2">{{ $group['group'] }}</h2>
                <div class="space-y-6">
                    @foreach($group['items'] as $item)
                        <div class="bg-white border border-slate-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $badge($item['method']) }}">{{ $item['method'] }}</span>
                                <code class="text-sm font-semibold">{{ $item['url'] }}</code>
                                @if($item['auth'])
                                    <span class="text-xs text-slate-500">Auth: Include header <code class="bg-slate-100 px-1.5 py-0.5 rounded">X-API-Token: your_token</code></span>
                                @else
                                    <span class="text-xs text-slate-500">No auth required</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600 mb-3">{{ $item['desc'] }}</p>

                            @if(count($item['params']))
                                <table class="w-full text-xs border-collapse mb-3">
                                    <thead>
                                        <tr class="text-left border-b border-slate-200 text-slate-500">
                                            <th class="py-1 pr-3">Name</th><th class="py-1 pr-3">Type</th><th class="py-1 pr-3">Required</th><th class="py-1">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item['params'] as $p)
                                            <tr class="border-b border-slate-100">
                                                <td class="py-1 pr-3 font-mono">{{ $p[0] }}</td>
                                                <td class="py-1 pr-3">{{ $p[1] }}</td>
                                                <td class="py-1 pr-3">{{ $p[2] }}</td>
                                                <td class="py-1">{{ $p[3] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if(isset($item['body']))
                                <p class="text-xs font-semibold text-slate-500 mb-1">Request body</p>
                                <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-3 mb-3">{{ $item['body'] }}</pre>
                            @endif

                            <p class="text-xs font-semibold text-slate-500 mb-1">Response</p>
                            <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-3">{{ $item['response'] }}</pre>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>

</body>
</html>
