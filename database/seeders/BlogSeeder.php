<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'slug' => 'getting-started-smart-erp-pos',
                'title_en' => 'Getting Started with Smart ERP + POS',
                'title_ar' => 'ابدأ مع نظام ERP + POS الذكي',
                'excerpt_en' => 'A quick guide to set up branches, users, items, and start selling in minutes.',
                'excerpt_ar' => 'دليل سريع لإعداد الفروع والمستخدمين والأصناف والبدء بالبيع خلال دقائق.',
                'content_en' => "Smart ERP/POS helps you run sales, inventory, and accounting from one place.\n\nStart by creating your branches and warehouses, then add user roles and permissions. Next, define your products, barcode rules, and taxes. Finally, open a POS session and begin issuing invoices.\n\nTip: keep item units and costing methods consistent to get accurate profit reports.",
                'content_ar' => "يساعدك نظام ERP/POS الذكي على إدارة المبيعات والمخزون والحسابات من مكان واحد.\n\nابدأ بإنشاء الفروع والمستودعات، ثم أضف صلاحيات المستخدمين والأدوار. بعد ذلك عرّف الأصناف والباركود والضرائب. أخيراً افتح جلسة نقطة البيع وابدأ بإصدار الفواتير.\n\nنصيحة: حافظ على توحيد وحدات الأصناف وطريقة التكلفة للحصول على تقارير أرباح دقيقة.",
                'image' => 'hud/assets/img/landing/blog-1.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'slug' => 'inventory-control-best-practices',
                'title_en' => 'Inventory Control Best Practices for Multi-Branch Stores',
                'title_ar' => 'أفضل ممارسات إدارة المخزون للفروع المتعددة',
                'excerpt_en' => 'Avoid stockouts and overstock with transfers, minimum levels, and cycle counts.',
                'excerpt_ar' => 'تجنب نفاد المخزون والزيادة عبر التحويلات والحد الأدنى والجرد الدوري.',
                'content_en' => "For multi-branch operations, inventory accuracy is everything.\n\n1) Set minimum stock levels per branch.\n2) Use stock transfers instead of manual adjustments.\n3) Schedule cycle counts weekly for fast-moving items.\n4) Review dead stock monthly and create clear discount/clearance rules.\n\nWith these steps, your purchasing and sales decisions become data-driven.",
                'content_ar' => "في عمليات الفروع المتعددة، دقة المخزون هي الأساس.\n\n1) حدّد حدًا أدنى للمخزون لكل فرع.\n2) استخدم تحويلات المخزون بدل التعديلات اليدوية.\n3) نفّذ جردًا دوريًا أسبوعيًا للأصناف سريعة الحركة.\n4) راجع البضاعة الراكدة شهريًا وضع سياسة واضحة للتصفيات والخصومات.\n\nبهذه الخطوات تصبح قرارات الشراء والبيع مبنية على بيانات.",
                'image' => 'hud/assets/img/landing/blog-2.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'slug' => 'cashier-shift-management',
                'title_en' => 'Cashier Shift Management: Reduce Mistakes and Speed Up Checkout',
                'title_ar' => 'إدارة ورديات الكاشير: تقليل الأخطاء وتسريع عملية البيع',
                'excerpt_en' => 'Open/close sessions correctly and reconcile cash to keep your POS clean.',
                'excerpt_ar' => 'افتح/أغلق الجلسات بشكل صحيح وطابق النقد لضبط نقطة البيع.',
                'content_en' => "Shift discipline improves accuracy and accountability.\n\n- Always open a POS session with a starting cash balance.\n- Use role permissions to limit refunds and price overrides.\n- Close the session at the end of the shift and reconcile cash, card, and transfers.\n\nWhen every shift is reconciled, your daily sales reports become trustworthy.",
                'content_ar' => "الالتزام بنظام الوردية يرفع الدقة والمحاسبة.\n\n- افتح جلسة نقطة البيع برصيد افتتاحي للنقد.\n- استخدم الصلاحيات للحد من الاسترجاعات وتعديل الأسعار.\n- أغلق الجلسة نهاية الوردية وطابق النقد والبطاقات والتحويلات.\n\nعند مطابقة كل وردية تصبح تقارير المبيعات اليومية موثوقة.",
                'image' => 'hud/assets/img/landing/blog-3.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(9),
            ],
            [
                'slug' => 'accounting-reports-for-owners',
                'title_en' => 'Accounting Reports Every Business Owner Should Review Weekly',
                'title_ar' => 'تقارير محاسبية يجب مراجعتها أسبوعيًا',
                'excerpt_en' => 'Track profit, cash flow, receivables, and tax summaries to stay in control.',
                'excerpt_ar' => 'تابع الأرباح والتدفقات والذمم والضرائب للبقاء في السيطرة.',
                'content_en' => "You don’t need to be an accountant to stay on top of your numbers. Review these weekly:\n\n- Sales summary (by branch and payment method)\n- Gross profit report (by category/item)\n- Cash flow snapshot\n- Accounts receivable and payable\n- Tax/VAT summary\n\nIf something changes suddenly, investigate early—before it becomes a loss.",
                'content_ar' => "لا تحتاج لأن تكون محاسبًا لتتابع أرقامك. راجع أسبوعيًا:\n\n- ملخص المبيعات (حسب الفرع وطريقة الدفع)\n- تقرير مجمل الربح (حسب التصنيف/الصنف)\n- نظرة سريعة على التدفقات النقدية\n- الذمم المدينة والدائنة\n- ملخص الضرائب/القيمة المضافة\n\nإذا حدث تغير مفاجئ، راجع السبب مبكرًا قبل أن يتحول لخسارة.",
                'image' => 'hud/assets/img/landing/blog-4.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'slug' => 'refunds-and-returns-policy',
                'title_en' => 'Refunds & Returns: A Simple Policy That Protects Profit',
                'title_ar' => 'الاسترجاع والاستبدال: سياسة بسيطة تحمي الأرباح',
                'excerpt_en' => 'Define return windows, approval rules, and audit logs to prevent abuse.',
                'excerpt_ar' => 'حدد مدة الاسترجاع وقواعد الموافقة وسجل التدقيق لمنع إساءة الاستخدام.',
                'content_en' => "Returns are normal—losses are not.\n\nCreate a clear policy:\n- Return window (e.g., 7 or 14 days)\n- Condition rules (sealed/opened/damaged)\n- Approval levels (cashier vs manager)\n- Mandatory reason codes\n- Audit logs for every refund\n\nThis keeps customer service strong while protecting your margins.",
                'content_ar' => "الاسترجاع طبيعي—أما الخسائر فليست كذلك.\n\nضع سياسة واضحة:\n- مدة الاسترجاع (مثل 7 أو 14 يومًا)\n- حالة المنتج (مغلق/مفتوح/تالف)\n- مستويات الموافقة (كاشير/مدير)\n- أسباب إلزامية للاسترجاع\n- سجل تدقيق لكل عملية استرجاع\n\nبهذا تحافظ على خدمة العملاء وتضمن حماية هامش الربح.",
                'image' => null,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'slug' => 'why-centralized-erp-matters',
                'title_en' => 'Why Centralized ERP Matters for Growth',
                'title_ar' => 'لماذا يساعدك ERP المركزي على النمو',
                'excerpt_en' => 'One dashboard, one source of truth: faster decisions and fewer surprises.',
                'excerpt_ar' => 'لوحة واحدة ومصدر واحد للحقيقة: قرارات أسرع ومفاجآت أقل.',
                'content_en' => "When sales, inventory, and accounting live in separate tools, reporting becomes slow and inconsistent.\n\nA centralized ERP gives you:\n- Unified reports across branches\n- Real-time stock and costing\n- Better controls through permissions\n- A clean audit trail\n\nThe result is clarity: you can scale operations without losing control.",
                'content_ar' => "عندما تكون المبيعات والمخزون والحسابات في أدوات منفصلة تصبح التقارير أبطأ وأقل دقة.\n\nنظام ERP المركزي يمنحك:\n- تقارير موحدة عبر الفروع\n- مخزون وتكلفة لحظية\n- تحكم أفضل عبر الصلاحيات\n- سجل تدقيق واضح\n\nالنتيجة هي وضوح يسمح لك بالتوسع بدون فقدان السيطرة.",
                'image' => null,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($blogs as $data) {
            Blog::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
