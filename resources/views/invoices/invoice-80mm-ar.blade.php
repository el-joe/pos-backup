<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/invoice-80mm-ar.css') }}">
</head>
<body>

<div class="receipt">

    <!-- بيانات الشركة -->
    <div class="center">
        <h2 class="company-name">{{ tenant('name') }} POS</h2>
        <!-- <p>حلول برمجية وتقنية</p> -->
        @if(tenant('tax_number'))
        <p>الرقم الضريبي: {{ tenant('tax_number') }}</p>
        @endif
        <p>هاتف: {{ tenant('phone') }}</p>
    </div>

    <hr>

    <!-- بيانات الفاتورة -->
    <div class="row">
        <span>رقم الفاتورة</span>
        <span>{{ $order->invoice_number }}</span>
    </div>
    <div class="row">
        <span>التاريخ</span>
        <span>{{ carbon($order->created_at)->translatedFormat('Y-m-d H:i') }}</span>
    </div>

    <hr>

    <!-- بيانات العميل -->
    <div class="section-title">بيانات العميل</div>
    <p>الاسم: {{ $order->customer?->name }}</p>
    <p>الهاتف: {{ $order->customer?->phone }}</p>

    <!-- بيانات المورد (اختياري) -->
    <!--
    <div class="section-title">بيانات المورد</div>
    <p>الاسم: شركة ABC</p>
    <p>الرقم الضريبي: 987654321</p>
    -->

    <hr>

    <!-- جدول الأصناف -->
    <table class="items">
        <thead>
            <tr>
                <th>الصنف</th>
                <th class="right">الكمية</th>
                <th class="right">السعر</th>
                <th class="right">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->saleItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ currencyFormat($item->sell_price, true) }}</td>
                    <td class="right">{{ currencyFormat($item->total, true) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- الإجماليات -->
    <div class="row">
        <span>الإجمالي الفرعي</span>
        <span>{{ currencyFormat($order->sub_total, true) }}</span>
    </div>
    <div class="row">
        <span>الخصم</span>
        <span>-{{ currencyFormat($order->discount_amount ?? 0, true) }}</span>
    </div>
    <div class="row">
        <span>ضريبة القيمة المضافة</span>
        <span>{{ currencyFormat($order->tax_amount ?? 0, true) }}</span>
    </div>

    <div class="row total">
        <span>الإجمالي النهائي</span>
        <span>{{ currencyFormat($order->grand_total_amount, true) }}</span>
    </div>

    <hr>

    <!-- بيانات الدفع -->
    <div class="section-title">بيانات الدفع</div>
    <div class="row">
        <span>طريقة الدفع</span>
        <span>نقدي</span>
    </div>
    <div class="row">
        <span>المدفوع</span>
        <span>{{ currencyFormat($order->paid_amount, true) }}</span>
    </div>
    <!-- <div class="row">
        <span>الباقي</span>
        <span>1.60</span>
    </div> -->

    <hr>

    <!-- تذييل -->
    <div class="center footer">
        <p>شكرًا لتعاملكم معنا</p>
        <p>مشغل بواسطة {{ tenant('name') }} POS</p>
    </div>

</div>

</body>
</html>
