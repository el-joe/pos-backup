<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/invoice-a4-ar.css') }}">
</head>
<body>

<div class="invoice-container">

    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1>{{ tenant('name') }}</h1>
            <!-- <p>حلول تقنية وأنظمة نقاط البيع</p> -->
            <p>العنوان: {{ tenant('address') ?? 'القاهرة – مصر' }}</p>
            <p>هاتف: {{ tenant('phone') }}</p>
            @if(tenant('tax_number'))
            <p>الرقم الضريبي: {{ tenant('tax_number') }}</p>
            @endif
        </div>

        <div class="invoice-meta">
            <h2>فاتورة بيع</h2>
            <table>
                <tr>
                    <td>رقم الفاتورة</td>
                    <td>{{ $order->invoice_number }}</td>
                </tr>
                <tr>
                    <td>تاريخ الفاتورة</td>
                    <td>{{ carbon($order->created_at)->translatedFormat('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td>طريقة الدفع</td>
                    <td>نقدي</td>
                </tr>
                <tr>
                    <td>حالة الدفع</td>
                    <td>{{ $order->payment_status }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="customer-section">
        <h4>بيانات العميل</h4>
        <div class="customer-grid">
            <p><strong>الاسم:</strong> {{ $order->customer?->name }}</p>
            <p><strong>الهاتف:</strong> {{ $order->customer?->phone }}</p>
            <p><strong>العنوان:</strong> {{ $order->customer?->address ?? '—' }}</p>
            <!--<p><strong>الرقم الضريبي:</strong> {{ $order->customer?->tax_number ?? '—' }}</p>-->
        </div>
    </div>

    <!-- Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الصنف</th>
                <th>الوحدة</th>
                <th>الكمية</th>
                <th>سعر الوحدة</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->saleItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product?->name }}</td>
                    <td>{{ $item->unit?->name ?? 'قطعة' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ currency()?->symbol }}{{ $item->sell_price }}</td>
                    <td>{{ currency()?->symbol }}{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table>
            <tr>
                <td>الإجمالي الفرعي</td>
                <td>{{ currency()->symbol }}{{ $order->sub_total }}</td>
            </tr>
            <tr>
                <td>خصم</td>
                <td>{{ currency()->symbol }}{{ ($order->discount_amount ?? 0 ) * -1}}</td>
            </tr>
            <tr>
                <td>ضريبة القيمة المضافة</td>
                <td>{{ currency()->symbol }}{{ $order->tax_amount ?? 0 }}</td>
            </tr>
            <tr class="grand-total">
                <td>الإجمالي النهائي</td>
                <td>{{ currency()->symbol }}{{ $order->grand_total_amount }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="invoice-footer">
        <div class="sign">
            <p>توقيع العميل</p>
        </div>
        <div class="sign">
            <p>توقيع البائع</p>
        </div>
    </div>

    <div class="note">
        <p>هذه فاتورة بيع صالحة للأغراض المحاسبية والضريبية</p>
    </div>

</div>

</body>
</html>
