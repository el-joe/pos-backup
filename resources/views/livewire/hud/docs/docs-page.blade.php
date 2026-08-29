@php
    $isAr = app()->getLocale() === 'ar';

    $nav = [
        'overview' => $isAr ? 'نظرة عامة' : 'Overview',
        'pos' => $isAr ? 'نقطة البيع' : 'POS',
        'products' => $isAr ? 'المنتجات والمخزون' : 'Products & Inventory',
        'sales' => $isAr ? 'المبيعات' : 'Sales',
        'purchases' => $isAr ? 'المشتريات' : 'Purchases',
        'accounting' => $isAr ? 'الحسابات' : 'Accounting',
        'hrm' => $isAr ? 'الموارد البشرية' : 'HRM',
        'contracting' => $isAr ? 'إدارة المقاولات' : 'Contracting',
        'settings' => $isAr ? 'الإعدادات' : 'Settings',
        'api' => $isAr ? 'واجهة برمجة التطبيقات' : 'API',
    ];

    $docs = [
        'overview' => [
            'title' => $isAr ? 'نظرة عامة على النظام' : 'System Overview',
            'what' => $isAr
                ? 'محاسب هو نظام متكامل لإدارة الأعمال يجمع بين نقطة البيع، المخزون، المحاسبة، والموارد البشرية في مكان واحد.'
                : 'Mohaaseb is an all-in-one business management system combining POS, inventory, accounting, and HR in a single platform.',
            'how' => $isAr
                ? ['سجّل الدخول من صفحة تسجيل الدخول الخاصة بمتجرك.', 'راجع لوحة التحكم للحصول على نظرة سريعة على أداء عملك.', 'استخدم القائمة الجانبية للتنقل بين الأقسام المختلفة.']
                : ['Log in from your store\'s login page.', 'Check the dashboard for a quick overview of your business performance.', 'Use the sidebar to navigate between the different sections.'],
            'fields' => [],
            'workflows' => $isAr ? ['إعداد الحساب لأول مرة ثم بدء البيع.'] : ['First-time account setup, then start selling.'],
            'tips' => $isAr ? ['أكمل قائمة الإعداد الظاهرة في لوحة التحكم للحصول على أفضل تجربة.'] : ['Complete the setup checklist shown on the dashboard for the best experience.'],
        ],
        'pos' => [
            'title' => $isAr ? 'نقطة البيع (POS)' : 'Point of Sale (POS)',
            'what' => $isAr
                ? 'شاشة بيع سريعة تعمل باللمس لإتمام عمليات البيع في المتجر خلال ثوانٍ.'
                : 'A fast, touch-friendly screen for completing in-store sales in seconds.',
            'how' => $isAr
                ? ['اختر المنتجات من الشبكة أو ابحث عنها بالاسم أو الباركود.', 'اختر العميل (اختياري) من القائمة.', 'حدد طريقة الدفع (نقدي، بطاقة، آجل).', 'اضغط على "تأكيد" لإتمام عملية البيع وطباعة الفاتورة.']
                : ['Select products from the grid or search by name/barcode.', 'Choose a customer (optional) from the list.', 'Pick a payment method (cash, card, deferred).', 'Press "Confirm" to complete the sale and print the invoice.'],
            'fields' => $isAr
                ? ['السلة: المنتجات المختارة والكميات.', 'الخصم: نسبة أو مبلغ ثابت على الفاتورة.', 'الدفع الآجل: يسجل المبلغ كمديونية على العميل.']
                : ['Cart: the selected products and quantities.', 'Discount: a percentage or fixed amount on the invoice.', 'Deferred payment: records the amount as customer debt.'],
            'workflows' => $isAr ? ['بيع سريع لعميل عابر.', 'بيع آجل لعميل مسجل مع تحصيل لاحق.'] : ['Quick sale to a walk-in customer.', 'Deferred sale to a registered customer, collected later.'],
            'tips' => $isAr ? ['استخدم قارئ الباركود لتسريع عملية البيع.'] : ['Use a barcode scanner to speed up checkout.'],
        ],
        'products' => [
            'title' => $isAr ? 'المنتجات والمخزون' : 'Products & Inventory',
            'what' => $isAr
                ? 'إدارة كتالوج المنتجات، الأسعار، والفئات، مع تتبع تلقائي لكميات المخزون.'
                : 'Manage your product catalog, prices, and categories with automatic stock tracking.',
            'how' => $isAr
                ? ['اذهب إلى "المنتجات" ثم اضغط "إضافة منتج".', 'أدخل الاسم، السعر، الفئة، والعلامة التجارية.', 'حدد كمية التنبيه لتلقي إشعار عند انخفاض المخزون.', 'احفظ المنتج.']
                : ['Go to "Products" and click "Add Product".', 'Enter the name, price, category, and brand.', 'Set the alert quantity to be notified when stock runs low.', 'Save the product.'],
            'fields' => $isAr
                ? ['سعر البيع / سعر الشراء.', 'كمية التنبيه: الحد الأدنى قبل التنبيه بنقص المخزون.', 'الوحدة: مثل قطعة، كجم، لتر.']
                : ['Sell price / cost price.', 'Alert quantity: the threshold before a low-stock warning.', 'Unit: e.g. piece, kg, liter.'],
            'workflows' => $isAr ? ['إضافة منتج جديد ثم شراء كمية أولية.', 'مراقبة المنتجات منخفضة المخزون من لوحة التحكم.'] : ['Add a new product then purchase an initial quantity.', 'Monitor low-stock products from the dashboard.'],
            'tips' => $isAr ? ['استخدم الاستيراد الجماعي لإضافة عدد كبير من المنتجات دفعة واحدة.'] : ['Use bulk import to add many products at once.'],
        ],
        'sales' => [
            'title' => $isAr ? 'المبيعات' : 'Sales',
            'what' => $isAr ? 'سجل كامل لكل عمليات البيع مع تفاصيل الدفع والمرتجعات.' : 'A complete record of every sale, including payments and refunds.',
            'how' => $isAr
                ? ['اذهب إلى "المبيعات" لعرض جميع الفواتير.', 'اضغط على أي فاتورة لعرض تفاصيلها.', 'يمكنك تسجيل دفعة إضافية أو عمل مرتجع من صفحة التفاصيل.']
                : ['Go to "Sales" to view all invoices.', 'Click any invoice to view its details.', 'You can record an additional payment or process a refund from the details page.'],
            'fields' => $isAr ? ['رقم الفاتورة.', 'المبلغ المستحق (الآجل).'] : ['Invoice number.', 'Due amount (deferred balance).'],
            'workflows' => $isAr ? ['متابعة الفواتير الآجلة وتحصيلها.'] : ['Following up on deferred invoices and collecting payment.'],
            'tips' => $isAr ? ['استخدم الفلاتر بالتاريخ والفرع لتحليل المبيعات.'] : ['Use the date and branch filters to analyze sales.'],
        ],
        'purchases' => [
            'title' => $isAr ? 'المشتريات' : 'Purchases',
            'what' => $isAr ? 'تسجيل المشتريات من الموردين وتحديث المخزون تلقائياً.' : 'Record purchases from suppliers with automatic stock updates.',
            'how' => $isAr
                ? ['اذهب إلى "المشتريات" ثم "إضافة مشترى".', 'اختر المورد والمنتجات والكميات.', 'سجّل الدفعة (كاملة أو جزئية).']
                : ['Go to "Purchases" then "Add Purchase".', 'Select the supplier, products, and quantities.', 'Record the payment (full or partial).'],
            'fields' => $isAr ? ['رقم المرجع.', 'المبلغ المستحق للمورد.'] : ['Reference number.', 'Amount due to supplier.'],
            'workflows' => $isAr ? ['شراء بضاعة جديدة وتحديث المخزون.'] : ['Purchasing new stock and updating inventory.'],
            'tips' => $isAr ? ['راجع تقرير المشتريات لمتابعة المدفوعات المستحقة للموردين.'] : ['Check the purchases report to track amounts owed to suppliers.'],
        ],
        'accounting' => [
            'title' => $isAr ? 'الحسابات' : 'Accounting',
            'what' => $isAr
                ? 'نظام محاسبة بالقيد المزدوج يسجل كل معاملة مالية تلقائياً.'
                : 'A double-entry accounting system that automatically records every financial transaction.',
            'how' => $isAr
                ? ['راجع "الحسابات" لعرض شجرة الحسابات.', 'اذهب إلى "التقارير المالية" لاستخراج قائمة الدخل والميزانية العمومية وميزان المراجعة.']
                : ['Review "Accounts" to see the chart of accounts.', 'Go to "Financial Reports" to generate the income statement, balance sheet, and trial balance.'],
            'fields' => $isAr ? ['نوع الحساب: أصول، خصوم، إيرادات، مصروفات.'] : ['Account type: asset, liability, revenue, expense.'],
            'workflows' => $isAr ? ['مراجعة الأرباح والخسائر شهرياً.'] : ['Reviewing profit & loss on a monthly basis.'],
            'tips' => $isAr ? ['لا تحذف حساباً له حركات مسجلة عليه.'] : ['Do not delete an account that already has recorded transactions.'],
        ],
        'hrm' => [
            'title' => $isAr ? 'الموارد البشرية' : 'Human Resources',
            'what' => $isAr ? 'إدارة الموظفين، الحضور والانصراف، الرواتب، والإجازات.' : 'Manage employees, attendance, payroll, and leaves.',
            'how' => $isAr
                ? ['أضف الأقسام والمسميات الوظيفية أولاً من "البيانات الأساسية".', 'أضف الموظفين وحدد رواتبهم.', 'سجّل الحضور يومياً أو استورده.', 'شغّل دورة الرواتب الشهرية.']
                : ['Set up departments and designations first under "Master Data".', 'Add employees and set their salaries.', 'Record attendance daily or import it.', 'Run the monthly payroll cycle.'],
            'fields' => $isAr ? ['نوع العقد.', 'رصيد الإجازات.'] : ['Contract type.', 'Leave balance.'],
            'workflows' => $isAr ? ['طلب إجازة موظف ثم موافقة المدير.'] : ['An employee requests leave, then a manager approves it.'],
            'tips' => $isAr ? ['راجع صلاحيات الموظفين قبل منحهم وصولاً للنظام.'] : ['Review employee permissions before granting them system access.'],
        ],
        'contracting' => [
            'title' => $isAr ? 'إدارة المقاولات' : 'Contracting',
            'what' => $isAr
                ? 'إدارة المناقصات والعقود والمشاريع ومستخلصات المقاولات.'
                : 'Manage tenders, contracts, projects, and contracting extracts.',
            'how' => $isAr
                ? ['ابدأ بإنشاء مناقصة أو مشروع.', 'أضف جدول الكميات (BOQ) والعمال والمعدات.', 'أصدر مستخلصات دورية لمتابعة تقدم العمل.']
                : ['Start by creating a tender or project.', 'Add the bill of quantities (BOQ), workers, and equipment.', 'Issue periodic extracts to track work progress.'],
            'fields' => $isAr ? ['جدول الكميات (BOQ).', 'مركز التكلفة.'] : ['Bill of Quantities (BOQ).', 'Cost center.'],
            'workflows' => $isAr ? ['من المناقصة إلى العقد ثم المشروع والمستخلصات.'] : ['From tender to contract, then project and extracts.'],
            'tips' => $isAr ? ['اربط كل مصروف بمركز التكلفة الصحيح لتحليل دقيق.'] : ['Tie every expense to the correct cost center for accurate analysis.'],
        ],
        'settings' => [
            'title' => $isAr ? 'الإعدادات' : 'Settings',
            'what' => $isAr ? 'إعداد بيانات نشاطك التجاري، الشعار، العملة، والضرائب.' : 'Configure your business info, logo, currency, and tax settings.',
            'how' => $isAr
                ? ['اذهب إلى "الإعدادات".', 'أدخل اسم النشاط، الشعار، الرقم الضريبي.', 'اضبط طرق الدفع والفروع.']
                : ['Go to "Settings".', 'Enter the business name, logo, and tax number.', 'Configure payment methods and branches.'],
            'fields' => $isAr ? ['العملة الافتراضية.', 'الرقم الضريبي.'] : ['Default currency.', 'Tax number.'],
            'workflows' => $isAr ? ['إعداد النظام لأول مرة قبل بدء البيع.'] : ['Initial system setup before you start selling.'],
            'tips' => $isAr ? ['أكمل جميع الإعدادات الأساسية قبل تفعيل نقطة البيع.'] : ['Complete all core settings before activating the POS.'],
        ],
        'api' => [
            'title' => $isAr ? 'واجهة برمجة التطبيقات (API)' : 'API',
            'what' => $isAr ? 'وصول برمجي للبيانات لدمج محاسب مع أنظمة أخرى.' : 'Programmatic access to your data for integrating Mohaaseb with other systems.',
            'how' => $isAr
                ? ['أنشئ رمز API الخاص بك من صفحة الحساب.', 'أرسل الرمز في ترويسة Authorization كـ Bearer Token.', 'استخدم نقاط النهاية الموثقة للوصول إلى المنتجات والمبيعات.']
                : ['Generate your API token from your account page.', 'Send the token in the Authorization header as a Bearer Token.', 'Use the documented endpoints to access products and sales.'],
            'fields' => $isAr ? ['رمز API.'] : ['API token.'],
            'workflows' => $isAr ? ['دمج نقطة بيع خارجية مع النظام عبر الـ API.'] : ['Integrating an external POS device with the system via the API.'],
            'tips' => $isAr ? ['لا تشارك رمز الـ API مع أي طرف غير موثوق.'] : ['Never share your API token with an untrusted party.'],
        ],
    ];

    $current = $docs[$section] ?? $docs['overview'];
