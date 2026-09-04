<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/Cloud_General_Accounting_with_Mohaaseb_The_Smart_ERP_POS_Solution.html');
        $contentAr_1 = file_get_contents($blogDir . '/Cloud_General_Accounting_with_Mohaaseb_The_Smart_ERP_POS_Solution_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/Why_Every_Business_Needs_a_Mohaaseb_Accountant_Partnered_with_a_Smart_ERP_POS_System.html');
        $contentAr_2 = file_get_contents($blogDir . '/Why_Every_Business_Needs_a_Mohaaseb_Accountant_Partnered_with_a_Smart_ERP_POS_System_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/Mohaaseb_com_The_All_in_One_ERP_POS_and_Cloud_Accounting_Platform.html');
        $contentAr_3 = file_get_contents($blogDir . '/Mohaaseb_com_The_All_in_One_ERP_POS_and_Cloud_Accounting_Platform_ar.html');

        $blogs = [
            [
                'title_en' => 'Cloud General Accounting with Mohaaseb: The Smart ERP/POS Solution',
                'title_ar' => 'حسابات عامة سحابي مع محاسب: حل ERP/POS الذكي',
                'excerpt_en' => 'Discover how cloud general accounting inside Mohaaseb\'s ERP/POS platform keeps your ledgers synced with every sale, in real time.',
                'excerpt_ar' => 'تعرف على كيفية إبقاء الحسابات العامة السحابية في منصة محاسب ERP/POS متزامنة مع كل عملية بيع لحظيًا.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'Why Every Business Needs a Mohaaseb Accountant, Paired with a Smart ERP/POS System',
                'title_ar' => 'لماذا تحتاج كل شركة إلى محاسب مدعوم بنظام ERP/POS ذكي',
                'excerpt_en' => 'A great محاسب needs great data. See how Mohaaseb\'s ERP/POS system gives accountants accurate, real-time numbers to work with.',
                'excerpt_ar' => 'المحاسب الجيد يحتاج بيانات دقيقة. تعرف على كيف يمنح نظام محاسب ERP/POS الذكي أرقامًا فورية ودقيقة للمحاسبين.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'mohaaseb.com: The All-in-One ERP, POS, and Cloud Accounting Platform',
                'title_ar' => 'mohaaseb.com: منصة ERP وPOS وحسابات سحابية متكاملة',
                'excerpt_en' => 'See how mohaaseb.com unifies POS, ERP, and حسابات عامة سحابي into a single platform built for growing businesses.',
                'excerpt_ar' => 'تعرف على كيف توحّد mohaaseb.com بين POS وERP وحسابات عامة سحابي في منصة واحدة مصممة للشركات النامية.',
                'content_en' => $contentEn_3,
                'content_ar' => $contentAr_3,
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
            'Cloud General Accounting with Mohaaseb: The Smart ERP/POS Solution',
            'Why Every Business Needs a Mohaaseb Accountant, Paired with a Smart ERP/POS System',
            'mohaaseb.com: The All-in-One ERP, POS, and Cloud Accounting Platform',
        ])->delete();
    }
};
