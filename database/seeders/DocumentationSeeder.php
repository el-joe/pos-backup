<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class DocumentationSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'docs-getting-started',
                'section' => 'getting-started',
                'sort_order' => 1,
                'title_en' => 'Getting Started with Mohaaseb',
                'title_ar' => 'البدء مع محاسب',
                'short_description_en' => 'A complete walkthrough of setting up your Mohaaseb account, from registration to your first sale.',
                'short_description_ar' => 'دليل شامل لإعداد حساب محاسب الخاص بك، من التسجيل إلى أول عملية بيع.',
                'content_en' => '<p>Welcome to Mohaaseb, the all-in-one cloud ERP, point of sale, and accounting platform built for businesses in Egypt and across the Arab world. This guide walks you through everything you need to know to get your account up and running, whether you run a single retail shop or manage multiple branches with a full back office.</p>'
                    . '<h2>Creating Your Account</h2>'
                    . '<p>Registration takes only a few minutes. Visit the registration page, provide your business name, choose a unique subdomain, and select the plan that best fits your needs. Mohaaseb automatically provisions a dedicated tenant database for your company, so your data is completely isolated from other businesses on the platform. Once your workspace is ready, you will receive a confirmation email with a direct link to your dashboard.</p>'
                    . '<h2>Setting Up Your Company Profile</h2>'
                    . '<p>Before you start selling, take a few minutes to configure your company profile. Add your business logo, legal name, tax registration number, address, and default currency. These details appear automatically on invoices, receipts, and reports, so getting them right early saves you from manual corrections later. You can always update this information from Settings at any time.</p>'
                    . '<h2>Inviting Your Team</h2>'
                    . '<p>Mohaaseb supports role-based access control, allowing you to invite cashiers, accountants, warehouse staff, and managers with permissions tailored to their responsibilities. Navigate to the Users section, click Add User, and assign a role such as Admin, Cashier, or Accountant. Each role determines which modules and actions a user can access, keeping sensitive financial data protected.</p>'
                    . '<h2>Configuring Products and Branches</h2>'
                    . '<p>Next, set up your branches and warehouses if you operate from more than one location. Then move to the Products module to add your catalog, either manually or by importing a spreadsheet. Each product can have its own pricing, tax rate, barcode, and stock tracking settings.</p>'
                    . '<h2>Your First Sale</h2>'
                    . '<p>With products and users in place, open the POS terminal from any branch, add items to the cart, apply discounts if needed, and complete checkout using cash, card, or a custom payment method. The transaction automatically updates your inventory and posts the relevant accounting entries behind the scenes.</p>'
                    . '<h2>Next Steps</h2>'
                    . '<p>Explore the rest of this documentation to learn about Sales, Purchases, Inventory, Accounting, HRM, Contracting, and Settings modules in depth. Each section includes step-by-step instructions and short video walkthroughs to help your team get productive quickly.</p>',
                'content_ar' => '<p>مرحباً بك في محاسب، منصة تخطيط موارد المؤسسات السحابية المتكاملة التي تجمع بين نقاط البيع والمحاسبة، وهي مصممة خصيصاً للشركات في مصر والعالم العربي. يرشدك هذا الدليل خطوة بخطوة إلى كل ما تحتاج معرفته لتشغيل حسابك، سواء كنت تدير متجراً واحداً أو عدة فروع مع نظام محاسبي متكامل.</p>'
                    . '<h2>إنشاء حسابك</h2>'
                    . '<p>لا يستغرق التسجيل سوى بضع دقائق. توجه إلى صفحة التسجيل، أدخل اسم نشاطك التجاري، اختر نطاقاً فرعياً فريداً، وحدد الخطة التي تناسب احتياجاتك. يقوم محاسب تلقائياً بإنشاء قاعدة بيانات مستقلة لشركتك، مما يضمن عزل بياناتك تماماً عن الشركات الأخرى على المنصة. بمجرد جاهزية مساحة العمل، ستصلك رسالة تأكيد تحتوي على رابط مباشر للوحة التحكم.</p>'
                    . '<h2>إعداد ملف الشركة</h2>'
                    . '<p>قبل البدء بالبيع، خصص بضع دقائق لإعداد ملف شركتك. أضف شعار نشاطك، الاسم القانوني، الرقم الضريبي، العنوان، والعملة الافتراضية. تظهر هذه البيانات تلقائياً على الفواتير والإيصالات والتقارير، لذا فإن ضبطها مبكراً يوفر عليك التصحيحات اليدوية لاحقاً. يمكنك دائماً تحديث هذه المعلومات من الإعدادات في أي وقت.</p>'
                    . '<h2>دعوة فريق العمل</h2>'
                    . '<p>يدعم محاسب التحكم في الصلاحيات حسب الدور الوظيفي، مما يتيح لك دعوة الكاشيرين والمحاسبين وموظفي المستودعات والمديرين بصلاحيات مخصصة لمسؤولياتهم. انتقل إلى قسم المستخدمين، اضغط على إضافة مستخدم، وحدد دوراً مثل مدير أو كاشير أو محاسب. يحدد كل دور الوحدات والإجراءات التي يمكن للمستخدم الوصول إليها، مما يحافظ على أمان البيانات المالية الحساسة.</p>'
                    . '<h2>إعداد المنتجات والفروع</h2>'
                    . '<p>بعد ذلك، قم بإعداد فروعك ومستودعاتك إذا كنت تدير أكثر من موقع. ثم انتقل إلى وحدة المنتجات لإضافة كتالوجك، إما يدوياً أو عن طريق استيراد ملف إكسل. يمكن لكل منتج أن يكون له تسعير خاص، نسبة ضريبة، باركود، وإعدادات تتبع مخزون مستقلة.</p>'
                    . '<h2>أول عملية بيع لك</h2>'
                    . '<p>بعد إعداد المنتجات والمستخدمين، افتح شاشة نقطة البيع من أي فرع، أضف الأصناف إلى السلة، طبّق الخصومات إذا لزم الأمر، وأكمل عملية الدفع نقداً أو بالبطاقة أو بأي وسيلة دفع مخصصة. تقوم المعاملة تلقائياً بتحديث المخزون وتسجيل القيود المحاسبية المرتبطة بها في الخلفية.</p>'
                    . '<h2>الخطوات التالية</h2>'
                    . '<p>استكشف بقية هذا الدليل لتتعرف بعمق على وحدات المبيعات والمشتريات والمخزون والمحاسبة والموارد البشرية والمقاولات والإعدادات. يتضمن كل قسم تعليمات تفصيلية ومقاطع فيديو قصيرة لمساعدة فريقك على العمل بكفاءة بسرعة.</p>',
            ],
            [
                'slug' => 'docs-point-of-sale',
                'section' => 'pos',
                'sort_order' => 1,
                'title_en' => 'Point of Sale (POS)',
                'title_ar' => 'نقطة البيع',
                'short_description_en' => 'Learn how to use the Mohaaseb POS terminal for fast, reliable checkout in-store.',
                'short_description_ar' => 'تعرف على كيفية استخدام شاشة نقطة البيع في محاسب لإتمام عمليات البيع بسرعة وموثوقية.',
                'content_en' => '<p>The Mohaaseb Point of Sale (POS) module is designed for speed and reliability, even on unstable internet connections. It works as a modern web-based terminal that any cashier can learn within minutes, while still offering the depth needed for multi-branch retail operations.</p>'
                    . '<h2>Opening a Register Session</h2>'
                    . '<p>Each shift begins by opening a cash register session, where the cashier records the opening cash balance. This creates an audit trail that ties every transaction during the shift to a specific cashier and register, making end-of-day reconciliation straightforward.</p>'
                    . '<h2>Building a Sale</h2>'
                    . '<p>Cashiers can search products by name, SKU, or barcode using a connected barcode scanner, or browse categories using the touch-friendly product grid. Items can be added with custom quantities, unit discounts, or line-level notes. The cart automatically calculates taxes, subtotals, and any active promotions.</p>'
                    . '<h2>Applying Discounts and Promotions</h2>'
                    . '<p>Managers can configure percentage or fixed-amount discounts at the cart or line-item level, with permission controls to prevent unauthorized price changes. Loyalty points and customer-specific pricing tiers are also supported for businesses that run rewards programs.</p>'
                    . '<h2>Payment and Checkout</h2>'
                    . '<p>Mohaaseb supports split payments across cash, card, wallet, and custom payment methods in a single transaction. Once payment is confirmed, a receipt is generated instantly and can be printed, emailed, or sent via WhatsApp depending on your integration settings.</p>'
                    . '<h2>Returns and Exchanges</h2>'
                    . '<p>Handling returns is built directly into the POS screen. Cashiers can look up a previous invoice, select the items being returned, and issue a refund or exchange, with inventory and accounting entries adjusted automatically.</p>'
                    . '<h2>Offline Resilience</h2>'
                    . '<p>If your internet connection drops mid-shift, the POS terminal continues to accept sales locally and automatically syncs once connectivity is restored, ensuring you never lose a transaction during peak hours.</p>'
                    . '<h2>Closing the Shift</h2>'
                    . '<p>At the end of the day, cashiers close their register session, and the system produces a full shift report comparing expected versus actual cash, broken down by payment method, ready for manager review.</p>',
                'content_ar' => '<p>صُممت وحدة نقطة البيع في محاسب لتكون سريعة وموثوقة حتى في حال ضعف الاتصال بالإنترنت. تعمل كشاشة حديثة قائمة على الويب يمكن لأي كاشير تعلمها خلال دقائق، مع توفير العمق اللازم لعمليات التجزئة متعددة الفروع.</p>'
                    . '<h2>فتح جلسة الصندوق</h2>'
                    . '<p>تبدأ كل وردية بفتح جلسة صندوق نقدي، حيث يسجل الكاشير الرصيد الافتتاحي. يُنشئ هذا مساراً واضحاً يربط كل معاملة خلال الوردية بكاشير وصندوق محددين، مما يجعل تسوية نهاية اليوم أمراً بسيطاً.</p>'
                    . '<h2>إنشاء عملية بيع</h2>'
                    . '<p>يمكن للكاشير البحث عن المنتجات بالاسم أو الرمز أو الباركود باستخدام قارئ باركود متصل، أو التصفح عبر شبكة المنتجات سهلة اللمس. يمكن إضافة الأصناف بكميات مخصصة وخصومات على مستوى الوحدة أو ملاحظات على السطر. تحسب السلة تلقائياً الضرائب والمجاميع الفرعية وأي عروض ترويجية نشطة.</p>'
                    . '<h2>تطبيق الخصومات والعروض</h2>'
                    . '<p>يمكن للمديرين إعداد خصومات بنسبة مئوية أو مبلغ ثابت على مستوى السلة أو السطر، مع ضوابط صلاحيات لمنع تغييرات الأسعار غير المصرح بها. كما يدعم النظام نقاط الولاء وفئات التسعير الخاصة بالعملاء للشركات التي تدير برامج مكافآت.</p>'
                    . '<h2>الدفع وإتمام البيع</h2>'
                    . '<p>يدعم محاسب تقسيم الدفع بين النقد والبطاقة والمحفظة الإلكترونية وطرق دفع مخصصة في معاملة واحدة. بمجرد تأكيد الدفع، يتم إنشاء إيصال فوراً يمكن طباعته أو إرساله بالبريد الإلكتروني أو عبر واتساب حسب إعدادات التكامل لديك.</p>'
                    . '<h2>المرتجعات والاستبدال</h2>'
                    . '<p>معالجة المرتجعات مدمجة مباشرة في شاشة نقطة البيع. يمكن للكاشير البحث عن فاتورة سابقة، تحديد الأصناف المرتجعة، وإصدار استرداد أو استبدال، مع تعديل قيود المخزون والمحاسبة تلقائياً.</p>'
                    . '<h2>العمل دون اتصال بالإنترنت</h2>'
                    . '<p>في حال انقطاع الاتصال بالإنترنت أثناء الوردية، تستمر شاشة نقطة البيع في قبول عمليات البيع محلياً وتتم مزامنتها تلقائياً عند استعادة الاتصال، مما يضمن عدم فقدان أي معاملة خلال ساعات الذروة.</p>'
                    . '<h2>إغلاق الوردية</h2>'
                    . '<p>في نهاية اليوم، يقوم الكاشير بإغلاق جلسة الصندوق، ويُصدر النظام تقريراً كاملاً عن الوردية يقارن النقد المتوقع بالفعلي، مقسماً حسب طريقة الدفع، وجاهزاً لمراجعة المدير.</p>',
            ],
            [
                'slug' => 'docs-products-inventory',
                'section' => 'inventory',
                'sort_order' => 1,
                'title_en' => 'Products & Inventory',
                'title_ar' => 'المنتجات والمخزون',
                'short_description_en' => 'Manage your product catalog, stock levels, and warehouses across every branch.',
                'short_description_ar' => 'إدارة كتالوج المنتجات ومستويات المخزون والمستودعات عبر جميع الفروع.',
                'content_en' => '<p>Accurate inventory is the backbone of any retail or wholesale business, and Mohaaseb gives you complete visibility over stock across every branch and warehouse in real time.</p>'
                    . '<h2>Building Your Product Catalog</h2>'
                    . '<p>Products can be created individually or imported in bulk from a spreadsheet, including fields such as name, SKU, barcode, category, unit of measure, cost price, and selling price. Variant products, such as different sizes or colors, are supported through a parent-variant structure so you can track each variation separately.</p>'
                    . '<h2>Categories and Units</h2>'
                    . '<p>Organizing products into categories and subcategories makes browsing the POS grid and generating reports much easier. You can also define custom units of measure and conversion rates, useful for businesses that buy in bulk (cartons) and sell individually (pieces).</p>'
                    . '<h2>Multi-Warehouse Stock Tracking</h2>'
                    . '<p>Every branch can have one or more associated warehouses. Stock levels are tracked independently per warehouse, and you can transfer inventory between locations with a full audit trail of who initiated the transfer and when it was received.</p>'
                    . '<h2>Stock Adjustments</h2>'
                    . '<p>Damages, expired goods, or physical count discrepancies can be corrected through stock adjustment entries, each requiring a reason code so that shrinkage can be analyzed over time.</p>'
                    . '<h2>Low Stock Alerts</h2>'
                    . '<p>Set minimum stock thresholds per product so that Mohaaseb automatically flags items running low, helping you reorder before you run out and avoid lost sales.</p>'
                    . '<h2>Batch and Expiry Tracking</h2>'
                    . '<p>For businesses handling perishable or regulated goods, Mohaaseb supports batch numbers and expiry dates, with reporting that highlights stock nearing expiration.</p>'
                    . '<h2>Inventory Valuation Reports</h2>'
                    . '<p>At any time, generate an inventory valuation report to see the total cost and retail value of stock on hand, broken down by warehouse, category, or product — essential information for accurate financial statements.</p>',
                'content_ar' => '<p>يُعد المخزون الدقيق العمود الفقري لأي نشاط تجزئة أو جملة، ويمنحك محاسب رؤية كاملة على المخزون عبر جميع الفروع والمستودعات في الوقت الفعلي.</p>'
                    . '<h2>بناء كتالوج المنتجات</h2>'
                    . '<p>يمكن إنشاء المنتجات بشكل فردي أو استيرادها بالجملة من ملف إكسل، وتشمل الحقول الاسم والرمز والباركود والفئة ووحدة القياس وسعر التكلفة وسعر البيع. يدعم النظام المنتجات ذات الخصائص المختلفة مثل الأحجام أو الألوان من خلال بنية أب-تفرع لتتبع كل تنويعة بشكل منفصل.</p>'
                    . '<h2>الفئات والوحدات</h2>'
                    . '<p>يجعل تنظيم المنتجات في فئات وفئات فرعية تصفح شاشة نقطة البيع وإنشاء التقارير أسهل بكثير. يمكنك أيضاً تعريف وحدات قياس مخصصة ومعدلات تحويل، وهو مفيد للشركات التي تشتري بالجملة (كراتين) وتبيع بالقطعة.</p>'
                    . '<h2>تتبع المخزون متعدد المستودعات</h2>'
                    . '<p>يمكن أن يرتبط كل فرع بمستودع واحد أو أكثر. تُتبع مستويات المخزون بشكل مستقل لكل مستودع، ويمكنك نقل المخزون بين المواقع مع مسار تدقيق كامل يوضح من بدأ النقل ومتى تم استلامه.</p>'
                    . '<h2>تسويات المخزون</h2>'
                    . '<p>يمكن تصحيح التلف أو انتهاء الصلاحية أو الفروقات في الجرد الفعلي من خلال قيود تسوية المخزون، ويتطلب كل منها رمز سبب حتى يمكن تحليل الفاقد مع مرور الوقت.</p>'
                    . '<h2>تنبيهات انخفاض المخزون</h2>'
                    . '<p>حدد الحد الأدنى للمخزون لكل منتج بحيث يقوم محاسب تلقائياً بتمييز الأصناف التي أوشكت على النفاد، مما يساعدك على إعادة الطلب قبل نفاد الكمية وتجنب فقدان المبيعات.</p>'
                    . '<h2>تتبع الدفعات وتاريخ الصلاحية</h2>'
                    . '<p>بالنسبة للشركات التي تتعامل مع السلع القابلة للتلف أو الخاضعة للرقابة، يدعم محاسب أرقام الدفعات وتواريخ انتهاء الصلاحية، مع تقارير تسلط الضوء على المخزون القريب من الانتهاء.</p>'
                    . '<h2>تقارير تقييم المخزون</h2>'
                    . '<p>في أي وقت، يمكنك إنشاء تقرير تقييم المخزون لمعرفة إجمالي التكلفة والقيمة البيعية للمخزون المتوفر، مقسماً حسب المستودع أو الفئة أو المنتج — وهي معلومات أساسية للبيانات المالية الدقيقة.</p>',
            ],
            [
                'slug' => 'docs-sales-management',
                'section' => 'sales',
                'sort_order' => 1,
                'title_en' => 'Sales Management',
                'title_ar' => 'إدارة المبيعات',
                'short_description_en' => 'Create quotations, sales orders, and invoices, and track customer balances with ease.',
                'short_description_ar' => 'إنشاء عروض الأسعار وأوامر البيع والفواتير ومتابعة أرصدة العملاء بسهولة.',
                'content_en' => '<p>Beyond the POS terminal, Mohaaseb includes a full sales management workflow for businesses that issue quotations, sell on credit, or manage business-to-business relationships.</p>'
                    . '<h2>Quotations</h2>'
                    . '<p>Create professional quotations for prospective customers, complete with itemized pricing, discounts, and validity dates. Quotations can be converted into sales orders or invoices with a single click once the customer approves, avoiding duplicate data entry.</p>'
                    . '<h2>Sales Orders and Invoices</h2>'
                    . '<p>Sales orders track committed sales before delivery or invoicing, useful for businesses with lead times or partial shipments. Once goods are delivered, orders convert into invoices, which post automatically to your accounts receivable ledger.</p>'
                    . '<h2>Customer Accounts and Credit Limits</h2>'
                    . '<p>Every customer has a running account balance visible from their profile. Set credit limits and payment terms per customer, and Mohaaseb will warn cashiers or sales staff before a transaction exceeds the approved limit.</p>'
                    . '<h2>Recording Payments</h2>'
                    . '<p>Payments against outstanding invoices can be recorded individually or applied against multiple invoices at once, with automatic allocation to the oldest balances if desired. Partial payments and installment plans are fully supported.</p>'
                    . '<h2>Sales Returns</h2>'
                    . '<p>Process customer returns against any prior invoice, with the option to issue a refund, store credit, or exchange, all reflected instantly in inventory and accounting.</p>'
                    . '<h2>Sales Reports and Analytics</h2>'
                    . '<p>Track daily, weekly, and monthly sales trends, best-selling products, top customers, and salesperson performance from the Statistics dashboard, helping you make informed decisions about pricing and promotions.</p>'
                    . '<h2>Multi-Currency Support</h2>'
                    . '<p>For businesses trading internationally, sales documents can be issued in a foreign currency with automatic conversion to your base currency for accounting purposes.</p>',
                'content_ar' => '<p>بالإضافة إلى شاشة نقطة البيع، يتضمن محاسب سير عمل كاملاً لإدارة المبيعات للشركات التي تصدر عروض أسعار أو تبيع بالأجل أو تدير علاقات بين الشركات.</p>'
                    . '<h2>عروض الأسعار</h2>'
                    . '<p>أنشئ عروض أسعار احترافية للعملاء المحتملين، مع تسعير مفصل وخصومات وتواريخ صلاحية. يمكن تحويل عروض الأسعار إلى أوامر بيع أو فواتير بنقرة واحدة بمجرد موافقة العميل، مما يتجنب إدخال البيانات المكرر.</p>'
                    . '<h2>أوامر البيع والفواتير</h2>'
                    . '<p>تتتبع أوامر البيع المبيعات المؤكدة قبل التسليم أو الفوترة، وهو مفيد للشركات ذات مهل التسليم أو الشحنات الجزئية. بمجرد تسليم البضائع، تتحول الأوامر إلى فواتير تُرحّل تلقائياً إلى دفتر أستاذ الذمم المدينة.</p>'
                    . '<h2>حسابات العملاء وحدود الائتمان</h2>'
                    . '<p>لكل عميل رصيد حساب جارٍ يظهر من ملفه الشخصي. حدد حدود الائتمان وشروط الدفع لكل عميل، وسينبه محاسب الكاشير أو موظف المبيعات قبل تجاوز المعاملة للحد المعتمد.</p>'
                    . '<h2>تسجيل المدفوعات</h2>'
                    . '<p>يمكن تسجيل المدفوعات مقابل الفواتير المستحقة بشكل فردي أو تطبيقها على عدة فواتير دفعة واحدة، مع تخصيص تلقائي للأرصدة الأقدم إذا رغبت. المدفوعات الجزئية وخطط التقسيط مدعومة بالكامل.</p>'
                    . '<h2>مرتجعات المبيعات</h2>'
                    . '<p>عالج مرتجعات العملاء مقابل أي فاتورة سابقة، مع خيار إصدار استرداد أو رصيد للمتجر أو استبدال، وينعكس ذلك فوراً في المخزون والمحاسبة.</p>'
                    . '<h2>تقارير وتحليلات المبيعات</h2>'
                    . '<p>تابع اتجاهات المبيعات اليومية والأسبوعية والشهرية، والمنتجات الأكثر مبيعاً، وأفضل العملاء، وأداء مندوبي المبيعات من لوحة الإحصائيات، مما يساعدك على اتخاذ قرارات مستنيرة بشأن التسعير والعروض الترويجية.</p>'
                    . '<h2>دعم تعدد العملات</h2>'
                    . '<p>بالنسبة للشركات التي تتاجر دولياً، يمكن إصدار مستندات البيع بعملة أجنبية مع تحويل تلقائي إلى العملة الأساسية لأغراض المحاسبة.</p>',
            ],
            [
                'slug' => 'docs-purchase-management',
                'section' => 'purchases',
                'sort_order' => 1,
                'title_en' => 'Purchase Management',
                'title_ar' => 'إدارة المشتريات',
                'short_description_en' => 'Manage suppliers, purchase orders, and goods receipts to keep your inventory replenished.',
                'short_description_ar' => 'إدارة الموردين وأوامر الشراء واستلام البضائع للحفاظ على تجديد مخزونك.',
                'content_en' => '<p>Mohaaseb\'s purchasing module streamlines the entire procurement cycle, from requesting quotes to paying suppliers, keeping your inventory replenished and your costs under control.</p>'
                    . '<h2>Supplier Management</h2>'
                    . '<p>Maintain a complete supplier directory with contact details, payment terms, tax numbers, and historical purchase performance. Each supplier has a running account balance so you always know what you owe.</p>'
                    . '<h2>Purchase Requests and Orders</h2>'
                    . '<p>Warehouse or branch staff can raise purchase requests when stock runs low, which managers review and convert into formal purchase orders sent to suppliers. Purchase orders capture expected quantities, prices, and delivery dates.</p>'
                    . '<h2>Goods Receipt</h2>'
                    . '<p>When a shipment arrives, warehouse staff record a goods receipt against the purchase order, matching received quantities to what was ordered. Any discrepancies, such as short shipments or damaged goods, are flagged for follow-up with the supplier.</p>'
                    . '<h2>Purchase Invoices</h2>'
                    . '<p>Once goods are received, the system generates a purchase invoice that updates your accounts payable and, if configured, automatically updates product cost prices using weighted average or FIFO costing methods.</p>'
                    . '<h2>Supplier Payments</h2>'
                    . '<p>Record full or partial payments to suppliers, track due dates, and generate an aging report to plan cash flow and avoid missing early-payment discounts.</p>'
                    . '<h2>Purchase Returns</h2>'
                    . '<p>Return defective or excess goods to suppliers with a purchase return document that reverses the relevant inventory and accounting entries.</p>'
                    . '<h2>Purchasing Analytics</h2>'
                    . '<p>Review spend by supplier, category, and branch, and identify your most cost-effective vendors using built-in purchasing reports, helping procurement teams negotiate better terms.</p>',
                'content_ar' => '<p>تُبسّط وحدة المشتريات في محاسب دورة الشراء بأكملها، من طلب عروض الأسعار إلى دفع الموردين، مع الحفاظ على تجديد مخزونك والتحكم في تكاليفك.</p>'
                    . '<h2>إدارة الموردين</h2>'
                    . '<p>احتفظ بدليل موردين كامل يتضمن بيانات الاتصال وشروط الدفع والأرقام الضريبية والأداء التاريخي للشراء. لكل مورد رصيد حساب جارٍ حتى تعرف دائماً ما تدين به.</p>'
                    . '<h2>طلبات وأوامر الشراء</h2>'
                    . '<p>يمكن لموظفي المستودعات أو الفروع رفع طلبات شراء عند انخفاض المخزون، والتي يراجعها المديرون ويحولونها إلى أوامر شراء رسمية تُرسل إلى الموردين. تسجل أوامر الشراء الكميات المتوقعة والأسعار وتواريخ التسليم.</p>'
                    . '<h2>استلام البضائع</h2>'
                    . '<p>عند وصول الشحنة، يسجل موظفو المستودع استلام البضائع مقابل أمر الشراء، مطابقين الكميات المستلمة بما تم طلبه. يتم تمييز أي فروقات، مثل نقص الشحنة أو تلف البضائع، للمتابعة مع المورد.</p>'
                    . '<h2>فواتير الشراء</h2>'
                    . '<p>بمجرد استلام البضائع، يُنشئ النظام فاتورة شراء تُحدّث حسابات الدائنين، وإذا تم إعدادها، تُحدّث تلقائياً أسعار تكلفة المنتجات باستخدام طريقة المتوسط المرجح أو الوارد أولاً صادر أولاً.</p>'
                    . '<h2>دفعات الموردين</h2>'
                    . '<p>سجل مدفوعات كاملة أو جزئية للموردين، وتابع تواريخ الاستحقاق، وأنشئ تقرير أعمار الديون لتخطيط التدفق النقدي وتجنب فقدان خصومات السداد المبكر.</p>'
                    . '<h2>مرتجعات المشتريات</h2>'
                    . '<p>أعد البضائع المعيبة أو الزائدة إلى الموردين بمستند مرتجع شراء يعكس قيود المخزون والمحاسبة ذات الصلة.</p>'
                    . '<h2>تحليلات المشتريات</h2>'
                    . '<p>راجع الإنفاق حسب المورد والفئة والفرع، وحدد الموردين الأكثر فعالية من حيث التكلفة باستخدام تقارير المشتريات المدمجة، مما يساعد فرق المشتريات على التفاوض على شروط أفضل.</p>',
            ],
            [
                'slug' => 'docs-accounting-finance',
                'section' => 'accounting',
                'sort_order' => 1,
                'title_en' => 'Accounting & Finance',
                'title_ar' => 'المحاسبة والمالية',
                'short_description_en' => 'A full double-entry accounting system that updates automatically from your daily operations.',
                'short_description_ar' => 'نظام محاسبة كامل بالقيد المزدوج يتم تحديثه تلقائياً من عملياتك اليومية.',
                'content_en' => '<p>Mohaaseb includes a complete double-entry accounting engine that works quietly in the background, automatically generating journal entries from every sale, purchase, payment, and stock movement across your business.</p>'
                    . '<h2>Chart of Accounts</h2>'
                    . '<p>A pre-configured chart of accounts covers assets, liabilities, equity, revenue, and expenses, and can be fully customized to match local accounting standards or your accountant\'s preferences. You can add, rename, or reorganize accounts at any time.</p>'
                    . '<h2>Automatic Journal Entries</h2>'
                    . '<p>Every POS sale, invoice, purchase, payment, and inventory adjustment automatically creates the corresponding journal entries, eliminating manual bookkeeping and reducing the risk of human error.</p>'
                    . '<h2>Manual Journal Vouchers</h2>'
                    . '<p>For entries that fall outside standard operations, such as depreciation or accruals, accountants can create manual journal vouchers with full support for multi-line debits and credits that must balance before saving.</p>'
                    . '<h2>Bank and Cash Accounts</h2>'
                    . '<p>Track multiple bank and cash accounts, record transfers between them, and reconcile against bank statements to catch discrepancies early.</p>'
                    . '<h2>Financial Statements</h2>'
                    . '<p>Generate a trial balance, income statement, and balance sheet for any date range with a single click. Reports can be filtered by branch or cost center for businesses that need departmental visibility.</p>'
                    . '<h2>Taxes</h2>'
                    . '<p>Configure VAT or other applicable taxes per product or category, and generate tax reports formatted for submission to local tax authorities.</p>'
                    . '<h2>Cost Centers and Budgets</h2>'
                    . '<p>Assign transactions to cost centers to track profitability by department, project, or branch, and set budgets to monitor actual spend against plan throughout the year.</p>',
                'content_ar' => '<p>يتضمن محاسب محرك محاسبة كامل بنظام القيد المزدوج يعمل بهدوء في الخلفية، منشئاً تلقائياً القيود اليومية من كل عملية بيع وشراء ودفع وحركة مخزون في نشاطك التجاري.</p>'
                    . '<h2>دليل الحسابات</h2>'
                    . '<p>يغطي دليل الحسابات المُعد مسبقاً الأصول والخصوم وحقوق الملكية والإيرادات والمصروفات، ويمكن تخصيصه بالكامل ليتوافق مع المعايير المحاسبية المحلية أو تفضيلات محاسبك. يمكنك إضافة الحسابات أو إعادة تسميتها أو إعادة تنظيمها في أي وقت.</p>'
                    . '<h2>القيود اليومية التلقائية</h2>'
                    . '<p>تُنشئ كل عملية بيع وفاتورة وشراء ودفعة وتسوية مخزون القيود اليومية المقابلة تلقائياً، مما يلغي مسك الدفاتر اليدوي ويقلل من مخاطر الخطأ البشري.</p>'
                    . '<h2>سندات القيد اليدوية</h2>'
                    . '<p>بالنسبة للقيود التي تقع خارج العمليات القياسية، مثل الإهلاك أو المستحقات، يمكن للمحاسبين إنشاء سندات قيد يدوية مع دعم كامل للمدين والدائن متعدد الأسطر التي يجب أن تتوازن قبل الحفظ.</p>'
                    . '<h2>الحسابات البنكية والنقدية</h2>'
                    . '<p>تتبع عدة حسابات بنكية ونقدية، سجل التحويلات بينها، وقم بالتسوية مقابل كشوف الحسابات البنكية لاكتشاف الفروقات مبكراً.</p>'
                    . '<h2>القوائم المالية</h2>'
                    . '<p>أنشئ ميزان المراجعة وقائمة الدخل والميزانية العمومية لأي فترة زمنية بنقرة واحدة. يمكن تصفية التقارير حسب الفرع أو مركز التكلفة للشركات التي تحتاج رؤية على مستوى الأقسام.</p>'
                    . '<h2>الضرائب</h2>'
                    . '<p>قم بإعداد ضريبة القيمة المضافة أو أي ضرائب أخرى سارية لكل منتج أو فئة، وأنشئ تقارير ضريبية بصيغة جاهزة للتقديم إلى مصلحة الضرائب المحلية.</p>'
                    . '<h2>مراكز التكلفة والموازنات</h2>'
                    . '<p>خصص المعاملات لمراكز التكلفة لتتبع الربحية حسب القسم أو المشروع أو الفرع، وحدد الموازنات لمراقبة الإنفاق الفعلي مقابل الخطة على مدار العام.</p>',
            ],
            [
                'slug' => 'docs-hrm',
                'section' => 'hrm',
                'sort_order' => 1,
                'title_en' => 'Human Resources (HRM)',
                'title_ar' => 'الموارد البشرية',
                'short_description_en' => 'Manage employees, attendance, payroll, and leave requests from one central place.',
                'short_description_ar' => 'إدارة الموظفين والحضور والرواتب وطلبات الإجازة من مكان مركزي واحد.',
                'content_en' => '<p>The HRM module in Mohaaseb helps growing businesses manage their most important asset — their people — without needing a separate HR system.</p>'
                    . '<h2>Employee Records</h2>'
                    . '<p>Maintain a complete profile for every employee, including contact details, job title, department, branch assignment, salary structure, and document uploads such as national ID and contracts. Employee records can be linked to a user login for self-service access.</p>'
                    . '<h2>Attendance Tracking</h2>'
                    . '<p>Employees can check in and out through the web portal, a mobile device, or a connected biometric device. Attendance data feeds directly into payroll calculations, and managers can review daily attendance summaries by branch or department.</p>'
                    . '<h2>Leave Management</h2>'
                    . '<p>Employees submit leave requests through their self-service portal, which route to their manager for approval. Configure leave types, annual entitlements, and carry-over rules to match your company policy.</p>'
                    . '<h2>Payroll Processing</h2>'
                    . '<p>Run payroll for all employees or a selected group with a few clicks. Mohaaseb automatically factors in attendance, overtime, deductions, bonuses, and any advances or loans, producing a detailed payslip for each employee and the corresponding accounting entries.</p>'
                    . '<h2>Loans and Advances</h2>'
                    . '<p>Record employee loans and salary advances, with automatic installment deductions applied during subsequent payroll runs until the balance is settled.</p>'
                    . '<h2>Performance and Documents</h2>'
                    . '<p>Store performance reviews, warnings, and important documents against each employee record, keeping a complete history accessible to HR administrators.</p>'
                    . '<h2>HR Reports</h2>'
                    . '<p>Generate headcount, turnover, attendance, and payroll cost reports to support workforce planning and budgeting decisions.</p>',
                'content_ar' => '<p>تساعد وحدة الموارد البشرية في محاسب الشركات النامية على إدارة أهم أصولها — موظفيها — دون الحاجة إلى نظام موارد بشرية منفصل.</p>'
                    . '<h2>سجلات الموظفين</h2>'
                    . '<p>احتفظ بملف كامل لكل موظف، يشمل بيانات الاتصال والمسمى الوظيفي والقسم والفرع المخصص وهيكل الراتب وتحميل المستندات مثل بطاقة الرقم القومي والعقود. يمكن ربط سجلات الموظفين بحساب مستخدم للوصول الذاتي.</p>'
                    . '<h2>تتبع الحضور</h2>'
                    . '<p>يمكن للموظفين تسجيل الحضور والانصراف عبر بوابة الويب أو الهاتف المحمول أو جهاز البصمة المتصل. تُغذي بيانات الحضور حسابات الرواتب مباشرة، ويمكن للمديرين مراجعة ملخصات الحضور اليومية حسب الفرع أو القسم.</p>'
                    . '<h2>إدارة الإجازات</h2>'
                    . '<p>يقدم الموظفون طلبات الإجازة عبر بوابة الخدمة الذاتية، والتي تُوجَّه إلى مديرهم للموافقة. قم بإعداد أنواع الإجازات والاستحقاقات السنوية وقواعد الترحيل لتتوافق مع سياسة شركتك.</p>'
                    . '<h2>معالجة الرواتب</h2>'
                    . '<p>شغّل الرواتب لجميع الموظفين أو مجموعة مختارة ببضع نقرات. يأخذ محاسب تلقائياً في الاعتبار الحضور والعمل الإضافي والخصومات والمكافآت وأي سلف أو قروض، منتجاً كشف راتب مفصل لكل موظف والقيود المحاسبية المرتبطة به.</p>'
                    . '<h2>السلف والقروض</h2>'
                    . '<p>سجل قروض الموظفين والسلف على الراتب، مع خصم أقساط تلقائي يُطبق خلال دورات الرواتب اللاحقة حتى يتم تسوية الرصيد.</p>'
                    . '<h2>الأداء والمستندات</h2>'
                    . '<p>خزّن تقييمات الأداء والإنذارات والمستندات المهمة مقابل سجل كل موظف، مع الاحتفاظ بسجل تاريخي كامل يمكن لمسؤولي الموارد البشرية الوصول إليه.</p>'
                    . '<h2>تقارير الموارد البشرية</h2>'
                    . '<p>أنشئ تقارير عدد الموظفين ومعدل الدوران والحضور وتكلفة الرواتب لدعم قرارات تخطيط القوى العاملة والميزانية.</p>',
            ],
            [
                'slug' => 'docs-settings-configuration',
                'section' => 'settings',
                'sort_order' => 1,
                'title_en' => 'Settings & Configuration',
                'title_ar' => 'الإعدادات والتهيئة',
                'short_description_en' => 'Configure branches, taxes, users, permissions, and integrations for your Mohaaseb workspace.',
                'short_description_ar' => 'إعداد الفروع والضرائب والمستخدمين والصلاحيات والتكاملات لمساحة عمل محاسب.',
                'content_en' => '<p>The Settings area is where you shape Mohaaseb to fit your business exactly the way you operate. This page summarizes the key configuration areas every administrator should review.</p>'
                    . '<h2>Company and Branding</h2>'
                    . '<p>Update your company name, logo, tax registration number, and default currency. These settings drive the appearance of invoices, receipts, and reports throughout the system.</p>'
                    . '<h2>Branches and Warehouses</h2>'
                    . '<p>Add or edit branches, assign warehouses to each, and configure branch-specific settings such as receipt printer templates and default price lists.</p>'
                    . '<h2>Users, Roles, and Permissions</h2>'
                    . '<p>Create custom roles with granular permissions across every module, from POS access to financial reports. Assign users to one or more branches, restricting what data they can see and edit.</p>'
                    . '<h2>Taxes and Payment Methods</h2>'
                    . '<p>Define tax rates and rules per product category, and configure the payment methods available at checkout, including cash, cards, wallets, and custom gateways.</p>'
                    . '<h2>Notifications</h2>'
                    . '<p>Configure email and WhatsApp notifications for events such as low stock alerts, new orders, or overdue invoices, keeping the right people informed automatically.</p>'
                    . '<h2>Integrations</h2>'
                    . '<p>Connect Mohaaseb with third-party services such as payment gateways, shipping providers, and accounting export tools through the Integrations panel.</p>'
                    . '<h2>Backups and Data Export</h2>'
                    . '<p>Schedule automatic backups of your tenant database and export any report or dataset to Excel or PDF for offline analysis or sharing with your accountant.</p>'
                    . '<h2>Localization</h2>'
                    . '<p>Switch the interface language between Arabic and English at any time, and configure date formats, number formats, and default locale per user.</p>',
                'content_ar' => '<p>منطقة الإعدادات هي المكان الذي تُشكّل فيه محاسب ليتناسب تماماً مع طريقة عمل نشاطك التجاري. تلخص هذه الصفحة أهم مجالات الإعداد التي يجب على كل مسؤول مراجعتها.</p>'
                    . '<h2>الشركة والهوية البصرية</h2>'
                    . '<p>حدّث اسم شركتك وشعارها ورقم التسجيل الضريبي والعملة الافتراضية. تحدد هذه الإعدادات شكل الفواتير والإيصالات والتقارير في جميع أنحاء النظام.</p>'
                    . '<h2>الفروع والمستودعات</h2>'
                    . '<p>أضف أو عدّل الفروع، وخصص مستودعات لكل منها، وقم بإعداد إعدادات خاصة بالفرع مثل قوالب طابعة الإيصالات وقوائم الأسعار الافتراضية.</p>'
                    . '<h2>المستخدمون والأدوار والصلاحيات</h2>'
                    . '<p>أنشئ أدواراً مخصصة بصلاحيات دقيقة عبر كل وحدة، من الوصول إلى نقطة البيع إلى التقارير المالية. خصص المستخدمين لفرع واحد أو أكثر، مقيداً البيانات التي يمكنهم رؤيتها وتعديلها.</p>'
                    . '<h2>الضرائب وطرق الدفع</h2>'
                    . '<p>حدد نسب وقواعد الضرائب لكل فئة منتج، وقم بإعداد طرق الدفع المتاحة عند إتمام الشراء، بما في ذلك النقد والبطاقات والمحافظ الإلكترونية وبوابات الدفع المخصصة.</p>'
                    . '<h2>الإشعارات</h2>'
                    . '<p>قم بإعداد إشعارات البريد الإلكتروني وواتساب لأحداث مثل تنبيهات انخفاض المخزون أو الطلبات الجديدة أو الفواتير المتأخرة، مما يبقي الأشخاص المناسبين على اطلاع تلقائياً.</p>'
                    . '<h2>التكاملات</h2>'
                    . '<p>اربط محاسب بخدمات خارجية مثل بوابات الدفع وشركات الشحن وأدوات تصدير المحاسبة من خلال لوحة التكاملات.</p>'
                    . '<h2>النسخ الاحتياطي وتصدير البيانات</h2>'
                    . '<p>جدول نسخاً احتياطية تلقائية لقاعدة بيانات شركتك، وصدّر أي تقرير أو مجموعة بيانات إلى إكسل أو PDF للتحليل دون اتصال أو مشاركتها مع محاسبك.</p>'
                    . '<h2>التوطين</h2>'
                    . '<p>بدّل لغة الواجهة بين العربية والإنجليزية في أي وقت، وقم بإعداد صيغ التاريخ والأرقام واللغة الافتراضية لكل مستخدم.</p>',
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title_en' => $data['title_en'],
                    'title_ar' => $data['title_ar'],
                    'short_description_en' => $data['short_description_en'],
                    'short_description_ar' => $data['short_description_ar'],
                    'content_en' => $data['content_en'],
                    'content_ar' => $data['content_ar'],
                    'is_published' => true,
                    'page_type' => 'documentation',
                    'section' => $data['section'],
                    'sort_order' => $data['sort_order'],
                ]
            );
        }
    }
}
