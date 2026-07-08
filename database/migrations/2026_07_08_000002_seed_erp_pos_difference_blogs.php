<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/ERP_vs_POS_The_Real_Difference_Explained_by_Mohaaseb.html');
        $contentAr_1 = file_get_contents($blogDir . '/ERP_vs_POS_The_Real_Difference_Explained_by_Mohaaseb_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/What_Is_ERP_A_Complete_Guide_by_Mohaaseb.html');
        $contentAr_2 = file_get_contents($blogDir . '/What_Is_ERP_A_Complete_Guide_by_Mohaaseb_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/What_Is_POS_Understanding_Point_of_Sale_with_Mohaaseb.html');
        $contentAr_3 = file_get_contents($blogDir . '/What_Is_POS_Understanding_Point_of_Sale_with_Mohaaseb_ar.html');

        $blogs = [
            [
                'title_en' => 'ERP vs POS: The Real Difference, Explained by Mohaaseb',
                'title_ar' => 'الفرق بين ERP وPOS: شرح مبسّط من محاسب',
                'excerpt_en' => 'What is the difference between ERP and POS? See how mohaaseb.com combines both into one connected platform.',
                'excerpt_ar' => 'ما الفرق بين ERP وPOS؟ تعرف على كيف تجمع mohaaseb.com بينهما في منصة واحدة متكاملة.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'What Is ERP? A Complete Guide by Mohaaseb',
                'title_ar' => 'ما هو نظام ERP؟ دليل شامل من محاسب',
                'excerpt_en' => 'A full breakdown of ERP systems and how Mohaaseb\'s ERP connects inventory, purchasing, sales, and accounting.',
                'excerpt_ar' => 'شرح كامل لأنظمة ERP وكيف يربط نظام ERP في محاسب المخزون والمشتريات والمبيعات والمحاسبة.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'What Is POS? Understanding Point of Sale with Mohaaseb',
                'title_ar' => 'ما هو نظام POS؟ فهم نقطة البيع مع محاسب',
                'excerpt_en' => 'Learn what a POS system is and how mohaaseb.com\'s POS connects directly to ERP and cloud accounting.',
                'excerpt_ar' => 'تعرف على ماهية نظام POS وكيف يرتبط نظام POS في mohaaseb.com مباشرة بـ ERP والمحاسبة السحابية.',
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
            'ERP vs POS: The Real Difference, Explained by Mohaaseb',
            'What Is ERP? A Complete Guide by Mohaaseb',
            'What Is POS? Understanding Point of Sale with Mohaaseb',
        ])->delete();
    }
};
