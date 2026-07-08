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
                'title_en' => 'POS vs. Retail ERP: Which One Should Your Business Choose?',
                'title_ar' => 'POS مقابل ERP التجزئة: أيهما يجب أن تختار شركتك؟',
                'excerpt_en' => 'POS or retail ERP? See what each one covers and why mohaaseb.com combines both so you never have to choose.',
                'excerpt_ar' => 'POS أم ERP للتجزئة؟ تعرف على ما يغطيه كل منهما ولماذا يجمع mohaaseb.com بينهما حتى لا تضطر للاختيار.',
                'content_en' => file_get_contents($blogDir . '/POS_vs_Retail_ERP_Which_to_Choose.html'),
                'content_ar' => file_get_contents($blogDir . '/POS_vs_Retail_ERP_Which_to_Choose_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'What Is an ERP System? A Practical Guide for Growing Businesses',
                'title_ar' => 'ما هو نظام ERP؟ دليل عملي للشركات النامية',
                'excerpt_en' => 'A plain-language guide to ERP systems, their core modules, and how mohaaseb delivers ERP without the usual complexity.',
                'excerpt_ar' => 'دليل مبسط لأنظمة ERP ووحداتها الأساسية، وكيف يقدم محاسب نظام ERP دون التعقيد المعتاد.',
                'content_en' => file_get_contents($blogDir . '/Understanding_ERP_Systems_with_Mohaaseb.html'),
                'content_ar' => file_get_contents($blogDir . '/Understanding_ERP_Systems_with_Mohaaseb_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'What Is a POS System? Everything Business Owners Need to Know',
                'title_ar' => 'ما هو نظام POS؟ كل ما يحتاج أصحاب الأعمال معرفته',
                'excerpt_en' => 'Learn what a POS system does, where a standalone POS falls short, and how mohaaseb.com turns every sale into a full ERP transaction.',
                'excerpt_ar' => 'تعرف على ما يقدمه نظام POS، وأين يقصر النظام المستقل، وكيف يحوّل mohaaseb.com كل عملية بيع إلى معاملة ERP كاملة.',
                'content_en' => file_get_contents($blogDir . '/Understanding_POS_Systems_with_Mohaaseb.html'),
                'content_ar' => file_get_contents($blogDir . '/Understanding_POS_Systems_with_Mohaaseb_ar.html'),
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
            'POS vs. Retail ERP: Which One Should Your Business Choose?',
            'What Is an ERP System? A Practical Guide for Growing Businesses',
            'What Is a POS System? Everything Business Owners Need to Know',
        ])->delete();
    }
};
