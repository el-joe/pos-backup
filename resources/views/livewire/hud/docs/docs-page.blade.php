@php
    $isAr = app()->getLocale() === 'ar';

    $nav = [
        'overview' => $isAr ? 'نظرة عامة' : 'Overview',
        'pos' => $isAr ? 'نقطة البيع' : 'POS',
        'products' => $isAr ? 'المنتجات والمخزون' : 'Products & Inventory',
        'sales' => $isAr ? 'المبيعات' : 'Sales',
        'purchases' => $isAr ? 'المشتريات' : 'Purchases',
        'refunds' => $isAr ? 'مرتجعات المبيعات' : 'Refunds',
        'purchase-requests' => $isAr ? 'طلبات الشراء الداخلية' : 'Purchase Requests',
        'sale-requests' => $isAr ? 'عروض الأسعار' : 'Sale Requests (Quotations)',
        'users' => $isAr ? 'العملاء والموردون' : 'Customers & Suppliers',
        'expenses' => $isAr ? 'المصروفات' : 'Expenses',
        'discounts' => $isAr ? 'الخصومات' : 'Discounts',
        'accounting' => $isAr ? 'الحسابات' : 'Accounting',
        'fixed-assets' => $isAr ? 'الأصول الثابتة' : 'Fixed Assets',
        'depreciation' => $isAr ? 'استهلاك الأصول' : 'Depreciation',
        'reports' => $isAr ? 'التقارير' : 'Reports',
        'stock' => $isAr ? 'المخزون والجرد' : 'Stock & Inventory',
        'checks' => $isAr ? 'الشيكات' : 'Checks',
        'transactions' => $isAr ? 'حركات الحسابات' : 'Transactions',
        'hrm' => $isAr ? 'الموارد البشرية' : 'HRM',
        'contracting' => $isAr ? 'إدارة المقاولات' : 'Contracting',
        'branches' => $isAr ? 'الفروع' : 'Branches',
        'taxes' => $isAr ? 'الضرائب' : 'Taxes',
        'payment-methods' => $isAr ? 'طرق الدفع' : 'Payment Methods',
        'accounts' => $isAr ? 'شجرة الحسابات' : 'Chart of Accounts',
        'admins' => $isAr ? 'المستخدمون والصلاحيات' : 'Admins & Roles',
        'roles' => $isAr ? 'الأدوار' : 'Roles',
        'subscriptions' => $isAr ? 'الاشتراكات والخطط' : 'Subscriptions & Plans',
        'imports' => $isAr ? 'استيراد البيانات' : 'Data Import',
        'notifications' => $isAr ? 'الإشعارات' : 'Notifications',
        'employee-portal' => $isAr ? 'بوابة الموظف' : 'Employee Portal',
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
        'refunds' => [
            'title' => $isAr ? 'مرتجعات المبيعات' : 'Refunds',
            'what' => $isAr
                ? 'تسجيل المنتجات المرتجعة من العملاء، مع تحديث المخزون والقيود المحاسبية تلقائياً عند كل عملية إرجاع.'
                : 'Register products returned by customers; stock and accounting entries are updated automatically with every refund.',
            'how' => $isAr
                ? ['اذهب إلى "المرتجعات" ثم اضغط "إضافة مرتجع".', 'اختر الفاتورة الأصلية أو ابحث عنها برقمها.', 'حدد المنتجات والكميات المراد إرجاعها.', 'اختر طريقة استرداد المبلغ (نقدي أو كرصيد للعميل) واحفظ.']
                : ['Go to "Refunds" and click "Add Refund".', 'Select the original invoice or search for it by number.', 'Choose the products and quantities to return.', 'Choose the refund method (cash or customer credit) and save.'],
            'fields' => $isAr
                ? ['الفاتورة المرجعية: رقم فاتورة البيع الأصلية.', 'كمية الإرجاع: لا يمكن أن تتجاوز الكمية المباعة.', 'طريقة الاسترداد: نقدي أو رصيد يُخصم من مديونية العميل.']
                : ['Reference invoice: the original sale invoice number.', 'Refund quantity: cannot exceed the sold quantity.', 'Refund method: cash or a credit applied against the customer\'s balance.'],
            'workflows' => $isAr
                ? ['عميل يعيد منتجاً تالفاً فيتم إرجاع المبلغ نقداً وإعادة الكمية للمخزون.', 'إرجاع جزئي لفاتورة آجلة يُخصم مباشرة من رصيد العميل المستحق.']
                : ['A customer returns a defective item; the amount is refunded in cash and the quantity is restocked.', 'A partial refund on a deferred invoice is deducted directly from the customer\'s outstanding balance.'],
            'tips' => $isAr ? ['راجع سبب الإرجاع دائماً لتحسين جودة المنتجات ومتابعة الموردين.'] : ['Always review the return reason to improve product quality and follow up with suppliers.'],
        ],
        'purchase-requests' => [
            'title' => $isAr ? 'طلبات الشراء الداخلية' : 'Purchase Requests',
            'what' => $isAr
                ? 'طلبات داخلية يقدمها الموظفون أو مسؤولو الفروع لشراء أصناف معينة، تمر بمسار موافقة قبل تحويلها إلى أمر شراء فعلي.'
                : 'Internal requests submitted by employees or branch managers to purchase specific items, going through an approval workflow before becoming an actual purchase order.',
            'how' => $isAr
                ? ['اذهب إلى "طلبات الشراء" ثم "طلب جديد".', 'حدد الفرع، المنتجات، والكميات المطلوبة.', 'أرسل الطلب لينتظر موافقة المخول بالاعتماد.', 'بعد الموافقة، حوّل الطلب إلى أمر شراء فعلي من نفس الشاشة.']
                : ['Go to "Purchase Requests" then "New Request".', 'Specify the branch, products, and quantities needed.', 'Submit the request to await approval from the authorized approver.', 'Once approved, convert the request into an actual purchase order from the same screen.'],
            'fields' => $isAr
                ? ['الحالة: قيد المراجعة، معتمد، مرفوض، أو مُحوَّل لأمر شراء.', 'ملاحظات الطلب: سبب أو أولوية الطلب.']
                : ['Status: pending, approved, rejected, or converted to a purchase order.', 'Request notes: the reason or priority of the request.'],
            'workflows' => $isAr
                ? ['مسؤول فرع يطلب توريد أصناف نفدت من المخزون، يوافق عليها المدير، ثم تتحول تلقائياً لأمر شراء من المورد المناسب.']
                : ['A branch manager requests items that ran out of stock; a manager approves it, then it is converted into a purchase order with the right supplier.'],
            'tips' => $isAr ? ['استخدم طلبات الشراء لضبط الصلاحيات، بحيث لا يشتري الموظفون مباشرة دون اعتماد.'] : ['Use purchase requests to enforce approval controls so employees cannot buy directly without authorization.'],
        ],
        'sale-requests' => [
            'title' => $isAr ? 'عروض الأسعار' : 'Sale Requests (Quotations)',
            'what' => $isAr
                ? 'عروض أسعار تُرسل للعملاء قبل إتمام عملية البيع الفعلية، ويمكن تحويلها لاحقاً إلى فاتورة بيع حقيقية بضغطة واحدة.'
                : 'Quotations sent to customers before completing an actual sale; they can later be converted into a real sales invoice with a single click.',
            'how' => $isAr
                ? ['اذهب إلى "عروض الأسعار" ثم "عرض جديد".', 'اختر العميل والمنتجات والأسعار والخصومات المقترحة.', 'أرسل أو اطبع العرض لمشاركته مع العميل.', 'عند موافقة العميل، حوّل العرض إلى فاتورة بيع مباشرة.']
                : ['Go to "Sale Requests" then "New Quotation".', 'Select the customer, products, prices, and proposed discounts.', 'Send or print the quotation to share with the customer.', 'Once the customer agrees, convert the quotation directly into a sales invoice.'],
            'fields' => $isAr
                ? ['تاريخ انتهاء الصلاحية: المدة التي يبقى فيها العرض سارياً.', 'الحالة: قيد الانتظار، مقبول، مرفوض، أو محوَّل لفاتورة.']
                : ['Expiry date: how long the quotation remains valid.', 'Status: pending, accepted, rejected, or converted to an invoice.'],
            'workflows' => $isAr
                ? ['إرسال عرض سعر لعميل محتمل، ثم تحويله إلى فاتورة بيع فور الموافقة دون إعادة إدخال البيانات.']
                : ['Sending a quotation to a prospective customer, then converting it to a sales invoice upon approval without re-entering the data.'],
            'tips' => $isAr ? ['حدد تاريخ انتهاء واضحاً للعرض لتفادي الالتزام بأسعار قديمة.'] : ['Set a clear expiry date on the quotation to avoid being bound by outdated prices.'],
        ],
        'users' => [
            'title' => $isAr ? 'العملاء والموردون' : 'Customers & Suppliers',
            'what' => $isAr
                ? 'قائمة موحدة لجميع العملاء والموردين، تعرض أرصدتهم الحالية وتتيح تسجيل دفعات على المبالغ المستحقة.'
                : 'A unified list of all customers and suppliers, showing their current balances and allowing payments to be recorded against outstanding amounts.',
            'how' => $isAr
                ? ['اذهب إلى "العملاء" أو "الموردون" حسب الحاجة.', 'اضغط على أي اسم لعرض سجل عملياته ورصيده الحالي.', 'اضغط "تسجيل دفعة" لتحصيل أو سداد مبلغ من الرصيد المستحق.']
                : ['Go to "Customers" or "Suppliers" as needed.', 'Click any name to view their transaction history and current balance.', 'Click "Record Payment" to collect or settle an amount against the outstanding balance.'],
            'fields' => $isAr
                ? ['الرصيد: صافي المستحق للعميل أو عليه (أو للمورد أو عليه).', 'بيانات التواصل: الاسم، الهاتف، البريد الإلكتروني، العنوان.']
                : ['Balance: the net amount owed to/by the customer (or supplier).', 'Contact info: name, phone, email, address.'],
            'workflows' => $isAr
                ? ['متابعة عميل لديه فواتير آجلة وتحصيل دفعة جزئية منه.', 'تسوية رصيد مستحق لمورد بعد استلام بضاعة.']
                : ['Following up with a customer who has deferred invoices and collecting a partial payment.', 'Settling a balance owed to a supplier after receiving goods.'],
            'tips' => $isAr ? ['راجع تقرير الأرصدة دورياً لمتابعة العملاء والموردين ذوي المبالغ الكبيرة المستحقة.'] : ['Review the balances report periodically to track customers and suppliers with large outstanding amounts.'],
        ],
        'expenses' => [
            'title' => $isAr ? 'المصروفات' : 'Expenses',
            'what' => $isAr
                ? 'تسجيل التكاليف التشغيلية للنشاط، مرتبطة بفئات مصروفات وفروع محددة، مع ترحيل تلقائي للقيود المحاسبية.'
                : 'Record the business\'s operational costs, linked to expense categories and specific branches, with automatic accounting entries.',
            'how' => $isAr
                ? ['اذهب إلى "المصروفات" ثم "إضافة مصروف".', 'اختر فئة المصروف والفرع المرتبط به.', 'أدخل المبلغ، التاريخ، ونسبة الضريبة إن وجدت.', 'احفظ المصروف ليُرحَّل تلقائياً إلى الحسابات.']
                : ['Go to "Expenses" then "Add Expense".', 'Select the expense category and the related branch.', 'Enter the amount, date, and tax percentage if any.', 'Save the expense to have it posted automatically to accounting.'],
            'fields' => $isAr
                ? ['فئة المصروف: مثل الإيجار، الرواتب، الصيانة.', 'الفرع: المصروف يُنسب لفرع محدد لتحليل التكاليف.']
                : ['Expense category: e.g. rent, salaries, maintenance.', 'Branch: the expense is attributed to a specific branch for cost analysis.'],
            'workflows' => $isAr ? ['تسجيل فاتورة إيجار شهرية وربطها تلقائياً بحساب المصروفات في الشجرة المحاسبية.'] : ['Recording a monthly rent bill and having it automatically linked to the expense account in the chart of accounts.'],
            'tips' => $isAr ? ['أنشئ فئات مصروفات دقيقة لتحصل على تقارير تحليلية أوضح لاحقاً.'] : ['Create precise expense categories to get clearer analytical reports later.'],
        ],
        'discounts' => [
            'title' => $isAr ? 'الخصومات' : 'Discounts',
            'what' => $isAr
                ? 'تعريف خصومات ثابتة أو بنسبة مئوية يمكن تطبيقها في نقطة البيع أو على فواتير المبيعات.'
                : 'Define fixed-amount or percentage discounts that can be applied at the POS or on sales invoices.',
            'how' => $isAr
                ? ['اذهب إلى "الخصومات" ثم "إضافة خصم".', 'اختر نوع الخصم: نسبة مئوية أو مبلغ ثابت.', 'حدد نطاق تطبيقه (منتج معين، فئة، أو كامل الفاتورة).', 'احفظ الخصم ليصبح متاحاً عند البيع.']
                : ['Go to "Discounts" then "Add Discount".', 'Choose the discount type: percentage or fixed amount.', 'Set its scope (a specific product, a category, or the whole invoice).', 'Save the discount to make it available at checkout.'],
            'fields' => $isAr
                ? ['نوع الخصم: نسبة (%) أو مبلغ ثابت.', 'النطاق: منتج، فئة، أو فاتورة كاملة.']
                : ['Discount type: percentage (%) or fixed amount.', 'Scope: product, category, or whole invoice.'],
            'workflows' => $isAr ? ['تطبيق خصم موسمي على فئة منتجات معينة خلال فترة عروض.'] : ['Applying a seasonal discount on a specific product category during a promotion period.'],
            'tips' => $isAr ? ['تجنّب تراكم أكثر من خصم على نفس الفاتورة لتفادي الأخطاء الحسابية.'] : ['Avoid stacking multiple discounts on the same invoice to prevent calculation errors.'],
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
        'fixed-assets' => [
            'title' => $isAr ? 'الأصول الثابتة' : 'Fixed Assets',
            'what' => $isAr
                ? 'تسجيل الأصول المملوكة للشركة كالسيارات والآلات والمعدات، مع تكلفة الشراء والعمر الإنتاجي المتوقع.'
                : 'Register company-owned assets such as vehicles, machinery, and equipment, along with their purchase cost and expected useful life.',
            'how' => $isAr
                ? ['اذهب إلى "الأصول الثابتة" ثم "إضافة أصل".', 'أدخل اسم الأصل، تكلفة الشراء، وتاريخ الاقتناء.', 'حدد العمر الإنتاجي وطريقة الاستهلاك المتبعة.', 'احفظ الأصل ليصبح جاهزاً لاحتساب الاستهلاك.']
                : ['Go to "Fixed Assets" then "Add Asset".', 'Enter the asset name, purchase cost, and acquisition date.', 'Set the useful life and the depreciation method used.', 'Save the asset so it is ready for depreciation calculations.'],
            'fields' => $isAr
                ? ['تكلفة الشراء: القيمة الأصلية للأصل.', 'العمر الإنتاجي: المدة المتوقعة لاستخدام الأصل بالسنوات.', 'القيمة التخريدية: القيمة المتبقية بعد انتهاء العمر الإنتاجي.']
                : ['Purchase cost: the original value of the asset.', 'Useful life: the expected usage period in years.', 'Salvage value: the residual value after the useful life ends.'],
            'workflows' => $isAr ? ['تسجيل سيارة توصيل جديدة كأصل ثابت لبدء احتساب استهلاكها الشهري.'] : ['Registering a new delivery vehicle as a fixed asset to start calculating its monthly depreciation.'],
            'tips' => $isAr ? ['أدخل تاريخ الاقتناء بدقة لأنه يحدد بداية احتساب الاستهلاك.'] : ['Enter the acquisition date accurately, as it determines when depreciation calculations begin.'],
        ],
        'depreciation' => [
            'title' => $isAr ? 'استهلاك الأصول' : 'Depreciation',
            'what' => $isAr
                ? 'تسجيل مصروفات الاستهلاك الشهرية أو السنوية المرتبطة بالأصول الثابتة، مع ترحيل تلقائي للقيود المحاسبية.'
                : 'Record the monthly or annual depreciation expenses tied to fixed assets, with automatic posting of accounting entries.',
            'how' => $isAr
                ? ['اذهب إلى "استهلاك الأصول".', 'اختر الأصل المطلوب احتساب استهلاكه.', 'راجع القيمة المحسوبة تلقائياً بناءً على طريقة الاستهلاك المحددة.', 'اعتمد القيد ليتم ترحيله للحسابات.']
                : ['Go to "Depreciation".', 'Select the asset to calculate depreciation for.', 'Review the value calculated automatically based on the chosen depreciation method.', 'Approve the entry to have it posted to accounting.'],
            'fields' => $isAr ? ['طريقة الاستهلاك: القسط الثابت أو غيرها.', 'قيمة الاستهلاك الدورية: المبلغ المحتسب لكل فترة.'] : ['Depreciation method: straight-line or other.', 'Periodic depreciation amount: the value calculated for each period.'],
            'workflows' => $isAr ? ['تشغيل دورة استهلاك شهرية لجميع الأصول الثابتة وترحيلها دفعة واحدة للحسابات.'] : ['Running a monthly depreciation cycle for all fixed assets and posting them to accounting in one batch.'],
            'tips' => $isAr ? ['لا توقف احتساب الاستهلاك حتى لو لم يُستخدم الأصل مؤقتاً، إلا في حالات خاصة.'] : ['Do not stop depreciation calculations even if the asset is temporarily unused, except in special cases.'],
        ],
        'reports' => [
            'title' => $isAr ? 'التقارير' : 'Reports',
            'what' => $isAr
                ? 'أكثر من 30 تقريراً تغطي القوائم المالية (قائمة الدخل، الميزانية العمومية، ميزان المراجعة، التدفقات النقدية، دفتر الأستاذ العام) بالإضافة إلى تقارير المبيعات والمشتريات والمخزون والضرائب وتحليلات الأداء.'
                : 'Over 30 reports covering financial statements (income statement, balance sheet, trial balance, cash flow, general ledger) as well as sales, purchases, inventory, tax, and performance analytics reports.',
            'how' => $isAr
                ? ['اذهب إلى "التقارير" واختر التصنيف المطلوب (مالي، مبيعات، مخزون...).', 'حدد نطاق التاريخ والفرع للتصفية.', 'اعرض التقرير على الشاشة أو صدّره كملف Excel أو PDF.']
                : ['Go to "Reports" and choose the required category (financial, sales, inventory...).', 'Set the date range and branch to filter by.', 'View the report on screen or export it as Excel or PDF.'],
            'fields' => $isAr ? ['نطاق التاريخ: من - إلى.', 'الفرع: لعرض بيانات فرع معين أو كل الفروع.'] : ['Date range: from – to.', 'Branch: to view data for a specific branch or all branches.'],
            'workflows' => $isAr
                ? ['استخراج قائمة الدخل الشهرية لمراجعة الأرباح والخسائر.', 'مراجعة تقرير المخزون منخفض الكمية لتحديد أولويات الشراء.']
                : ['Generating the monthly income statement to review profit and loss.', 'Reviewing the low-stock report to prioritize purchasing.'],
            'tips' => $isAr ? ['اجدول تصدير التقارير المالية الرئيسية شهرياً لأرشفتها ومراجعتها بسهولة.'] : ['Schedule exporting the main financial reports monthly for easy archiving and review.'],
        ],
        'stock' => [
            'title' => $isAr ? 'المخزون والجرد' : 'Stock & Inventory',
            'what' => $isAr
                ? 'يضم ثلاث شاشات رئيسية: الأرصدة (كميات المخزون الحالية لكل فرع)، تسويات المخزون (لتصحيح الكميات بعد الجرد الفعلي)، وتحويلات المخزون (لنقل البضاعة بين الفروع).'
                : 'Includes three main pages: Stocks (current stock levels per branch), Stock Adjustments (correcting counts after a physical count), and Stock Transfers (moving stock between branches).',
            'how' => $isAr
                ? ['اذهب إلى "الأرصدة" لعرض كمية كل منتج في كل فرع.', 'لتصحيح كمية بعد الجرد، افتح "تسويات المخزون" وأضف تسوية جديدة.', 'لنقل بضاعة بين فرعين، افتح "تحويلات المخزون" وحدد الفرع المرسل والمستقبل والكميات.']
                : ['Go to "Stocks" to see each product\'s quantity per branch.', 'To correct a quantity after a physical count, open "Stock Adjustments" and add a new adjustment.', 'To move goods between two branches, open "Stock Transfers" and specify the source branch, destination branch, and quantities.'],
            'fields' => $isAr
                ? ['كمية النظام مقابل الكمية الفعلية: الفرق يُسجَّل كتسوية.', 'الفرع المرسل / الفرع المستقبل في التحويلات.']
                : ['System quantity vs. actual quantity: the difference is recorded as an adjustment.', 'Source branch / destination branch in transfers.'],
            'workflows' => $isAr
                ? ['إجراء جرد فعلي دوري ثم تسجيل الفروقات كتسوية مخزون.', 'نقل بضاعة زائدة من فرع لآخر يعاني نقصاً في نفس الصنف.']
                : ['Performing a periodic physical count then recording the differences as a stock adjustment.', 'Transferring surplus goods from one branch to another that is short on the same item.'],
            'tips' => $isAr ? ['وثّق سبب كل تسوية مخزون لتسهيل مراجعتها لاحقاً.'] : ['Document the reason for every stock adjustment to make future audits easier.'],
        ],
        'checks' => [
            'title' => $isAr ? 'الشيكات' : 'Checks',
            'what' => $isAr
                ? 'إدارة الشيكات المستلمة من العملاء والمصدرة للموردين، مع متابعة حالتها (تحت التحصيل، محصّلة، مرتجعة) وربطها بالحسابات.'
                : 'Manage checks received from customers and issued to suppliers, tracking their status (under collection, cleared, bounced) and linking them to accounting.',
            'how' => $isAr
                ? ['اذهب إلى "الشيكات" ثم "إضافة شيك".', 'حدد نوع الشيك (وارد أو صادر) والحساب البنكي المرتبط.', 'أدخل رقم الشيك، المبلغ، وتاريخ الاستحقاق.', 'حدّث حالة الشيك عند تحصيله أو ارتداده.']
                : ['Go to "Checks" then "Add Check".', 'Select the check type (received or issued) and the linked bank account.', 'Enter the check number, amount, and due date.', 'Update the check status when it is cleared or bounced.'],
            'fields' => $isAr ? ['الحالة: تحت التحصيل، محصّلة، مرتجعة.', 'تاريخ الاستحقاق: التاريخ المتوقع لصرف الشيك.'] : ['Status: under collection, cleared, bounced.', 'Due date: the expected date the check will be cashed.'],
            'workflows' => $isAr ? ['استلام شيك آجل من عميل ثم تحديث حالته إلى "محصّلة" عند صرفه بالبنك.'] : ['Receiving a post-dated check from a customer, then updating its status to "cleared" once cashed at the bank.'],
            'tips' => $isAr ? ['راجع قائمة الشيكات المستحقة أسبوعياً لتفادي أي تأخير في التحصيل أو السداد.'] : ['Review the list of due checks weekly to avoid delays in collection or payment.'],
        ],
        'transactions' => [
            'title' => $isAr ? 'حركات الحسابات' : 'Transactions',
            'what' => $isAr
                ? 'دفتر أستاذ كامل بالقيد المزدوج يعرض كل حركة محاسبية في النظام، قابل للتصفية حسب الحساب أو التاريخ أو الفرع.'
                : 'A complete double-entry ledger showing every accounting transaction in the system, filterable by account, date, or branch.',
            'how' => $isAr
                ? ['اذهب إلى "حركات الحسابات".', 'اختر الحساب أو نطاق التاريخ أو الفرع للتصفية.', 'راجع تفاصيل كل قيد ومصدره (فاتورة بيع، مصروف، تسوية...).']
                : ['Go to "Transactions".', 'Filter by account, date range, or branch.', 'Review the details of each entry and its source (sale invoice, expense, adjustment...).'],
            'fields' => $isAr ? ['مدين / دائن: طرفا القيد المحاسبي.', 'المصدر: نوع العملية التي أنشأت القيد.'] : ['Debit / Credit: the two sides of the accounting entry.', 'Source: the type of operation that generated the entry.'],
            'workflows' => $isAr ? ['تتبع كل الحركات على حساب "الصندوق" خلال شهر معين للتأكد من صحة الرصيد.'] : ['Tracing every movement on the "Cash" account during a given month to verify the balance is correct.'],
            'tips' => $isAr ? ['استخدم هذه الشاشة عند اكتشاف أي فرق في رصيد حساب لمعرفة مصدره بدقة.'] : ['Use this screen when you notice a discrepancy in an account balance to pinpoint its exact source.'],
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
        'branches' => [
            'title' => $isAr ? 'الفروع' : 'Branches',
            'what' => $isAr
                ? 'إنشاء وإدارة مواقع النشاط التجاري، حيث تُنسب كل عملية بيع، شراء، حركة مخزون، أو مصروف إلى فرع محدد.'
                : 'Create and manage your business locations; every POS sale, purchase, stock entry, and expense is scoped to a specific branch.',
            'how' => $isAr
                ? ['اذهب إلى "الفروع" ثم "إضافة فرع".', 'أدخل اسم الفرع والعنوان وبيانات التواصل.', 'اربط الفرع بالمخزون والمستخدمين المصرح لهم بالعمل عليه.']
                : ['Go to "Branches" then "Add Branch".', 'Enter the branch name, address, and contact details.', 'Link the branch to its stock and the users authorized to work on it.'],
            'fields' => $isAr ? ['اسم الفرع.', 'العنوان وبيانات التواصل.'] : ['Branch name.', 'Address and contact details.'],
            'workflows' => $isAr ? ['فتح فرع جديد وربط أول مجموعة موظفين وأصناف مخزون به.'] : ['Opening a new branch and linking the first set of employees and stock items to it.'],
            'tips' => $isAr ? ['حدد فرعاً افتراضياً واضحاً لكل مستخدم لتفادي أخطاء تسجيل العمليات على فرع خاطئ.'] : ['Set a clear default branch for each user to avoid recording operations under the wrong branch.'],
        ],
        'taxes' => [
            'title' => $isAr ? 'الضرائب' : 'Taxes',
            'what' => $isAr
                ? 'تعريف ضريبة القيمة المضافة وغيرها من الضرائب المطبقة على المنتجات والمبيعات، وتُستخدم تلقائياً في تقارير الضرائب.'
                : 'Define VAT and other tax rates applied to products and sales; they are used automatically in tax reports.',
            'how' => $isAr
                ? ['اذهب إلى "الضرائب" ثم "إضافة ضريبة".', 'أدخل اسم الضريبة والنسبة المئوية.', 'اربط الضريبة بالمنتجات أو طبّقها مباشرة على الفاتورة عند البيع.']
                : ['Go to "Taxes" then "Add Tax".', 'Enter the tax name and percentage.', 'Link the tax to products, or apply it directly on the invoice at the point of sale.'],
            'fields' => $isAr ? ['النسبة المئوية للضريبة.', 'نطاق التطبيق: منتج محدد أو الفاتورة كاملة.'] : ['Tax percentage.', 'Scope: a specific product or the whole invoice.'],
            'workflows' => $isAr ? ['تطبيق ضريبة القيمة المضافة 15% تلقائياً على جميع المنتجات الخاضعة للضريبة.'] : ['Automatically applying a 15% VAT rate to all taxable products.'],
            'tips' => $isAr ? ['تأكد من مطابقة نسب الضرائب المسجلة مع اللوائح الضريبية المحلية لنشاطك.'] : ['Make sure the recorded tax rates match your local tax regulations.'],
        ],
        'payment-methods' => [
            'title' => $isAr ? 'طرق الدفع' : 'Payment Methods',
            'what' => $isAr
                ? 'إعداد طرق الدفع المقبولة في النشاط (نقدي، بطاقة، تحويل بنكي)، وربط كل طريقة بحساب محاسبي محدد.'
                : 'Configure the payment methods accepted by your business (cash, card, bank transfer), each linked to a specific accounting account.',
            'how' => $isAr
                ? ['اذهب إلى "طرق الدفع" ثم "إضافة طريقة دفع".', 'أدخل اسم الطريقة (مثل "فيزا" أو "تحويل بنكي").', 'اربطها بالحساب المحاسبي المناسب في شجرة الحسابات.']
                : ['Go to "Payment Methods" then "Add Payment Method".', 'Enter the method\'s name (e.g. "Visa" or "Bank Transfer").', 'Link it to the appropriate account in the chart of accounts.'],
            'fields' => $isAr ? ['اسم طريقة الدفع.', 'الحساب المحاسبي المرتبط.'] : ['Payment method name.', 'Linked accounting account.'],
            'workflows' => $isAr ? ['إضافة طريقة دفع "محفظة إلكترونية" وربطها بحساب بنكي مخصص لمتابعة تحصيلاتها.'] : ['Adding an "E-Wallet" payment method and linking it to a dedicated bank account to track its collections.'],
            'tips' => $isAr ? ['اربط كل طريقة دفع بحساب مستقل لتسهيل تسوية الحسابات البنكية لاحقاً.'] : ['Link each payment method to a separate account to make bank reconciliation easier later.'],
        ],
        'accounts' => [
            'title' => $isAr ? 'شجرة الحسابات' : 'Chart of Accounts',
            'what' => $isAr
                ? 'الهيكل الشجري لجميع الحسابات المحاسبية (أصول، خصوم، إيرادات، مصروفات)، وتُستخدم تلقائياً من قبل جميع العمليات في النظام.'
                : 'The tree structure of all accounting accounts (assets, liabilities, revenue, expenses), used automatically by every transaction in the system.',
            'how' => $isAr
                ? ['اذهب إلى "شجرة الحسابات".', 'اضغط "إضافة حساب" لإنشاء حساب فرعي جديد تحت حساب رئيسي.', 'حدد نوع الحساب ورمزه المحاسبي.']
                : ['Go to "Chart of Accounts".', 'Click "Add Account" to create a new sub-account under a parent account.', 'Set the account type and its accounting code.'],
            'fields' => $isAr ? ['نوع الحساب: أصول، خصوم، إيرادات، مصروفات، حقوق ملكية.', 'الحساب الرئيسي: الحساب الأب في الشجرة.'] : ['Account type: asset, liability, revenue, expense, equity.', 'Parent account: the account above it in the tree.'],
            'workflows' => $isAr ? ['إنشاء حساب فرعي جديد لمصروف معين لتحليل بنوده بشكل منفصل.'] : ['Creating a new sub-account for a specific expense to analyze its line items separately.'],
            'tips' => $isAr ? ['لا تحذف حساباً له حركات مسجلة عليه، بل قم بتعطيله بدلاً من ذلك.'] : ['Do not delete an account that already has recorded transactions — deactivate it instead.'],
        ],
        'admins' => [
            'title' => $isAr ? 'المستخدمون والصلاحيات' : 'Admins & Roles',
            'what' => $isAr
                ? 'إنشاء حسابات المستخدمين الإداريين وتحديد الأدوار المسندة لهم، مع تقييد كل مستخدم بفرع معين ومجموعة صلاحيات محددة.'
                : 'Create admin user accounts and assign their roles; each admin has a branch restriction and a specific set of permissions.',
            'how' => $isAr
                ? ['اذهب إلى "المستخدمون" ثم "إضافة مستخدم".', 'أدخل بيانات المستخدم واختر الدور المناسب له.', 'حدد الفرع الذي سيقتصر عمل المستخدم عليه إن لزم.']
                : ['Go to "Admins" then "Add Admin".', 'Enter the user\'s details and select the appropriate role.', 'Restrict the user to a specific branch if needed.'],
            'fields' => $isAr ? ['الدور: يحدد الصلاحيات الممنوحة للمستخدم.', 'الفرع المقيَّد: الفرع الوحيد الذي يمكن للمستخدم العمل عليه.'] : ['Role: determines the permissions granted to the user.', 'Restricted branch: the only branch the user can operate on.'],
            'workflows' => $isAr ? ['إنشاء حساب كاشير جديد وتقييده بفرع واحد وصلاحيات نقطة البيع فقط.'] : ['Creating a new cashier account restricted to one branch with POS-only permissions.'],
            'tips' => $isAr ? ['طبّق مبدأ أقل الصلاحيات الكافية لكل مستخدم لتقليل المخاطر.'] : ['Apply the principle of least privilege for every user to reduce risk.'],
        ],
        'roles' => [
            'title' => $isAr ? 'الأدوار' : 'Roles',
            'what' => $isAr
                ? 'تعريف أدوار وظيفية (مثل كاشير، محاسب) وتحديد صلاحيات دقيقة لكل وحدة من وحدات النظام.'
                : 'Define functional roles (e.g. Cashier, Accountant) and assign granular permissions per module.',
            'how' => $isAr
                ? ['اذهب إلى "الأدوار" ثم "إضافة دور".', 'أدخل اسم الدور.', 'حدد الصلاحيات المسموحة لكل وحدة (عرض، إضافة، تعديل، حذف).', 'احفظ الدور وأسنده للمستخدمين المطلوبين.']
                : ['Go to "Roles" then "Add Role".', 'Enter the role name.', 'Select the allowed permissions per module (view, create, edit, delete).', 'Save the role and assign it to the required users.'],
            'fields' => $isAr ? ['اسم الدور.', 'الصلاحيات: قائمة تفصيلية بالإجراءات المسموحة في كل وحدة.'] : ['Role name.', 'Permissions: a detailed list of allowed actions per module.'],
            'workflows' => $isAr ? ['إنشاء دور "محاسب" يملك صلاحية الوصول للحسابات والتقارير المالية فقط دون نقطة البيع.'] : ['Creating an "Accountant" role that only has access to accounting and financial reports, without POS access.'],
            'tips' => $isAr ? ['راجع الأدوار دورياً وأزل الصلاحيات غير المستخدمة لتقليل المخاطر الأمنية.'] : ['Review roles periodically and remove unused permissions to reduce security risk.'],
        ],
        'subscriptions' => [
            'title' => $isAr ? 'الاشتراكات والخطط' : 'Subscriptions & Plans',
            'what' => $isAr
                ? 'عرض خطة الاشتراك الحالية للمنشأة، والمزايا المتضمنة فيها، وتاريخ التجديد القادم.'
                : 'View your tenant\'s current subscription plan, the features included, and the upcoming renewal date.',
            'how' => $isAr
                ? ['اذهب إلى "الاشتراك" لعرض تفاصيل الخطة الحالية.', 'راجع المزايا والحدود المتاحة (عدد الفروع، المستخدمين، إلخ).', 'قم بالترقية أو التجديد قبل تاريخ الانتهاء لتفادي انقطاع الخدمة.']
                : ['Go to "Subscription" to view the current plan\'s details.', 'Review the included features and limits (branches, users, etc.).', 'Upgrade or renew before the expiry date to avoid service interruption.'],
            'fields' => $isAr ? ['اسم الخطة.', 'تاريخ التجديد القادم.', 'الحدود: عدد الفروع والمستخدمين المسموح بهم.'] : ['Plan name.', 'Next renewal date.', 'Limits: allowed number of branches and users.'],
            'workflows' => $isAr ? ['ترقية الخطة عند الحاجة لفتح فروع إضافية تتجاوز حدود الخطة الحالية.'] : ['Upgrading the plan when additional branches are needed beyond the current plan\'s limits.'],
            'tips' => $isAr ? ['تابع تاريخ انتهاء الاشتراك لتفادي أي توقف مفاجئ في الخدمة.'] : ['Keep an eye on the subscription expiry date to avoid any sudden service disruption.'],
        ],
        'imports' => [
            'title' => $isAr ? 'استيراد البيانات' : 'Data Import',
            'what' => $isAr
                ? 'استيراد جماعي للمنتجات أو المستخدمين من ملفات Excel أو CSV، تتم معالجته في الخلفية عبر مهمة قائمة انتظار دون تعطيل عملك.'
                : 'Bulk-import products or users from Excel/CSV files; the import runs as a background queue job without interrupting your work.',
            'how' => $isAr
                ? ['اذهب إلى "استيراد البيانات" واختر نوع البيانات (منتجات أو مستخدمون).', 'حمّل القالب الجاهز واملأه ببياناتك.', 'ارفع الملف وانتظر معالجته في الخلفية.', 'راجع نتيجة الاستيراد والسجلات التي فشلت إن وجدت.']
                : ['Go to "Data Import" and choose the data type (products or users).', 'Download the ready-made template and fill it with your data.', 'Upload the file and wait for it to be processed in the background.', 'Review the import result and any failed rows.'],
            'fields' => $isAr ? ['ملف الاستيراد: بصيغة Excel أو CSV مطابقة للقالب.', 'حالة المهمة: قيد المعالجة، مكتملة، فشلت.'] : ['Import file: Excel or CSV matching the template.', 'Job status: processing, completed, failed.'],
            'workflows' => $isAr ? ['استيراد كتالوج منتجات ضخم مرة واحدة عند الانتقال من نظام آخر.'] : ['Importing a large product catalog in one go when migrating from another system.'],
            'tips' => $isAr ? ['اختبر الاستيراد بملف صغير أولاً للتأكد من مطابقة الأعمدة قبل رفع الملف الكامل.'] : ['Test the import with a small file first to confirm the columns match before uploading the full file.'],
        ],
        'notifications' => [
            'title' => $isAr ? 'الإشعارات' : 'Notifications',
            'what' => $isAr
                ? 'تنبيهات النظام حول نقص المخزون، المدفوعات المستحقة، فشل عمليات الاستيراد، وأحداث أخرى تحتاج متابعتك.'
                : 'System alerts for low stock, pending payments, failed imports, and other events that need your attention.',
            'how' => $isAr
                ? ['اضغط على أيقونة الجرس أعلى الصفحة لعرض الإشعارات.', 'اضغط على أي إشعار للانتقال مباشرة إلى الشاشة المرتبطة به.', 'يمكنك تعليم الإشعارات كمقروءة بشكل فردي أو جماعي.']
                : ['Click the bell icon at the top of the page to view notifications.', 'Click any notification to jump directly to its related screen.', 'You can mark notifications as read individually or all at once.'],
            'fields' => $isAr ? ['نوع الإشعار: مخزون، مدفوعات، استيراد، إلخ.', 'حالة القراءة: مقروء أو غير مقروء.'] : ['Notification type: stock, payments, imports, etc.', 'Read status: read or unread.'],
            'workflows' => $isAr ? ['استلام تنبيه بانخفاض مخزون منتج معين والانتقال مباشرة لإنشاء أمر شراء له.'] : ['Receiving a low-stock alert for a product and jumping straight to creating a purchase order for it.'],
            'tips' => $isAr ? ['راجع الإشعارات يومياً حتى لا يفوتك أي حدث هام مثل نفاد المخزون.'] : ['Review notifications daily so you don\'t miss important events like stock running out.'],
        ],
        'employee-portal' => [
            'title' => $isAr ? 'بوابة الموظف' : 'Employee Portal',
            'what' => $isAr
                ? 'تسجيل دخول منفصل عبر الرابط /employee يتيح للموظفين عرض قسائم الرواتب، تقديم طلبات الإجازات، تسجيل مطالبات المصروفات، ومتابعة الحضور، دون الحاجة لصلاحيات إدارية.'
                : 'A separate login at /employee that lets staff view payslips, submit leave requests, file expense claims, and check attendance — no admin access needed.',
            'how' => $isAr
                ? ['شارك رابط /employee مع الموظفين مع بيانات الدخول الخاصة بهم.', 'يسجّل الموظف دخوله ليعرض راتبه وحضوره.', 'يمكنه تقديم طلب إجازة أو مطالبة مصروفات من نفس البوابة.']
                : ['Share the /employee link with staff along with their login credentials.', 'The employee logs in to view their payslip and attendance.', 'They can submit a leave request or an expense claim from the same portal.'],
            'fields' => $isAr ? ['قسيمة الراتب: تفاصيل الراتب الشهري والخصومات.', 'طلب الإجازة: النوع والمدة وسبب الطلب.'] : ['Payslip: monthly salary details and deductions.', 'Leave request: type, duration, and reason.'],
            'workflows' => $isAr ? ['موظف يقدم طلب إجازة من البوابة، ثم يوافق عليه مديره من لوحة التحكم الإدارية.'] : ['An employee submits a leave request from the portal, which is then approved by their manager from the admin panel.'],
            'tips' => $isAr ? ['شجّع الموظفين على استخدام البوابة لتقليل الأعباء الإدارية على قسم الموارد البشرية.'] : ['Encourage employees to use the portal to reduce the administrative burden on HR.'],
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
                ? ['أنشئ رمز API الخاص بك من صفحة الحساب.', 'أرسل الرمز في ترويسة X-API-Token مع كل طلب.', 'استخدم نقاط النهاية الموثقة للوصول إلى المنتجات والمبيعات.', 'قم بزيارة /api/docs على نطاق الشركة لعرض توثيق واجهة البرمجة التفاعلي.']
                : ['Generate your API token from your account page.', 'Send the token in the X-API-Token header with every request.', 'Use the documented endpoints to access products and sales.', 'Visit /api/docs on your tenant domain to view the interactive API documentation.'],
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
