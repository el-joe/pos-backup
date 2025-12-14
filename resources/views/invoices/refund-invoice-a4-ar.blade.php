<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة مرتجع / استرجاع</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/refund-invoice-a4-ar.css') }}">
</head>
<body>
<div class="invoice-container">

    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1>شركة كودفانز للبرمجيات</h1>
            <p>حلول تقنية وأنظمة نقاط البيع</p>
            <p>العنوان: القاهرة – مصر</p>
            <p>هاتف: 01000000000</p>
            <p>الرقم الضريبي: 123456789</p>
        </div>

        <div class="invoice-meta">
            <h2>فاتورة مرتجع / استرجاع</h2>
            <table>
                <tr>
                    <td>رقم المستند</td>
                    <td>CR-2025-005</td>
                </tr>
                <tr>
                    <td>تاريخ المستند</td>
                    <td>2025-12-14</td>
                </tr>
                <tr>
                    <td>رقم الفاتورة الأصلية</td>
                    <td>INV-2025-025</td>
                </tr>
                <tr>
                    <td>طريقة الدفع</td>
                    <td>نقدي</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="customer-section">
        <h4>بيانات العميل</h4>
        <div class="customer-grid">
            <p><strong>الاسم:</strong> أحمد حسن</p>
            <p><strong>الهاتف:</strong> 01123456789</p>
            <p><strong>العنوان:</strong> الجيزة</p>
        </div>
    </div>

    <!-- Returned Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الصنف</th>
                <th>الوحدة</th>
                <th>الكمية المرتجعة</th>
                <th>سعر الوحدة</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>أرز أبيض</td>
                <td>كجم</td>
                <td>1</td>
                <td>25.00</td>
                <td>25.00</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table>
            <tr>
                <td>الإجمالي الفرعي</td>
                <td>25.00</td>
            </tr>
            <tr>
                <td>خصم</td>
                <td>0.00</td>
            </tr>
            <tr>
                <td>ضريبة القيمة المضافة (14%)</td>
                <td>3.50</td>
            </tr>
            <tr class="grand-total">
                <td>الإجمالي المسترجع</td>
                <td>28.50</td>
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
        <p>هذا المستند يمثل فاتورة مرتجع رسمية لأغراض المحاسبة والضريبة</p>
    </div>

</div>
</body>
</html>
