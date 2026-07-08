<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/Building_Your_Accounting_Tree_in_Mohaaseb.html');
        $contentAr_1 = file_get_contents($blogDir . '/Building_Your_Accounting_Tree_in_Mohaaseb_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/How_Mohaaseb_ERP_Streamlines_Your_Back_Office.html');
        $contentAr_2 = file_get_contents($blogDir . '/How_Mohaaseb_ERP_Streamlines_Your_Back_Office_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/Why_Mohaaseb_POS_Is_More_Than_Just_a_Cash_Register.html');
        $contentAr_3 = file_get_contents($blogDir . '/Why_Mohaaseb_POS_Is_More_Than_Just_a_Cash_Register_ar.html');

        $blogs = [
            [
                'title_en' => 'Building Your Accounting Tree in Mohaaseb: A Practical Guide',
                'title_ar' => 'بناء الشجرة المحاسبية في محاسب: دليل عملي',
                'excerpt_en' => 'Learn how to structure your accounting tree in Mohaaseb so it stays connected to your ERP and POS activity automatically.',
                'excerpt_ar' => 'تعرف على كيفية بناء شجرتك المحاسبية في محاسب بحيث تبقى متصلة تلقائيًا بنشاط ERP وPOS.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => "How Mohaaseb's ERP Streamlines Your Back Office",
                'title_ar' => 'كيف يُبسّط ERP في محاسب عمليات مكتبك الخلفي',
                'excerpt_en' => 'Discover how Mohaaseb ERP connects purchasing, inventory, and financial reporting into one accounting tree.',
                'excerpt_ar' => 'اكتشف كيف يربط ERP في محاسب بين المشتريات والمخزون والتقارير المالية في شجرة محاسبية واحدة.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title_en' => 'Why Mohaaseb POS Is More Than Just a Cash Register',
                'title_ar' => 'لماذا نقطة بيع محاسب أكثر من مجرد آلة تسجيل نقدية',
                'excerpt_en' => 'See how every sale in Mohaaseb POS updates your ERP inventory and accounting tree in real time.',
                'excerpt_ar' => 'شاهد كيف تُحدّث كل عملية بيع في نقطة بيع محاسب مخزون ERP والشجرة المحاسبية في الوقت الفعلي.',
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
            'Building Your Accounting Tree in Mohaaseb: A Practical Guide',
            "How Mohaaseb's ERP Streamlines Your Back Office",
            'Why Mohaaseb POS Is More Than Just a Cash Register',
        ])->delete();
    }
};
