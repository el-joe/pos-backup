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
        <h2 class="company-name">كودفانز لنظم البيع</h2>
        <p>حلول برمجية وتقنية</p>
        <p>الرقم الضريبي: 123456789</p>
        <p>هاتف: 01000000000</p>
    </div>

    <hr>

    <!-- بيانات الفاتورة -->
    <div class="row">
        <span>رقم الفاتورة</span>
        <span>INV-00025</span>
    </div>
    <div class="row">
        <span>التاريخ</span>
        <span>2025-12-14 14:35</span>
    </div>

    <hr>

    <!-- بيانات العميل -->
    <div class="section-title">بيانات العميل</div>
    <p>الاسم: أحمد حسن</p>
    <p>الهاتف: 01123456789</p>
    <p>الحساب: حساب عميل</p>

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
            <tr>
                <td>أرز 1 كجم</td>
                <td class="right">2</td>
                <td class="right">25.00</td>
                <td class="right">50.00</td>
            </tr>
            <tr>
                <td>سكر 1 كجم</td>
                <td class="right">1</td>
                <td class="right">18.00</td>
                <td class="right">18.00</td>
            </tr>
        </tbody>
    </table>

    <hr>

    <!-- الإجماليات -->
    <div class="row">
        <span>الإجمالي الفرعي</span>
        <span>68.00</span>
    </div>
    <div class="row">
        <span>الخصم</span>
        <span>-8.00</span>
    </div>
    <div class="row">
        <span>ضريبة القيمة المضافة (14%)</span>
        <span>8.40</span>
    </div>

    <div class="row total">
        <span>الإجمالي النهائي</span>
        <span>68.40</span>
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
        <span>70.00</span>
    </div>
    <div class="row">
        <span>الباقي</span>
        <span>1.60</span>
    </div>

    <hr>

    <!-- تذييل -->
    <div class="center footer">
        <p>شكرًا لتعاملكم معنا</p>
        <p>مشغل بواسطة Codefanz POS</p>
    </div>

</div>

</body>
</html>
