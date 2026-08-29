@php
    $isAr = app()->getLocale() === 'ar';

    $steps = [
        [
            'icon' => 'fa fa-hand-sparkles',
            'title' => $isAr ? 'أهلاً بك في محاسب!' : 'Welcome to Mohaaseb!',
            'body' => $isAr
                ? 'نظام إدارة أعمالك المتكامل. خذ جولة سريعة للتعرف على النظام.'
                : "Your complete business management system. Let's take a quick tour to get you started.",
        ],
        [
            'icon' => 'fa fa-chart-line',
            'title' => $isAr ? 'لوحة التحكم' : 'Dashboard',
            'body' => $isAr
                ? 'نظرة عامة على أعمالك: إجمالي المبيعات، أفضل المنتجات، حالة الخزينة، ومخططات الأرباح — كل ذلك في مكان واحد.'
                : 'Your business overview: sales totals, top products, cash register status, and profit charts — all in one place.',
            'link' => ['route' => 'admin.statistics', 'label' => $isAr ? 'الذهاب للإحصائيات' : 'Go to Statistics'],
        ],
        [
            'icon' => 'fa fa-cash-register',
            'title' => $isAr ? 'نقطة البيع (POS)' : 'Point of Sale (POS)',
            'body' => $isAr
                ? 'بع منتجاتك فوراً باستخدام شاشة نقطة البيع. اختر المنتجات، اختر العميل، حدد طريقة الدفع، ثم أكّد الطلب.'
                : 'Sell products instantly with the touchscreen POS. Select products, choose customer, pick payment method, and confirm.',
        ],
        [
            'icon' => 'fa fa-boxes-stacked',
            'title' => $isAr ? 'المنتجات والمخزون' : 'Products & Inventory',
            'body' => $isAr
                ? 'أضف منتجاتك، حدد الأسعار، ونظّمها حسب الفئة والعلامة التجارية. تتحدث كميات المخزون تلقائياً مع كل عملية بيع أو شراء.'
                : "Add your products, set prices, organize by category and brand. Stock levels update automatically with every sale and purchase.",
        ],
        [
            'icon' => 'fa fa-file-invoice',
            'title' => $isAr ? 'المبيعات والمشتريات' : 'Sales & Purchases',
            'body' => $isAr
                ? 'إدارة كاملة للفواتير. تابع ما بعته واشتريته، سجّل المدفوعات، وأدر المرتجعات.'
                : "Full invoice management. Track what you've sold and bought, record payments, and manage refunds.",
        ],
        [
            'icon' => 'fa fa-book',
            'title' => $isAr ? 'الحسابات' : 'Accounting',
            'body' => $isAr
                ? 'نظام محاسبة بالقيد المزدوج مدمج. تابع الحركات المالية، أدر الحسابات، واستخرج التقارير المالية (الأرباح والخسائر، الميزانية العمومية، ميزان المراجعة).'
                : 'Double-entry bookkeeping built in. Track transactions, manage accounts, run financial reports (P&L, Balance Sheet, Trial Balance).',
        ],
        ...(adminCan('hrm_dashboard.list,hrm_master_data.list,hrm_payroll.list,hrm_claims.list,hrm_attendance.list,hrm_leaves.list') ? [[
            'icon' => 'fa fa-users',
            'title' => $isAr ? 'الموارد البشرية' : 'Human Resources',
            'body' => $isAr
                ? 'أدر الموظفين، الحضور والانصراف، الرواتب، الإجازات، ومصروفات الموظفين من مكان واحد.'
                : 'Manage employees, attendance, payroll, leaves, and expense claims from one place.',
        ]] : []),
        [
            'icon' => 'fa fa-gear',
            'title' => $isAr ? 'أكمل إعداد النظام' : 'Complete Your Setup',
            'body' => $isAr
                ? 'اذهب إلى الإعدادات لإضافة اسم عملك، الشعار، الرقم الضريبي، العملة، وطرق الدفع قبل البدء.'
                : 'Go to Settings to add your business name, logo, tax number, currency, and payment methods before you start.',
            'action' => ['method' => 'goToSettings', 'label' => $isAr ? 'الذهاب إلى الإعدادات' : 'Go to Settings'],
        ],
        [
            'icon' => 'fa fa-flag-checkered',
            'title' => $isAr ? 'أنت جاهز الآن!' : "You're Ready!",
            'body' => $isAr
                ? 'ابدأ بإضافة أول منتج لديك، ثم قم بأول عملية بيع. هل تحتاج مساعدة؟ راجع التوثيق.'
                : 'Start by adding your first product, then make your first sale. Need help? Check the documentation.',
            'final' => true,
        ],
    ];

    $total = count($steps);
    $current = $steps[$this->step] ?? $steps[$total - 1];
    $progress = $total > 1 ? round((($this->step) / ($total - 1)) * 100) : 100;
