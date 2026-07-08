<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/ERP_and_POS_Integration_How_Mohaaseb_Connects_Your_Whole_Business.html');
        $contentAr_1 = file_get_contents($blogDir . '/ERP_and_POS_Integration_How_Mohaaseb_Connects_Your_Whole_Business_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/Why_ERP_and_POS_Integration_Matters_A_Mohaaseb_Deep_Dive.html');
        $contentAr_2 = file_get_contents($blogDir . '/Why_ERP_and_POS_Integration_Matters_A_Mohaaseb_Deep_Dive_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/The_Complete_Guide_to_ERP_and_POS_Integration_with_Mohaaseb.html');
        $contentAr_3 = file_get_contents($blogDir . '/The_Complete_Guide_to_ERP_and_POS_Integration_with_Mohaaseb_ar.html');

        $blogs = [
            [
                'title_en' => 'ERP and POS Integration: How Mohaaseb.com Connects Your Whole Business',
                'title_ar' => 'تكامل ERP وPOS: كيف يربط محاسب (Mohaaseb.com) عملك بالكامل',
                'excerpt_en' => 'Learn what ERP and POS integration really means and how Mohaaseb.com unifies sales, inventory, and accounting in real time.',
                'excerpt_ar' => 'تعرّف على معنى تكامل ERP وPOS الحقيقي وكيف يوحّد محاسب (Mohaaseb.com) المبيعات والمخزون والمحاسبة في الوقت الفعلي.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'Why ERP and POS Integration Matters: A Mohaaseb Deep Dive',
                'title_ar' => 'لماذا يهم تكامل ERP وPOS: نظرة معمّقة من محاسب',
                'excerpt_en' => 'Disconnected ERP and POS systems cost businesses time and accuracy. See how Mohaaseb solves it natively.',
                'excerpt_ar' => 'انفصال أنظمة ERP وPOS يكلّف الأعمال وقتًا ودقة. اكتشف كيف يحل محاسب هذه المشكلة بشكل أصيل.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'The Complete Guide to ERP and POS Integration with Mohaaseb',
                'title_ar' => 'الدليل الكامل لتكامل ERP وPOS مع محاسب',
                'excerpt_en' => 'A full breakdown of ERP and POS integration fundamentals, key workflows, and how Mohaaseb.com brings them together.',
                'excerpt_ar' => 'شرح كامل لأساسيات تكامل ERP وPOS وسير العمل الأساسي وكيف يجمعها محاسب (mohaaseb.com) معًا.',
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
            'ERP and POS Integration: How Mohaaseb.com Connects Your Whole Business',
            'Why ERP and POS Integration Matters: A Mohaaseb Deep Dive',
            'The Complete Guide to ERP and POS Integration with Mohaaseb',
        ])->delete();
    }
};