@endphp

<div x-data="{ q: '' }">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <input type="text" x-model="q" class="form-control form-control-sm mb-3"
                           placeholder="{{ $isAr ? 'بحث في التوثيق...' : 'Search documentation...' }}">
                    <ul class="list-unstyled mb-0">
                        @foreach($nav as $key => $label)
                            <li x-show="q === '' || '{{ strtolower($label) }}'.includes(q.toLowerCase())" class="mb-1">
                                <a href="javascript:void(0)" wire:click="selectSection('{{ $key }}')"
                                   class="d-block px-2 py-1 rounded {{ $section === $key ? 'bg-primary text-white' : '' }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">{{ $current['title'] }}</h4>

                    <h6 class="text-muted mb-1">{{ $isAr ? 'ما هو؟' : 'What is it?' }}</h6>
                    <p>{{ $current['what'] }}</p>

                    @if(!empty($current['how']))
                        <h6 class="text-muted mb-1">{{ $isAr ? 'كيفية الاستخدام' : 'How to use it' }}</h6>
                        <ol>
                            @foreach($current['how'] as $step)
                                <li>{{ $step }}</li>
                            @endforeach
                        </ol>
                    @endif

                    @if(!empty($current['fields']))
                        <h6 class="text-muted mb-1">{{ $isAr ? 'الحقول الرئيسية' : 'Key fields' }}</h6>
                        <ul>
                            @foreach($current['fields'] as $field)
                                <li>{{ $field }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!empty($current['workflows']))
                        <h6 class="text-muted mb-1">{{ $isAr ? 'سيناريوهات شائعة' : 'Common workflows' }}</h6>
                        <ul>
                            @foreach($current['workflows'] as $workflow)
                                <li>{{ $workflow }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!empty($current['tips']))
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>{{ $isAr ? 'نصيحة' : 'Tip' }}:</strong>
                            {{ implode(' ', $current['tips']) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
