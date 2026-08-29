<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tenant REST API Documentation</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { font-family: ui-sans-serif, system-ui, -apple-system, sans-serif; }
  code { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
  pre { white-space: pre-wrap; word-break: break-word; }
  .endpoint-card[hidden] { display: none; }
</style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-2">Tenant REST API</h1>
    <p class="text-slate-600 mb-4">
        Base URL: <code class="bg-slate-100 px-2 py-0.5 rounded">/api/v1</code>
    </p>

    <div class="bg-white border border-slate-200 rounded-xl p-5 mb-8">
        <h2 class="text-lg font-semibold mb-2">Authentication</h2>
        <p class="text-sm text-slate-600 mb-3">Every request (except this documentation page) must be authenticated with your API token, using <strong>either</strong> of these two methods:</p>
        <p class="text-sm text-slate-600 mb-1">1. Header (recommended):</p>
        <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-3 mb-3">X-API-Token: YOUR_TOKEN</pre>
        <p class="text-sm text-slate-600 mb-1">2. Query string parameter:</p>
        <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-3 mb-3">GET /api/v1/products?api_token=YOUR_TOKEN</pre>
        <p class="text-sm text-slate-600">Generate/regenerate your token from Settings &rarr; API in the admin panel, or via <code class="bg-slate-100 px-1.5 py-0.5 rounded">POST /api/v1/settings/regenerate-token</code>. The token is tied to a specific admin account, and permissions are enforced per-endpoint based on that admin's role.</p>
    </div>

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
                <tr class="border-b border-slate-100"><td class="py-1.5 pr-4 font-mono">401</td><td class="py-1.5">Unauthorized — missing/invalid token (<code>X-API-Token</code> header or <code>api_token</code> query param)</td></tr>
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
                        'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/products?search=coffee' \\\n  -H 'X-API-Token: YOUR_TOKEN'",
                    ],
                    [
                        'method' => 'GET', 'url' => '/api/v1/products/{id}', 'auth' => true, 'desc' => 'Show a single product.',
                        'params' => [['id','integer','yes','Product id (path)']],
                        'response' => '{"success":true,"data":{"id":1,"nameEn":"Coffee","nameAr":"Coffee","code":"P-001","sku":"SKU1","sellPrice":12.5,"qtyInStock":40,"active":true,"category":{"id":1,"name":"Drinks"},"brand":{"id":2,"name":"Acme"},"unit":{"id":1,"name":"Pcs"},"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                        'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/products/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'",
                    ],
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
                    ], 'body' => '{"name":"Coffee","sku":"SKU1","category_id":1,"brand_id":2,"unit_id":1}',
                       'response' => '{"success":true,"data":{"id":1,"nameEn":"Coffee","nameAr":"Coffee","code":null,"sku":"SKU1","sellPrice":0,"qtyInStock":0,"active":true,"category":{"id":1,"name":"Drinks"},"brand":{"id":2,"name":"Acme"},"unit":{"id":1,"name":"Pcs"},"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/products' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"name\":\"Coffee\",\"sku\":\"SKU1\",\"category_id\":1,\"brand_id\":2,\"unit_id\":1}'"],
                    ['method' => 'PUT', 'url' => '/api/v1/products/{id}', 'auth' => true, 'desc' => 'Update a product.', 'params' => [['id','integer','yes','Product id (path)']],
                       'body' => '{"name":"Coffee (updated)"}',
                       'response' => '{"success":true,"data":{"id":1,"nameEn":"Coffee (updated)","nameAr":"Coffee (updated)","code":null,"sku":"SKU1","sellPrice":0,"qtyInStock":0,"active":true,"category":{"id":1,"name":"Drinks"},"brand":{"id":2,"name":"Acme"},"unit":{"id":1,"name":"Pcs"},"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:05:00+00:00"}}',
                       'curl' => "curl -X PUT 'https://YOUR-TENANT.com/api/v1/products/1' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"name\":\"Coffee (updated)\"}'"],
                    ['method' => 'DELETE', 'url' => '/api/v1/products/{id}', 'auth' => true, 'desc' => 'Soft-delete a product.', 'params' => [['id','integer','yes','Product id (path)']],
                       'response' => '{"success":true,"data":null}',
                       'curl' => "curl -X DELETE 'https://YOUR-TENANT.com/api/v1/products/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Customers',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/customers', 'auth' => true, 'desc' => 'List customers (users where type=customer).', 'params' => [
                        ['search','string','no','Filter by name/email/phone/address'],
                        ['active','string','no','1, 0, or all (default all)'],
                        ['branch_id','integer','no','Filter to customers with sales in this branch'],
                        ['balance_filter','string','no','with_balance or without_balance'],
                        ['page','integer','no',''],
                        ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"name":"John Doe","email":"john@example.com","phone":"0100000000","address":null,"type":"customer","active":true,"balance":150.5,"salesCount":4,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/customers?search=john' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    [
                        'method' => 'GET', 'url' => '/api/v1/customers/{id}', 'auth' => true, 'desc' => 'Show a single customer. Pass ?include=sales to embed the 10 most recent sales.',
                        'params' => [
                            ['id','integer','yes','Customer id (path)'],
                            ['include','string','no','Set to "sales" to embed recentSales'],
                        ],
                        'response' => '{"success":true,"data":{"id":1,"name":"John Doe","email":"john@example.com","phone":"0100000000","address":"12 Tahrir St, Cairo","type":"customer","active":true,"balance":150.5,"salesCount":4,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00","recentSales":[{"id":10,"invoiceNumber":"INV-000010","total":120,"paidAmount":120,"dueAmount":0,"date":"2026-08-28"}]}}',
                        'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/customers/1?include=sales' \\\n  -H 'X-API-Token: YOUR_TOKEN'",
                    ],
                    ['method' => 'POST', 'url' => '/api/v1/customers', 'auth' => true, 'desc' => 'Create a customer.', 'params' => [
                        ['name','string','yes',''], ['email','string','no',''], ['phone','string','no',''], ['address','string','no',''], ['active','boolean','no','Default true'],
                    ], 'body' => '{"name":"John Doe","phone":"0100000000"}',
                       'response' => '{"success":true,"data":{"id":1,"name":"John Doe","email":null,"phone":"0100000000","address":null,"type":"customer","active":true,"balance":0,"salesCount":0,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/customers' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"name\":\"John Doe\",\"phone\":\"0100000000\"}'"],
                    ['method' => 'PUT', 'url' => '/api/v1/customers/{id}', 'auth' => true, 'desc' => 'Update a customer.', 'params' => [['id','integer','yes','Customer id (path)']],
                       'body' => '{"name":"John D."}',
                       'response' => '{"success":true,"data":{"id":1,"name":"John D.","email":null,"phone":"0100000000","address":null,"type":"customer","active":true,"balance":0,"salesCount":0,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:05:00+00:00"}}',
                       'curl' => "curl -X PUT 'https://YOUR-TENANT.com/api/v1/customers/1' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"name\":\"John D.\"}'"],
                    ['method' => 'POST', 'url' => '/api/v1/customers/{id}/payments', 'auth' => true, 'desc' => 'Record a payment from the customer, auto-applied FIFO against their oldest outstanding (non-deferred) sales.', 'params' => [
                        ['id','integer','yes','Customer id (path)'],
                        ['account_id','integer','yes','Account the payment is deposited into'],
                        ['amount','number','yes','Must not exceed total outstanding balance'],
                        ['note','string','no','Max 255 chars'],
                    ], 'body' => '{"account_id":1,"amount":50,"note":"Partial settlement"}',
                       'response' => '{"success":true,"data":{"id":1,"name":"John Doe","email":"john@example.com","phone":"0100000000","address":null,"type":"customer","active":true,"balance":100.5,"salesCount":4,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/customers/1/payments' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"account_id\":1,\"amount\":50,\"note\":\"Partial settlement\"}'"],
                ],
            ],
            [
                'group' => 'Suppliers',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/suppliers', 'auth' => true, 'desc' => 'List suppliers (users where type=supplier).', 'params' => [
                        ['search','string','no',''], ['active','string','no','1, 0, or all (default all)'],
                        ['branch_id','integer','no','Filter to suppliers with purchases in this branch'],
                        ['balance_filter','string','no','with_balance or without_balance'],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":2,"name":"Acme Supplies","email":null,"phone":"0111111111","address":null,"type":"supplier","active":true,"balance":320,"salesCount":6,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/suppliers' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/suppliers/{id}', 'auth' => true, 'desc' => 'Show a single supplier. Pass ?include=purchases to embed the 10 most recent purchases.', 'params' => [
                        ['id','integer','yes','Supplier id (path)'],
                        ['include','string','no','Set to "purchases" to embed recentPurchases'],
                    ], 'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies","email":null,"phone":"0111111111","address":"Industrial Zone, Cairo","type":"supplier","active":true,"balance":320,"salesCount":6,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00","recentPurchases":[{"id":4,"invoiceNumber":"PUR-004","total":500,"paidAmount":500,"dueAmount":0,"date":"2026-08-27"}]}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/suppliers/2?include=purchases' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'POST', 'url' => '/api/v1/suppliers', 'auth' => true, 'desc' => 'Create a supplier.', 'params' => [
                        ['name','string','yes',''], ['email','string','no',''], ['phone','string','no',''], ['address','string','no',''], ['active','boolean','no','Default true'],
                    ], 'body' => '{"name":"Acme Supplies"}',
                       'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies","email":null,"phone":null,"address":null,"type":"supplier","active":true,"balance":0,"salesCount":0,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/suppliers' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"name\":\"Acme Supplies\"}'"],
                    ['method' => 'PUT', 'url' => '/api/v1/suppliers/{id}', 'auth' => true, 'desc' => 'Update a supplier.', 'params' => [['id','integer','yes','Supplier id (path)']],
                       'body' => '{"name":"Acme Supplies Ltd"}',
                       'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies Ltd","email":null,"phone":null,"address":null,"type":"supplier","active":true,"balance":0,"salesCount":0,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:05:00+00:00"}}',
                       'curl' => "curl -X PUT 'https://YOUR-TENANT.com/api/v1/suppliers/2' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"name\":\"Acme Supplies Ltd\"}'"],
                    ['method' => 'POST', 'url' => '/api/v1/suppliers/{id}/payments', 'auth' => true, 'desc' => 'Record a payment to the supplier, auto-applied FIFO against your oldest outstanding (non-deferred) purchases.', 'params' => [
                        ['id','integer','yes','Supplier id (path)'],
                        ['account_id','integer','yes','Account the payment is withdrawn from'],
                        ['amount','number','yes','Must not exceed total outstanding balance'],
                        ['note','string','no','Max 255 chars'],
                    ], 'body' => '{"account_id":1,"amount":100,"note":"Partial settlement"}',
                       'response' => '{"success":true,"data":{"id":2,"name":"Acme Supplies","email":null,"phone":null,"address":null,"type":"supplier","active":true,"balance":220,"salesCount":6,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/suppliers/2/payments' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"account_id\":1,\"amount\":100,\"note\":\"Partial settlement\"}'"],
                ],
            ],
            [
                'group' => 'Sales',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/sales', 'auth' => true, 'desc' => 'List sales (paginated).', 'params' => [
                        ['from_date','date','no','YYYY-MM-DD'], ['to_date','date','no','YYYY-MM-DD'],
                        ['status','string','no','paid or unpaid'], ['customer_id','integer','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":10,"invoiceNumber":"INV-000010","customer":{"id":1,"name":"John Doe"},"branchId":1,"date":"2026-08-28T00:00:00+00:00","subTotal":120,"discountAmount":0,"taxAmount":0,"total":120,"paidAmount":120,"dueAmount":0,"status":"paid","isDeferred":false,"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/sales?status=unpaid' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/sales/{id}', 'auth' => true, 'desc' => 'Show a sale with its items.', 'params' => [['id','integer','yes','Sale id (path)']],
                       'response' => '{"success":true,"data":{"id":10,"invoiceNumber":"INV-000010","customer":{"id":1,"name":"John Doe"},"branchId":1,"date":"2026-08-28T00:00:00+00:00","subTotal":120,"discountAmount":0,"taxAmount":0,"total":120,"paidAmount":120,"dueAmount":0,"status":"paid","isDeferred":false,"items":[{"id":1,"productId":5,"productName":"Coffee","unitId":1,"qty":2,"sellPrice":12.5,"taxable":false}],"createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/sales/10' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'POST', 'url' => '/api/v1/sales', 'auth' => true, 'desc' => 'Create a POS sale (reuses SellService — deducts stock, posts accounting entries).', 'params' => [
                        ['customer_id','integer','no','Nullable for walk-in'],
                        ['branch_id','integer','yes','Required — the branch the sale is recorded under is not auto-resolved from the token'],
                        ['invoice_number','string','no','Auto-generated if omitted'],
                        ['order_date','date','no','Defaults to now'],
                        ['tax_id','integer','no',''],
                        ['tax_percentage','number','no',''],
                        ['discount_id','integer','no',''],
                        ['discount_type','string','no','fixed or percentage'],
                        ['discount_value','number','no',''],
                        ['is_deferred','boolean','no','Defer inventory/accounting posting'],
                        ['due_date','date','no','Required if left unpaid and not deferred'],
                        ['payment_note','string','no',''],
                        ['payments','array','no','[{account_id, amount}]'],
                        ['products','array','yes','[{id, unit_id, quantity, sell_price, unit_cost, taxable}]'],
                    ], 'body' => '{"customer_id":1,"branch_id":1,"products":[{"id":5,"unit_id":1,"quantity":2,"sell_price":12.5}],"payments":[{"account_id":1,"amount":25}]}',
                       'response' => '{"success":true,"data":{"id":11,"invoiceNumber":"INV-000011","customer":{"id":1,"name":"John Doe"},"branchId":1,"date":"2026-08-28T10:10:00+00:00","subTotal":25,"discountAmount":0,"taxAmount":0,"total":25,"paidAmount":25,"dueAmount":0,"status":"paid","isDeferred":false,"items":[{"id":2,"productId":5,"productName":"Coffee","unitId":1,"qty":2,"sellPrice":12.5,"taxable":false}],"createdAt":"2026-08-28T10:10:00+00:00","updatedAt":"2026-08-28T10:10:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/sales' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"customer_id\":1,\"branch_id\":1,\"products\":[{\"id\":5,\"unit_id\":1,\"quantity\":2,\"sell_price\":12.5}],\"payments\":[{\"account_id\":1,\"amount\":25}]}'"],
                ],
            ],
            [
                'group' => 'Purchases',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/purchases', 'auth' => true, 'desc' => 'List purchases (paginated).', 'params' => [
                        ['from_date','date','no',''], ['to_date','date','no',''], ['status','string','no','pending, partial_paid, full_paid'],
                        ['supplier_id','integer','no',''], ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":4,"refNo":"PUR-004","supplier":{"id":2,"name":"Acme Supplies"},"branchId":1,"date":"2026-08-27T00:00:00+00:00","status":"full_paid","total":500,"paidAmount":500,"dueAmount":0,"isDeferred":false,"createdAt":"2026-08-27T10:00:00+00:00","updatedAt":"2026-08-27T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/purchases?status=pending' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/purchases/{id}', 'auth' => true, 'desc' => 'Show a purchase with its items.', 'params' => [['id','integer','yes','Purchase id (path)']],
                       'response' => '{"success":true,"data":{"id":4,"refNo":"PUR-004","supplier":{"id":2,"name":"Acme Supplies"},"branchId":1,"date":"2026-08-27T00:00:00+00:00","status":"full_paid","total":500,"paidAmount":500,"dueAmount":0,"isDeferred":false,"items":[{"id":1,"productId":5,"productName":"Coffee","unitId":1,"qty":10,"purchasePrice":8,"sellPrice":12.5}],"createdAt":"2026-08-27T10:00:00+00:00","updatedAt":"2026-08-27T10:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/purchases/4' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'POST', 'url' => '/api/v1/purchases', 'auth' => true, 'desc' => 'Create a purchase (reuses PurchaseService — updates stock and accounting entries).', 'params' => [
                        ['supplier_id','integer','yes',''],
                        ['branch_id','integer','yes',''],
                        ['ref_no','string','no',''],
                        ['order_date','date','no','Defaults to now'],
                        ['discount_type','string','no','fixed or percentage'],
                        ['discount_value','number','no',''],
                        ['tax_id','integer','no',''],
                        ['tax_rate','number','no',''],
                        ['payment_status','string','no','pending, partial_paid, full_paid'],
                        ['is_deferred','boolean','no','Defer inventory/accounting posting'],
                        ['orderProducts','array','yes','[{id, unit_id, qty, purchase_price, sell_price, discount_percentage, tax_percentage, x_margin}]'],
                    ], 'body' => '{"supplier_id":2,"branch_id":1,"orderProducts":[{"id":5,"unit_id":1,"qty":10,"purchase_price":8,"sell_price":12.5,"discount_percentage":0,"tax_percentage":0,"x_margin":0}]}',
                       'response' => '{"success":true,"data":{"id":5,"refNo":"PUR-005","supplier":{"id":2,"name":"Acme Supplies"},"branchId":1,"date":"2026-08-28T10:10:00+00:00","status":"pending","total":80,"paidAmount":0,"dueAmount":80,"isDeferred":false,"items":[{"id":2,"productId":5,"productName":"Coffee","unitId":1,"qty":10,"purchasePrice":8,"sellPrice":12.5}],"createdAt":"2026-08-28T10:10:00+00:00","updatedAt":"2026-08-28T10:10:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/purchases' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"supplier_id\":2,\"branch_id\":1,\"orderProducts\":[{\"id\":5,\"unit_id\":1,\"qty\":10,\"purchase_price\":8,\"sell_price\":12.5}]}'"],
                ],
            ],
            [
                'group' => 'Refunds',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/refunds', 'auth' => true, 'desc' => 'List refunds (paginated).', 'params' => [
                        ['order_type','string','no','sale or purchase'],
                        ['branch_id','integer','no',''],
                        ['from_date','date','no',''], ['to_date','date','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"branchId":1,"orderType":"Sale","orderId":10,"reason":"Damaged item","total":12.5,"items":[{"id":1,"productId":5,"unitId":1,"qty":1}],"createdAt":"2026-08-28T11:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/refunds?order_type=sale' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/refunds/{id}', 'auth' => true, 'desc' => 'Show a single refund with its items.', 'params' => [['id','integer','yes','Refund id (path)']],
                       'response' => '{"success":true,"data":{"id":1,"branchId":1,"orderType":"Sale","orderId":10,"reason":"Damaged item","total":12.5,"items":[{"id":1,"productId":5,"unitId":1,"qty":1}],"createdAt":"2026-08-28T11:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/refunds/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'POST', 'url' => '/api/v1/refunds', 'auth' => true, 'desc' => 'Create a refund against an existing sale or purchase order item. Restocks/reverses inventory and accounting via SellService/PurchaseService.', 'params' => [
                        ['branch_id','integer','yes',''],
                        ['order_type','string','yes','sale or purchase'],
                        ['order_id','integer','yes','Id of the sale or purchase being refunded'],
                        ['reason','string','no','Max 255 chars'],
                        ['items','array','yes','[{order_item_id, qty}] — order_item_id must belong to the given order'],
                    ], 'body' => '{"branch_id":1,"order_type":"sale","order_id":10,"reason":"Damaged item","items":[{"order_item_id":1,"qty":1}]}',
                       'response' => '{"success":true,"data":{"id":2,"branchId":1,"orderType":"Sale","orderId":10,"reason":"Damaged item","total":12.5,"items":[{"id":2,"productId":5,"unitId":1,"qty":1}],"createdAt":"2026-08-28T11:10:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/refunds' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"branch_id\":1,\"order_type\":\"sale\",\"order_id\":10,\"items\":[{\"order_item_id\":1,\"qty\":1}]}'"],
                ],
            ],
            [
                'group' => 'Stocks',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/stocks', 'auth' => true, 'desc' => 'List current stock levels (read-only, paginated).', 'params' => [
                        ['branch_id','integer','no','Filter by branch'], ['product_id','integer','no','Filter by product'], ['unit_id','integer','no','Filter by unit'],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"productId":5,"product":{"id":5,"name":"Coffee"},"branchId":1,"branch":{"id":1,"name":"Main Branch"},"unitId":1,"qty":38,"unitCost":8,"sellPrice":12.5}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/stocks?branch_id=1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/stocks/{id}', 'auth' => true, 'desc' => 'Show a single stock record.', 'params' => [['id','integer','yes','Stock record id (path)']],
                       'response' => '{"success":true,"data":{"id":1,"productId":5,"product":{"id":5,"name":"Coffee"},"branchId":1,"branch":{"id":1,"name":"Main Branch"},"unitId":1,"qty":38,"unitCost":8,"sellPrice":12.5}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/stocks/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Checks',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/checks', 'auth' => true, 'desc' => 'List checks (incoming from customers or outgoing to suppliers), paginated.', 'params' => [
                        ['direction','string','no','in or out'],
                        ['status','string','no','pending, cleared, bounced, etc.'],
                        ['branch_id','integer','no',''],
                        ['from_date','date','no','Filters check_date'], ['to_date','date','no','Filters check_date'],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"branchId":1,"direction":"in","status":"pending","amount":300,"checkNumber":"CHK-1001","bankName":"NBE","checkDate":"2026-09-01","dueDate":"2026-09-01","note":null,"customerId":1,"supplierId":null,"createdAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/checks?direction=in&status=pending' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/checks/{id}', 'auth' => true, 'desc' => 'Show a single check.', 'params' => [['id','integer','yes','Check id (path)']],
                       'response' => '{"success":true,"data":{"id":1,"branchId":1,"direction":"in","status":"pending","amount":300,"checkNumber":"CHK-1001","bankName":"NBE","checkDate":"2026-09-01","dueDate":"2026-09-01","note":null,"customerId":1,"supplierId":null,"createdAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/checks/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Purchase Requests',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/purchase-requests', 'auth' => true, 'desc' => 'List internal purchase requests (read-only, paginated).', 'params' => [
                        ['status','string','no',''], ['branch_id','integer','no',''], ['supplier_id','integer','no',''],
                        ['from_date','date','no',''], ['to_date','date','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"requestNumber":"PR-001","status":"pending","supplierId":2,"supplier":{"id":2,"name":"Acme Supplies"},"branchId":1,"requestDate":"2026-08-28","note":null,"createdAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/purchase-requests?status=pending' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/purchase-requests/{id}', 'auth' => true, 'desc' => 'Show a purchase request with its items.', 'params' => [['id','integer','yes','Purchase request id (path)']],
                       'response' => '{"success":true,"data":{"id":1,"requestNumber":"PR-001","status":"pending","supplierId":2,"supplier":{"id":2,"name":"Acme Supplies"},"branchId":1,"requestDate":"2026-08-28","note":null,"items":[{"id":1,"productId":5,"unitId":1,"qty":20}],"createdAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/purchase-requests/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Sale Requests',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/sale-requests', 'auth' => true, 'desc' => 'List customer sale requests / quotes (read-only, paginated).', 'params' => [
                        ['status','string','no',''], ['branch_id','integer','no',''], ['customer_id','integer','no',''],
                        ['from_date','date','no',''], ['to_date','date','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"quoteNumber":"SQ-001","status":"pending","customerId":1,"customer":{"id":1,"name":"John Doe"},"branchId":1,"requestDate":"2026-08-28","validUntil":"2026-09-04","note":null,"createdAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/sale-requests?status=pending' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/sale-requests/{id}', 'auth' => true, 'desc' => 'Show a sale request with its items.', 'params' => [['id','integer','yes','Sale request id (path)']],
                       'response' => '{"success":true,"data":{"id":1,"quoteNumber":"SQ-001","status":"pending","customerId":1,"customer":{"id":1,"name":"John Doe"},"branchId":1,"requestDate":"2026-08-28","validUntil":"2026-09-04","note":null,"items":[{"id":1,"productId":5,"unitId":1,"qty":2,"sellPrice":12.5}],"createdAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/sale-requests/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Expenses',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/expenses', 'auth' => true, 'desc' => 'List expenses (paginated).', 'params' => [
                        ['branch_id','integer','no',''], ['expense_category_id','integer','no',''],
                        ['from_date','date','no',''], ['to_date','date','no',''],
                        ['page','integer','no',''], ['per_page','integer','no','Default 20, max 100'],
                    ], 'response' => '{"success":true,"data":[{"id":1,"branchId":1,"category":{"id":1,"name":"Rent"},"amount":100,"taxPercentage":0,"total":100,"totalPaid":100,"expenseDate":"2026-08-28","note":null,"type":"expense","createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}],"meta":{"current_page":1,"last_page":1,"per_page":20,"total":1}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/expenses' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/expenses/{id}', 'auth' => true, 'desc' => 'Show a single expense.', 'params' => [['id','integer','yes','Expense id (path)']],
                       'response' => '{"success":true,"data":{"id":1,"branchId":1,"category":{"id":1,"name":"Rent"},"amount":100,"taxPercentage":0,"total":100,"totalPaid":100,"expenseDate":"2026-08-28","note":null,"type":"expense","createdAt":"2026-08-28T10:00:00+00:00","updatedAt":"2026-08-28T10:00:00+00:00"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/expenses/1' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'POST', 'url' => '/api/v1/expenses', 'auth' => true, 'desc' => 'Create an expense.', 'params' => [
                        ['expense_category_id','integer','yes',''], ['amount','number','yes',''], ['expense_date','date','yes',''],
                        ['branch_id','integer','no',''], ['tax_percentage','number','no',''], ['note','string','no',''],
                    ], 'body' => '{"expense_category_id":1,"amount":100,"expense_date":"2026-08-28"}',
                       'response' => '{"success":true,"data":{"id":2,"branchId":null,"category":{"id":1,"name":"Rent"},"amount":100,"taxPercentage":0,"total":100,"totalPaid":0,"expenseDate":"2026-08-28","note":null,"type":"expense","createdAt":"2026-08-28T10:10:00+00:00","updatedAt":"2026-08-28T10:10:00+00:00"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/expenses' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"expense_category_id\":1,\"amount\":100,\"expense_date\":\"2026-08-28\"}'"],
                    ['method' => 'PUT', 'url' => '/api/v1/expenses/{id}', 'auth' => true, 'desc' => 'Update an expense.', 'params' => [['id','integer','yes','Expense id (path)']],
                       'body' => '{"amount":150}',
                       'response' => '{"success":true,"data":{"id":2,"branchId":null,"category":{"id":1,"name":"Rent"},"amount":150,"taxPercentage":0,"total":150,"totalPaid":0,"expenseDate":"2026-08-28","note":null,"type":"expense","createdAt":"2026-08-28T10:10:00+00:00","updatedAt":"2026-08-28T10:15:00+00:00"}}',
                       'curl' => "curl -X PUT 'https://YOUR-TENANT.com/api/v1/expenses/2' \\\n  -H 'X-API-Token: YOUR_TOKEN' \\\n  -H 'Content-Type: application/json' \\\n  -d '{\"amount\":150}'"],
                    ['method' => 'DELETE', 'url' => '/api/v1/expenses/{id}', 'auth' => true, 'desc' => 'Soft-delete an expense.', 'params' => [['id','integer','yes','Expense id (path)']],
                       'response' => '{"success":true,"data":null}',
                       'curl' => "curl -X DELETE 'https://YOUR-TENANT.com/api/v1/expenses/2' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Statistics',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/statistics/summary', 'auth' => true, 'desc' => "Today's totals for sales, purchases and expenses. Always scoped to the current day — from_date/to_date are not accepted here, use /statistics/daily for a range.", 'params' => [],
                       'response' => '{"success":true,"data":{"date":"2026-08-28","salesTotal":250,"salesCount":3,"purchasesTotal":80,"purchasesCount":1,"expensesTotal":100}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/statistics/summary' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'GET', 'url' => '/api/v1/statistics/daily', 'auth' => true, 'desc' => 'Daily breakdown for the last N days.', 'params' => [['days','integer','no','Default 30, max 365']],
                       'response' => '{"success":true,"data":[{"date":"2026-08-27","salesTotal":100,"purchasesTotal":0,"expensesTotal":0},{"date":"2026-08-28","salesTotal":250,"purchasesTotal":80,"expensesTotal":100}]}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/statistics/daily?days=7' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Settings',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/v1/settings', 'auth' => true, 'desc' => 'Return all tenant settings as key/value pairs.', 'params' => [],
                       'response' => '{"success":true,"data":{"business_name":"My Store","currency_id":"1","timezone":"Africa/Cairo"}}',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/v1/settings' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                    ['method' => 'POST', 'url' => '/api/v1/settings/regenerate-token', 'auth' => true, 'desc' => "Regenerate the authenticated admin's API token. The new token is returned once — store it immediately, the old token stops working right away.", 'params' => [],
                       'response' => '{"success":true,"data":{"token":"5f4dcc3b5aa765d61d8327deb882cf99a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6"}}',
                       'curl' => "curl -X POST 'https://YOUR-TENANT.com/api/v1/settings/regenerate-token' \\\n  -H 'X-API-Token: YOUR_TOKEN'"],
                ],
            ],
            [
                'group' => 'Documentation',
                'items' => [
                    ['method' => 'GET', 'url' => '/api/docs', 'auth' => false, 'desc' => 'This documentation page (no token required).', 'params' => [],
                       'response' => 'text/html',
                       'curl' => "curl -X GET 'https://YOUR-TENANT.com/api/docs'"],
                ],
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-8">
        <nav class="lg:sticky lg:top-6 lg:self-start bg-white border border-slate-200 rounded-xl p-4 h-fit">
            <input
                id="endpoint-search"
                type="text"
                placeholder="Search endpoints..."
                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                oninput="filterEndpoints(this.value)"
            >
            <ul class="space-y-1 text-sm">
                @foreach($endpoints as $group)
                    <li>
                        <a href="#{{ strtolower(str_replace(' ', '-', $group['group'])) }}" class="block px-2 py-1.5 rounded hover:bg-slate-100 text-slate-700 font-medium">
                            {{ $group['group'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="space-y-10 min-w-0">
            @foreach($endpoints as $group)
                <section id="{{ strtolower(str_replace(' ', '-', $group['group'])) }}">
                    <h2 class="text-xl font-bold mb-1 border-b border-slate-200 pb-2">{{ $group['group'] }}</h2>
                    <div class="space-y-6 mt-4">
                        @foreach($group['items'] as $item)
                            <div class="endpoint-card bg-white border border-slate-200 rounded-xl p-5"
                                 data-search="{{ strtolower($item['method'].' '.$item['url'].' '.$item['desc']) }}">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <span class="text-xs font-bold px-2 py-1 rounded {{ $badge($item['method']) }}">{{ $item['method'] }}</span>
                                    <code class="text-sm font-semibold">{{ $item['url'] }}</code>
                                    @if($item['auth'])
                                        <span class="text-xs text-slate-500">Auth: <code class="bg-slate-100 px-1.5 py-0.5 rounded">X-API-Token</code> header or <code class="bg-slate-100 px-1.5 py-0.5 rounded">?api_token=</code></span>
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
                                <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-3 mb-3">{{ $item['response'] }}</pre>

                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-semibold text-slate-500">curl</p>
                                    <button
                                        type="button"
                                        onclick="copyCurl(this)"
                                        class="text-xs px-2 py-0.5 rounded bg-slate-200 hover:bg-slate-300 text-slate-700"
                                    >Copy</button>
                                </div>
                                <pre class="bg-slate-900 text-slate-100 text-xs rounded-lg p-3">{{ $item['curl'] }}</pre>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</div>

<script>
function filterEndpoints(query) {
    var q = query.trim().toLowerCase();
    document.querySelectorAll('.endpoint-card').forEach(function (card) {
        var haystack = card.getAttribute('data-search') || '';
        card.hidden = q.length > 0 && haystack.indexOf(q) === -1;
    });
}

function copyCurl(button) {
    var pre = button.closest('div').nextElementSibling;
    if (!pre) return;
    var text = pre.textContent;
    navigator.clipboard.writeText(text).then(function () {
        var original = button.textContent;
        button.textContent = 'Copied!';
        setTimeout(function () { button.textContent = original; }, 1500);
    });
}
</script>

</body>
</html>
