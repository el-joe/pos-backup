<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $blogs = [
            [
                'title_en' => 'General Cloud Accounting Explained: How Mohaaseb Unifies ERP and POS Bookkeeping',
                'title_ar' => 'الحسابات العامة السحابية: كيف يوحّد محاسب بين ERP وPOS في دفتر أستاذ واحد',
                'excerpt_en' => 'Learn what general cloud accounting (حسابات عامة سحابي) means and how mohaaseb ties ERP and POS transactions into one live general ledger.',
                'excerpt_ar' => 'تعرف على معنى الحسابات العامة السحابية وكيف يربط محاسب معاملات ERP وPOS في دفتر أستاذ عام واحد وحي.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_General_Cloud_Accounting_Explained_ERP_POS.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_General_Cloud_Accounting_Explained_ERP_POS_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'mohaaseb.com: The Complete Platform for ERP, POS, and General Cloud Accounting',
                'title_ar' => 'mohaaseb.com: المنصة الكاملة لـ ERP وPOS والحسابات العامة السحابية',
                'excerpt_en' => 'See how mohaaseb.com merges ERP, POS, and general cloud accounting into one platform so your محاسب always works from reconciled, live data.',
                'excerpt_ar' => 'اكتشف كيف يجمع mohaaseb.com بين ERP وPOS والحسابات العامة السحابية في منصة واحدة حتى يعمل محاسبك دائمًا على بيانات حية ومسوّاة.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_com_Complete_ERP_POS_General_Accounting_Platform.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_com_Complete_ERP_POS_General_Accounting_Platform_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'From POS Sale to Cloud General Ledger: How Mohaaseb ERP Automates Your Accounts',
                'title_ar' => 'من عملية بيع في POS إلى دفتر الأستاذ العام السحابي: كيف يُؤتمت محاسب حساباتك عبر ERP',
                'excerpt_en' => 'Follow the journey of a single POS sale through mohaaseb ERP and general cloud accounting, fully automated from checkout to the general ledger.',
                'excerpt_ar' => 'تابع رحلة عملية بيع واحدة في POS عبر محاسب ERP والحسابات العامة السحابية، مؤتمتة بالكامل من الدفع وحتى دفتر الأستاذ العام.',
                'content_en' => file_get_contents($blogDir . '/From_POS_Sale_to_Cloud_General_Ledger_Mohaaseb_ERP.html'),
                'content_ar' => file_get_contents($blogDir . '/From_POS_Sale_to_Cloud_General_Ledger_Mohaaseb_ERP_ar.html'),
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($blogs as $data) {
            Blog::updateOrCreate(
                ['title_en' => $data['title_en']],
                $data
            );
        }
    }

    public function down(): void
    {
        Blog::whereIn('title_en', [
            'General Cloud Accounting Explained: How Mohaaseb Unifies ERP and POS Bookkeeping',
            'mohaaseb.com: The Complete Platform for ERP, POS, and General Cloud Accounting',
            'From POS Sale to Cloud General Ledger: How Mohaaseb ERP Automates Your Accounts',
        ])->delete();
    }
};
