<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/Mohaaseb_Cloud_Accounting_App_The_Complete_Guide.html');
        $contentAr_1 = file_get_contents($blogDir . '/Mohaaseb_Cloud_Accounting_App_The_Complete_Guide_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/Mohaaseb_ERP_POS_and_Your_Accountant_How_They_Work_Together.html');
        $contentAr_2 = file_get_contents($blogDir . '/Mohaaseb_ERP_POS_and_Your_Accountant_How_They_Work_Together_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/Getting_Started_with_Mohaaseb_com_Your_First_Week_Checklist.html');
        $contentAr_3 = file_get_contents($blogDir . '/Getting_Started_with_Mohaaseb_com_Your_First_Week_Checklist_ar.html');

        $blogs = [
            [
                'title_en' => 'Mohaaseb: The Cloud Accounting App Built for Growing Businesses',
                'title_ar' => 'محاسب: تطبيق محاسبة سحابي مصمم للأعمال المتنامية',
                'excerpt_en' => 'Discover how Mohaaseb, a cloud accounting app, unifies accounting, ERP, and POS in one platform for your business and your accountant.',
                'excerpt_ar' => 'تعرف على كيف يوحّد محاسب، تطبيق المحاسبة السحابي، المحاسبة وERP وPOS في منصة واحدة لعملك ومحاسبك.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'ERP, POS, and Your Accountant: How Mohaaseb Connects All Three',
                'title_ar' => 'ERP وPOS ومحاسبك: كيف يربط محاسب بينهم جميعًا',
                'excerpt_en' => 'See how Mohaaseb links ERP, POS, and your accountant into one live workflow, eliminating manual reconciliation between systems.',
                'excerpt_ar' => 'اكتشف كيف يربط محاسب بين ERP وPOS ومحاسبك في سير عمل حي واحد، دون الحاجة لمطابقة يدوية بين الأنظمة.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title_en' => 'Getting Started with Mohaaseb.com: Your First Week Checklist',
                'title_ar' => 'البدء مع mohaaseb.com: قائمة أسبوعك الأول',
                'excerpt_en' => 'A day-by-day checklist for setting up accounting, inventory, and POS on mohaaseb.com in your first week.',
                'excerpt_ar' => 'قائمة يومية لإعداد المحاسبة والمخزون ونقطة البيع على mohaaseb.com خلال أسبوعك الأول.',
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
            'Mohaaseb: The Cloud Accounting App Built for Growing Businesses',
            'ERP, POS, and Your Accountant: How Mohaaseb Connects All Three',
            'Getting Started with Mohaaseb.com: Your First Week Checklist',
        ])->delete();
    }
};
