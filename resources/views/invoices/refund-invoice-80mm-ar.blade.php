<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>فاتورة مرتجع</title>
<style>
body { font-family: "Cairo", Tahoma, sans-serif; font-size: 12px; margin:0; padding:0; }
.receipt-80mm { width: 80mm; padding: 5px; background: #fff; color: #000; }
.company-name { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
.receipt-title { text-align: center; font-weight: bold; font-size: 13px; margin-bottom: 5px; }
hr { border: 0; border-top: 1px dashed #000; margin: 5px 0; }
.meta p, .customer p { margin: 2px 0; font-size: 11px; }
table { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 5px; }
table th, table td { border: 1px solid #000; text-align: center; padding: 2px; }
</style>
</head>
<body>
<div class="receipt-80mm">
    <div class="company-name">كودفانز للبرمجيات</div>
    <div class="receipt-title">مرتجع / استرجاع</div>

    <!-- Document Meta -->
    <div class="meta">
        <p>رقم المستند: CR-2025-005</p>
        <p>التاريخ: 2025-12-14</p>
        <p>فاتورة أصلية: INV-2025-025</p>
        <p>طريقة الدفع: نقدي</p>
    </div>
    <hr>

    <!-- Customer Info -->
    <div class="customer">
        <p>العميل: أحمد حسن</p>
        <p>الهاتف: 01123456789</p>
    </div>
    <hr>

    <!-- Returned Items -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الصنف</th>
                <th>كمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>أرز أبيض</td>
                <td>1</td>
                <td>25.00</td>
                <td>25.00</td>
            </tr>
        </tbody>
    </table>
    <hr>

    <!-- Total -->
    <p>الإجمالي المسترجع: 28.50</p>
    <p style="text-align:center;">شكراً لتعاملكم</p>
</div>
</body>
</html>
