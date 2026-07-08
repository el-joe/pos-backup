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
                'title_en' => 'Sales and Inventory Management: How Mohaaseb ERP & POS Keeps Your Business in Sync',
                'title_ar' => 'إدارة المبيعات والمخزون: كيف يبقي نظام محاسب ERP وPOS عملك متزامنًا',
                'excerpt_en' => 'See how mohaaseb unifies sales and inventory management so every POS transaction updates your stock and accounting instantly.',
                'excerpt_ar' => 'تعرف على كيف يوحّد محاسب إدارة المبيعات والمخزون بحيث تُحدّث كل عملية بيع في نقطة البيع المخزون والمحاسبة فورًا.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_Sales_and_Inventory_Management_ERP_POS_Guide.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_Sales_and_Inventory_Management_ERP_POS_Guide_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'ERP vs. POS: How Mohaaseb Combines Both Into One Complete System',
                'title_ar' => 'ERP مقابل POS: كيف يدمج محاسب النظامين في منصة واحدة متكاملة',
                'excerpt_en' => 'Discover why mohaaseb merges ERP and POS into a single platform so your محاسب, sales, and inventory always stay in sync.',
                'excerpt_ar' => 'اكتشف لماذا يدمج محاسب نظامي ERP وPOS في منصة واحدة حتى يبقى محاسبك والمبيعات والمخزون متزامنين دائمًا.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_ERP_vs_POS_Complete_Business_System.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_ERP_vs_POS_Complete_Business_System_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'Why mohaaseb.com Is the Smart Choice for POS, ERP, and Accounting',
                'title_ar' => 'لماذا يُعد mohaaseb.com الخيار الذكي لأنظمة POS وERP والمحاسبة',
                'excerpt_en' => 'A complete look at how mohaaseb.com brings POS, ERP, and accounting together in one platform for growing businesses.',
                'excerpt_ar' => 'نظرة شاملة على كيف يجمع mohaaseb.com بين POS وERP والمحاسبة في منصة واحدة للشركات النامية.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_com_Why_Choose_Us_ERP_POS_Accounting.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_com_Why_Choose_Us_ERP_POS_Accounting_ar.html'),
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
            'Sales and Inventory Management: How Mohaaseb ERP & POS Keeps Your Business in Sync',
            'ERP vs. POS: How Mohaaseb Combines Both Into One Complete System',
            'Why mohaaseb.com Is the Smart Choice for POS, ERP, and Accounting',
        ])->delete();
    }
};