@endphp

@if($show)
<div class="onboarding-tour-overlay" style="position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.65);display:flex;align-items:center;justify-content:center;padding:1rem;">
    <div class="onboarding-tour-card" style="background:var(--bs-body-bg,#1c1f26);color:var(--bs-body-color,#fff);border-radius:1rem;max-width:520px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.5);position:relative;">
        <div class="d-flex justify-content-end p-2">
            <button type="button" wire:click="dismiss" class="btn btn-sm btn-link text-muted" style="text-decoration:none;">
                {{ $isAr ? 'تخطي الجولة' : 'Skip Tour' }} <i class="fa fa-xmark ms-1"></i>
            </button>
        </div>

        <div class="text-center px-4 pb-2">
            <div style="width:72px;height:72px;border-radius:50%;background:rgba(13,110,253,.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="{{ $current['icon'] }}" style="font-size:2rem;color:#0d6efd;"></i>
            </div>
            <h4 class="fw-bold mb-2">{{ $current['title'] }}</h4>
            <p class="text-muted mb-3">{{ $current['body'] }}</p>

            @isset($current['link'])
                <a href="{{ route($current['link']['route']) }}" wire:click="dismiss" class="btn btn-outline-primary btn-sm mb-3">
                    {{ $current['link']['label'] }}
                </a>
            @endisset

            @isset($current['action'])
                <button type="button" wire:click="{{ $current['action']['method'] }}" class="btn btn-primary btn-sm mb-3">
                    {{ $current['action']['label'] }}
                </button>
            @endisset

            @if(!empty($current['final']))
                <div class="d-flex gap-2 justify-content-center flex-wrap mb-3">
                    <a href="{{ route('admin.docs') }}" wire:click="dismiss" class="btn btn-outline-secondary btn-sm">
                        {{ $isAr ? 'فتح التوثيق' : 'Open Documentation' }}
                    </a>
                    <button type="button" wire:click="dismiss" class="btn btn-success btn-sm">
                        {{ $isAr ? 'ابدأ استخدام النظام' : 'Start Using System' }}
                    </button>
                </div>
            @endif
        </div>

        <div class="px-4 pb-4">
            <div class="progress mb-3" style="height:6px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" wire:click="prevStep" class="btn btn-sm btn-outline-secondary {{ $this->step === 0 ? 'invisible' : '' }}">
                    <i class="fa {{ $isAr ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i>
                    {{ $isAr ? 'السابق' : 'Previous' }}
                </button>
                <small class="text-muted">{{ $this->step + 1 }} / {{ $total }}</small>
                @if($this->step < $total - 1)
                    <button type="button" wire:click="nextStep" class="btn btn-sm btn-primary">
                        {{ $isAr ? 'التالي' : 'Next' }}
                        <i class="fa {{ $isAr ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                    </button>
                @else
                    <button type="button" wire:click="dismiss" class="btn btn-sm btn-success">
                        {{ $isAr ? 'إنهاء' : 'Finish' }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 576px) {
        .onboarding-tour-card { max-width: 100%; }
    }
</style>
@endif
