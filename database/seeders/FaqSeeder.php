<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question_en' => 'What is Mohaaseb, and who is it built for?',
                'answer_en' => '<p><strong>Mohaaseb</strong> is a cloud-based <strong>erp</strong> platform with <strong>pos</strong> capabilities designed for day-to-day operations and financial control. It helps teams manage <strong>inventory</strong>, sales, purchasing, <strong>accounting</strong>, and <strong>reports</strong> in one workflow.</p>',
                'question_ar' => 'ما هو Mohaaseb ولمن تم تصميمه؟',
                'answer_ar' => '<p>Mohaaseb منصة ERP سحابية مع نظام نقاط بيع تساعدك على إدارة العمليات والمحاسبة والتقارير بشكل مترابط.</p>',
            ],
            [
                'question_en' => 'Does Mohaaseb support both ERP and POS workflows?',
                'answer_en' => '<p>Yes. Mohaaseb combines <strong>erp</strong> back-office control with <strong>pos</strong> front-office speed. The key benefit is that operational sales become financial <strong>transactions</strong> that can be reviewed in <strong>reports</strong> without manual re-entry.</p>',
                'question_ar' => 'هل يدعم Mohaaseb سير عمل ERP وPOS؟',
                'answer_ar' => '<p>نعم، يجمع Mohaaseb بين إدارة ERP وسرعة نقاط البيع POS مع تقارير دقيقة.</p>',
            ],
            [
                'question_en' => 'How should we structure our accounting tree in Mohaaseb?',
                'answer_en' => '<p>Start with a simple <strong>accounting tree</strong> (chart of accounts) that matches how you operate and how you want to analyze performance. In Mohaaseb, keep the tree readable, then add detail when it improves <strong>reports</strong> (e.g., expenses by department, revenue by channel).</p>',
                'question_ar' => 'كيف أبني شجرة الحسابات داخل Mohaaseb؟',
                'answer_ar' => '<p>ابدأ بشجرة حسابات بسيطة وواضحة ثم أضف التفاصيل تدريجيًا بما يخدم التقارير.</p>',
            ],
            [
                'question_en' => 'What is the difference between accounting and daily transactions?',
                'answer_en' => '<p><strong>Accounting</strong> is the system of rules and period closing routines. Daily events (sales, purchases, payments) become <strong>transactions</strong> that must post to the correct accounts in your <strong>accounting tree</strong>. Mohaaseb helps standardize this posting so your numbers stay consistent.</p>',
                'question_ar' => 'ما الفرق بين المحاسبة والعمليات اليومية؟',
                'answer_ar' => '<p>المحاسبة هي قواعد وسياسات، والعمليات اليومية هي حركات يتم تسجيلها وتظهر في التقارير.</p>',
            ],
            [
                'question_en' => 'How does Mohaaseb handle inventory and stock control?',
                'answer_en' => '<p>Mohaaseb provides <strong>inventory</strong> management with accurate <strong>stock</strong> tracking. Every sale and purchase affects stock levels, and you can audit the movement using <strong>reports</strong> to detect shortages, slow movers, and valuation issues.</p>',
                'question_ar' => 'كيف يدير Mohaaseb المخزون؟',
                'answer_ar' => '<p>يوفر النظام إدارة مخزون وتتبع كميات المخزون مع تقارير حركة وتقييم.</p>',
            ],
            [
                'question_en' => 'What is a stock transfer, and when should we use it?',
                'answer_en' => '<p>A <strong>stock transfer</strong> moves <strong>stock</strong> between locations (stores, warehouses, branches). In Mohaaseb, stock transfer transactions keep quantities and <strong>inventory</strong> valuation aligned across branches and show up clearly in movement <strong>reports</strong>.</p>',
                'question_ar' => 'ما هو نقل المخزون ومتى أستخدمه؟',
                'answer_ar' => '<p>نقل المخزون يُستخدم لنقل الكميات بين الفروع/المستودعات مع حفظ أثر الحركة في التقارير.</p>',
            ],
            [
                'question_en' => 'What is a stock adjustment, and why is it important?',
                'answer_en' => '<p>A <strong>stock adjustment</strong> corrects quantities due to count differences, damage, or shrinkage. When you post a stock adjustment in Mohaaseb, you keep <strong>inventory</strong> and <strong>accounting</strong> aligned and improve audit quality in your <strong>reports</strong>.</p>',
                'question_ar' => 'ما هو تعديل المخزون ولماذا هو مهم؟',
                'answer_ar' => '<p>تعديل المخزون لتسجيل فروقات الجرد والهالك ويظهر أثره في التقارير.</p>',
            ],
            [
                'question_en' => 'How can Mohaaseb reduce errors in accounting and reporting?',
                'answer_en' => '<p>Mohaaseb reduces errors by enforcing consistent transaction types, mapping them to the right accounts, and encouraging document-based posting. This improves <strong>accounting</strong> discipline and makes <strong>reports</strong> trustworthy.</p>',
                'question_ar' => 'كيف يقلل Mohaaseb الأخطاء في التقارير؟',
                'answer_ar' => '<p>بتوحيد أنواع الحركات وربطها بالحسابات مع اعتماد المستندات.</p>',
            ],
            [
                'question_en' => 'What reports should we review weekly in Mohaaseb?',
                'answer_en' => '<p>For most teams, start with cash/bank summary, <strong>inventory</strong> movement, top expenses, and sales trends. Regular review of these <strong>reports</strong> helps you catch posting mistakes early and keep <strong>accounting</strong> clean.</p>',
                'question_ar' => 'ما التقارير الأسبوعية الموصى بها؟',
                'answer_ar' => '<p>تقارير النقدية والبنك وحركة المخزون وأهم المصروفات والمبيعات.</p>',
            ],
            [
                'question_en' => 'How are refunds and returns different in practice?',
                'answer_en' => '<p><strong>Returns</strong> typically reverse a sale and may bring items back into <strong>stock</strong> (affecting <strong>inventory</strong>). <strong>Refunds</strong> are the money side (cash/bank outflow). In Mohaaseb, linking returns and refunds to the original invoice improves traceability in <strong>reports</strong>.</p>',
                'question_ar' => 'ما الفرق بين المرتجع والاسترداد؟',
                'answer_ar' => '<p>المرتجع يعيد العملية وربما الكمية للمخزون، والاسترداد هو دفع المبلغ للعميل.</p>',
            ],
            [
                'question_en' => 'Does Mohaaseb support partial returns and partial refunds?',
                'answer_en' => '<p>Yes. Partial <strong>returns</strong> and partial <strong>refunds</strong> are common in retail. The key is to record them as separate, well-documented transactions so <strong>reports</strong> show accurate net sales and accurate <strong>inventory</strong>/<strong>stock</strong> movement.</p>',
                'question_ar' => 'هل يمكن عمل مرتجع/استرداد جزئي؟',
                'answer_ar' => '<p>نعم، ويمكن تسجيله كحركة منفصلة مع توثيق واضح لضمان دقة التقارير.</p>',
            ],
            [
                'question_en' => 'What is the best way to set up inventory categories and items?',
                'answer_en' => '<p>Keep your <strong>inventory</strong> structure simple: clear item names, consistent units, and meaningful categories. In Mohaaseb, clean item setup makes <strong>reports</strong> and <strong>stock</strong> analysis easier.</p>',
                'question_ar' => 'ما أفضل طريقة لتجهيز الأصناف؟',
                'answer_ar' => '<p>أسماء واضحة ووحدات ثابتة وتصنيفات منطقية لتسهيل التقارير.</p>',
            ],
            [
                'question_en' => 'How should a business handle stock transfer approvals?',
                'answer_en' => '<p>Use a simple approval flow: create the <strong>stock transfer</strong>, confirm dispatch, then confirm receipt. This avoids negative <strong>stock</strong> and makes <strong>inventory</strong> movement <strong>reports</strong> consistent.</p>',
                'question_ar' => 'كيف أعتمد نقل المخزون؟',
                'answer_ar' => '<p>اعتمد خطوات واضحة: إنشاء النقل ثم تأكيد الإرسال ثم تأكيد الاستلام.</p>',
            ],
            [
                'question_en' => 'When should we use stock adjustment instead of editing quantities directly?',
                'answer_en' => '<p>Always use a <strong>stock adjustment</strong> for corrections. Editing quantities directly breaks the audit trail. A proper stock adjustment keeps <strong>inventory</strong> history intact and supports reliable <strong>reports</strong> and better <strong>accounting</strong> control.</p>',
                'question_ar' => 'متى أستخدم تعديل المخزون بدل تعديل الكميات؟',
                'answer_ar' => '<p>استخدم تعديل المخزون للحفاظ على مسار تدقيق واضح وعدم كسر التقارير.</p>',
            ],
            [
                'question_en' => 'How does Mohaaseb help with month-end closing?',
                'answer_en' => '<p>Mohaaseb supports disciplined closing by encouraging reconciliations, review of pending <strong>returns</strong>/<strong>refunds</strong>, and final checks on <strong>inventory</strong> valuation. A consistent close process makes your <strong>reports</strong> stable month after month.</p>',
                'question_ar' => 'كيف يساعد Mohaaseb في الإغلاق الشهري؟',
                'answer_ar' => '<p>بالمطابقات الدورية ومراجعة المرتجعات وفروقات المخزون قبل اعتماد التقارير.</p>',
            ],
            [
                'question_en' => 'What is an accounting tree and why does it affect reports?',
                'answer_en' => '<p>Your <strong>accounting tree</strong> is the structure that defines where every transaction goes. If it is weak, your <strong>reports</strong> will be weak. Mohaaseb works best when the accounting tree is simple, documented, and used consistently.</p>',
                'question_ar' => 'ما هي شجرة الحسابات ولماذا تؤثر على التقارير؟',
                'answer_ar' => '<p>هي هيكل الحسابات الذي تعتمد عليه كل حركة، وضعفها يضعف التقارير.</p>',
            ],
            [
                'question_en' => 'How do we keep inventory valuation accurate with frequent sales?',
                'answer_en' => '<p>Accuracy comes from disciplined posting: every purchase, sale, <strong>returns</strong>, <strong>stock adjustment</strong>, and <strong>stock transfer</strong> must be recorded. With clean <strong>inventory</strong> and <strong>accounting</strong> data, Mohaaseb can generate consistent valuation <strong>reports</strong>.</p>',
                'question_ar' => 'كيف أحافظ على دقة تقييم المخزون؟',
                'answer_ar' => '<p>بتسجيل كل الحركات: بيع/شراء/مرتجع/تعديل/نقل لضمان تقارير صحيحة.</p>',
            ],
            [
                'question_en' => 'Can Mohaaseb handle multi-branch inventory operations?',
                'answer_en' => '<p>Yes. Multi-branch operations are supported through location-aware <strong>inventory</strong>, controlled <strong>stock transfer</strong>, and branch-level <strong>reports</strong>. This is a common benefit of using an integrated <strong>erp</strong> with a <strong>pos</strong>.</p>',
                'question_ar' => 'هل يدعم النظام عدة فروع؟',
                'answer_ar' => '<p>نعم، عبر إدارة مخزون حسب الفرع ونقل مخزون وتقارير لكل فرع.</p>',
            ],
            [
                'question_en' => 'How do we track stock shortages and shrinkage?',
                'answer_en' => '<p>Use cycle counts and post differences as a <strong>stock adjustment</strong>. Then review <strong>inventory</strong> movement and shortage <strong>reports</strong>. This improves controls and protects <strong>accounting</strong> accuracy.</p>',
                'question_ar' => 'كيف أتتبع نقص المخزون؟',
                'answer_ar' => '<p>بالجرد الدوري وتسجيل الفروقات كتعديل مخزون ومراجعة تقارير الحركة.</p>',
            ],
            [
                'question_en' => 'What keywords should we expect to see in Mohaaseb documentation and training?',
                'answer_en' => '<p>You will often see terms like <strong>Mohaaseb</strong>, <strong>inventory</strong>, <strong>accounting</strong>, <strong>accounting tree</strong>, <strong>stock</strong>, <strong>stock transfer</strong>, <strong>stock adjustment</strong>, <strong>reports</strong>, <strong>refunds</strong>, <strong>returns</strong>, <strong>erp</strong>, and <strong>pos</strong>. These reflect the core workflows that connect operations with finance.</p>',
                'question_ar' => 'ما أهم المصطلحات التي تظهر في التدريب؟',
                'answer_ar' => '<p>مصطلحات مثل المخزون والمحاسبة وشجرة الحسابات والنقل والتعديل والتقارير والمرتجعات.</p>',
            ],
            [
                'question_en' => 'How do we configure POS payments to match accounting reports?',
                'answer_en' => '<p>Map each <strong>pos</strong> payment method (cash, card, transfer) to the correct account in your <strong>accounting tree</strong>. This ensures daily sales transactions flow correctly into <strong>accounting</strong> and appear accurately in cash and bank <strong>reports</strong>.</p>',
                'question_ar' => 'كيف أربط طرق الدفع بنقاط البيع مع الحسابات؟',
                'answer_ar' => '<p>اربط كل طريقة دفع بحسابها الصحيح لضمان تقارير نقدية وبنكية دقيقة.</p>',
            ],
            [
                'question_en' => 'What is the best practice for handling refunds at POS?',
                'answer_en' => '<p>Always link <strong>refunds</strong> to the original invoice and keep a reason code. If the refund includes a product return, record <strong>returns</strong> so <strong>stock</strong> and <strong>inventory</strong> movement stay correct and <strong>reports</strong> remain reliable.</p>',
                'question_ar' => 'ما أفضل ممارسة لاسترداد المبالغ؟',
                'answer_ar' => '<p>اربط الاسترداد بالفاتورة وسبب واضح، وإذا عاد المنتج فسجل المرتجع لضمان دقة المخزون.</p>',
            ],
            [
                'question_en' => 'How can we audit stock transfers between warehouse and store?',
                'answer_en' => '<p>Use a two-step confirmation (sent/received) for every <strong>stock transfer</strong>. Then review transfer <strong>reports</strong> and investigate mismatches. This prevents hidden stock issues and protects <strong>inventory</strong> accuracy.</p>',
                'question_ar' => 'كيف أدقق عمليات نقل المخزون؟',
                'answer_ar' => '<p>بتأكيد الإرسال والاستلام ومراجعة تقارير النقل واكتشاف الفروقات.</p>',
            ],
            [
                'question_en' => 'How should we document stock adjustments for compliance?',
                'answer_en' => '<p>For every <strong>stock adjustment</strong>, keep a note, date, responsible user, and reference (count sheet or incident). Clear documentation improves audit trails and keeps <strong>reports</strong> and <strong>accounting</strong> aligned.</p>',
                'question_ar' => 'كيف أوثق تعديل المخزون؟',
                'answer_ar' => '<p>وثّق السبب والتاريخ والمسؤول والمستند الداعم لضمان مسار تدقيق واضح.</p>',
            ],
            [
                'question_en' => 'What is the recommended FAQ structure for an ERP system like Mohaaseb?',
                'answer_en' => '<p>Group FAQs by modules: onboarding, <strong>erp</strong> basics, <strong>pos</strong> operations, <strong>inventory</strong>/<strong>stock</strong>, <strong>accounting</strong>/<strong>accounting tree</strong>, and <strong>reports</strong>. Include practical scenarios such as <strong>returns</strong> and <strong>refunds</strong>.</p>',
                'question_ar' => 'كيف أنظم الأسئلة الشائعة؟',
                'answer_ar' => '<p>قسّمها حسب الوحدات: نقاط البيع، المخزون، المحاسبة، التقارير، والعمليات الشائعة.</p>',
            ],
            [
                'question_en' => 'How do we ensure inventory, accounting, and reports always match?',
                'answer_en' => '<p>Use one system (Mohaaseb), enforce consistent posting, and reconcile regularly. Ensure every sale, purchase, <strong>stock transfer</strong>, <strong>stock adjustment</strong>, <strong>returns</strong>, and <strong>refunds</strong> is recorded. This keeps <strong>inventory</strong>, <strong>accounting</strong>, and <strong>reports</strong> synchronized.</p>',
                'question_ar' => 'كيف أجعل المخزون والمحاسبة والتقارير متطابقة؟',
                'answer_ar' => '<p>بتوحيد النظام وتسجيل كل الحركات ومراجعتها دوريًا لتبقى البيانات متسقة.</p>',
            ],
            [
                'question_en' => 'What is the difference between ERP reports and POS reports?',
                'answer_en' => '<p><strong>POS</strong> reports focus on cashiers and daily sales speed. <strong>ERP</strong> reports provide cross-module views such as profitability, <strong>inventory</strong> valuation, and <strong>accounting</strong> summaries. Mohaaseb gives both sets of <strong>reports</strong> from the same data.</p>',
                'question_ar' => 'ما الفرق بين تقارير ERP وتقارير POS؟',
                'answer_ar' => '<p>تقارير POS يومية وتشغيلية، وتقارير ERP شاملة تربط المخزون والمحاسبة والربحية.</p>',
            ],
            [
                'question_en' => 'How should we handle returns that come back to stock?',
                'answer_en' => '<p>Record the <strong>returns</strong> against the invoice and ensure the item is received back into <strong>stock</strong>. This updates <strong>inventory</strong> and keeps sales and margin <strong>reports</strong> accurate. If money is paid back, process the related <strong>refunds</strong>.</p>',
                'question_ar' => 'كيف أسجل مرتجع يعود للمخزون؟',
                'answer_ar' => '<p>سجل المرتجع على الفاتورة وأعد الكمية للمخزون، وإذا أعيد المبلغ فسجل الاسترداد.</p>',
            ],
            [
                'question_en' => 'Can we produce inventory reports by category and warehouse?',
                'answer_en' => '<p>Yes. You can analyze <strong>inventory</strong> by category, location, and movement. These <strong>reports</strong> are essential for controlling <strong>stock</strong> levels and planning purchases in an <strong>erp</strong> environment.</p>',
                'question_ar' => 'هل يمكن تقارير مخزون حسب المستودع؟',
                'answer_ar' => '<p>نعم، تقارير حسب الصنف والموقع وحركة المخزون تساعد على التخطيط والرقابة.</p>',
            ],
            [
                'question_en' => 'How do stock transfers affect accounting?',
                'answer_en' => '<p>A <strong>stock transfer</strong> mainly changes <strong>stock</strong> location. If your <strong>accounting</strong> includes location-level valuation, transfers can affect segment <strong>reports</strong>. The key is to keep transfers documented so <strong>inventory</strong> remains auditable.</p>',
                'question_ar' => 'هل يؤثر نقل المخزون على المحاسبة؟',
                'answer_ar' => '<p>غالبًا يؤثر على موقع الكمية، وقد يؤثر على تقارير الفروع حسب إعدادات التقارير.</p>',
            ],
            [
                'question_en' => 'How do stock adjustments affect accounting?',
                'answer_en' => '<p>A <strong>stock adjustment</strong> changes <strong>inventory</strong> value and may post an expense or variance entry in <strong>accounting</strong>. This makes variance visible in <strong>reports</strong> and improves control over shrinkage.</p>',
                'question_ar' => 'هل يؤثر تعديل المخزون على المحاسبة؟',
                'answer_ar' => '<p>نعم قد يثبت فرقًا كمصروف/فروق ويظهر أثره في التقارير.</p>',
            ],
            [
                'question_en' => 'Which modules are most important to start with in Mohaaseb?',
                'answer_en' => '<p>Most businesses start with <strong>pos</strong> (sales), <strong>inventory</strong> (items and <strong>stock</strong>), and <strong>accounting</strong> (including the <strong>accounting tree</strong>). Then add workflows like <strong>stock transfer</strong>, <strong>stock adjustment</strong>, and operational <strong>reports</strong>. Handle <strong>returns</strong> and <strong>refunds</strong> early to avoid messy history.</p>',
                'question_ar' => 'ما أهم الوحدات للبدء؟',
                'answer_ar' => '<p>ابدأ بالمبيعات والمخزون والمحاسبة ثم أضف النقل والتعديل والتقارير والمرتجعات.</p>',
            ],
            [
                'question_en' => 'How do we create better reports without creating too many accounts?',
                'answer_en' => '<p>Use a balanced <strong>accounting tree</strong>: only add accounts that improve decisions. Combine that with operational tagging (branch, channel) and consistent transactions. Mohaaseb can then generate strong <strong>reports</strong> without making your accounting tree impossible to use.</p>',
                'question_ar' => 'كيف أحسن التقارير دون تضخم شجرة الحسابات؟',
                'answer_ar' => '<p>أضف حسابات تخدم التحليل فقط مع تصنيفات تشغيلية وثبات في التسجيل.</p>',
            ],
            [
                'question_en' => 'What should we do if inventory reports show negative stock?',
                'answer_en' => '<p>Negative <strong>stock</strong> usually means missing purchases, wrong timing, or improper <strong>stock transfer</strong>/<strong>stock adjustment</strong>. Investigate movement <strong>reports</strong>, fix the underlying transactions, and prevent backdated edits that break <strong>inventory</strong> accuracy.</p>',
                'question_ar' => 'ماذا أفعل إذا ظهر مخزون سالب؟',
                'answer_ar' => '<p>راجع الحركات والمشتريات والنقل/التعديل، ثم صحح السبب ومنع التلاعب بالتواريخ.</p>',
            ],
            [
                'question_en' => 'How do we train staff to use POS correctly with accounting?',
                'answer_en' => '<p>Train staff on consistent <strong>pos</strong> documents (invoice/receipt), correct payment selection, and how <strong>returns</strong> and <strong>refunds</strong> should be recorded. When staff follows the workflow, <strong>accounting</strong> and <strong>reports</strong> stay accurate.</p>',
                'question_ar' => 'كيف أدرب الفريق على نقاط البيع؟',
                'answer_ar' => '<p>بتدريبهم على الفواتير وطرق الدفع وتسجيل المرتجعات والاسترداد بشكل صحيح.</p>',
            ],
            [
                'question_en' => 'Can Mohaaseb be used for service businesses without inventory?',
                'answer_en' => '<p>Yes. Even without <strong>inventory</strong>, Mohaaseb still provides <strong>accounting</strong>, an <strong>accounting tree</strong>, invoicing via <strong>pos</strong>-style workflows, and strong <strong>reports</strong> for profitability and cash flow.</p>',
                'question_ar' => 'هل يصلح للشركات الخدمية بدون مخزون؟',
                'answer_ar' => '<p>نعم، يمكنك استخدامه للفواتير والمحاسبة والتقارير حتى بدون إدارة مخزون.</p>',
            ],
            [
                'question_en' => 'What is the fastest way to find mistakes in reports?',
                'answer_en' => '<p>Start with reconciliation and exception checks: unusual discounts, high <strong>returns</strong>/<strong>refunds</strong>, large <strong>stock adjustment</strong> entries, and frequent <strong>stock transfer</strong> reversals. These signals help you pinpoint posting issues in <strong>inventory</strong> and <strong>accounting</strong> quickly.</p>',
                'question_ar' => 'ما أسرع طريقة لاكتشاف أخطاء التقارير؟',
                'answer_ar' => '<p>ابحث عن الاستثناءات: خصومات غير طبيعية، مرتجعات عالية، تعديلات مخزون كبيرة، ونقل متكرر.</p>',
            ],
            [
                'question_en' => 'Does Mohaaseb support exporting reports for auditors?',
                'answer_en' => '<p>Yes. You can export key <strong>reports</strong> (sales, <strong>inventory</strong> movement, and <strong>accounting</strong> summaries). Clean source data is the real advantage: accurate <strong>accounting tree</strong> mapping and correct transactions make audits easier.</p>',
                'question_ar' => 'هل يمكن تصدير التقارير؟',
                'answer_ar' => '<p>نعم، ويمكن تصدير تقارير المبيعات والمخزون والمحاسبة للمراجعة.</p>',
            ],
            [
                'question_en' => 'How should we name FAQ questions to improve search visibility?',
                'answer_en' => '<p>Use user-style queries that include product and module terms, such as “Mohaaseb inventory report” or “accounting tree setup.” Including terms like <strong>inventory</strong>, <strong>accounting</strong>, <strong>reports</strong>, <strong>erp</strong>, and <strong>pos</strong> helps users find answers faster.</p>',
                'question_ar' => 'كيف أكتب الأسئلة لتكون واضحة؟',
                'answer_ar' => '<p>اكتبها بصيغة سؤال يبحث عنه المستخدم، مع كلمات مثل المخزون والمحاسبة والتقارير.</p>',
            ],
            [
                'question_en' => 'What should we do before enabling stock transfer in multiple locations?',
                'answer_en' => '<p>Define locations, user permissions, and a standard <strong>stock transfer</strong> workflow. Make sure item units and opening <strong>stock</strong> are correct so <strong>inventory</strong> movement <strong>reports</strong> are trustworthy from day one.</p>',
                'question_ar' => 'ماذا أفعل قبل تفعيل نقل المخزون؟',
                'answer_ar' => '<p>عرّف المواقع والصلاحيات وثبت الوحدات وأرصدة البداية لتكون تقارير الحركة دقيقة.</p>',
            ],
            [
                'question_en' => 'What should we do before running stock adjustment after a physical count?',
                'answer_en' => '<p>Freeze receiving and sales briefly, export current <strong>inventory</strong> and <strong>stock</strong> reports, then perform the count. After that, post a single controlled <strong>stock adjustment</strong> so the audit trail and <strong>reports</strong> remain clear.</p>',
                'question_ar' => 'ماذا أفعل قبل تعديل المخزون بعد الجرد؟',
                'answer_ar' => '<p>أوقف الحركة مؤقتًا، خذ تقارير قبل الجرد، ثم سجل التعديل بشكل مضبوط وواضح.</p>',
            ],
            [
                'question_en' => 'Can the FAQ page cover common ERP/POS scenarios from the blog articles?',
                'answer_en' => '<p>Yes. A good FAQ page highlights practical scenarios (inventory setup, POS mistakes, accounting tree design, returns and refunds, stock transfer and stock adjustment) and links them to reliable reports. This complements long-form articles while staying quick to scan.</p>',
                'question_ar' => 'هل يمكن للأسئلة الشائعة تلخيص سيناريوهات من المقالات؟',
                'answer_ar' => '<p>نعم، من الأفضل تلخيص السيناريوهات العملية بسرعة مثل المخزون ونقاط البيع وشجرة الحسابات والمرتجعات.</p>',
            ],
        ];

        $sort = 1;

        foreach ($faqs as $row) {
            Faq::updateOrCreate(
                ['question_en' => $row['question_en']],
                [
                    'question_en' => $row['question_en'],
                    'question_ar' => $row['question_ar'] ?? null,
                    'answer_en' => $row['answer_en'],
                    'answer_ar' => $row['answer_ar'] ?? null,
                    'is_published' => true,
                    'sort_order' => $sort++,
                ]
            );
        }
    }
}
