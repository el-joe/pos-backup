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
                'title_en' => 'Cloud General Accounting with Mohaaseb: The Smart ERP/POS Solution',
                'title_ar' => 'حسابات عامة سحابي مع محاسب: حل ERP/POS الذكي',
                'excerpt_en' => 'Discover how Mohaaseb brings cloud general accounting together with a full ERP and POS system, so every sale flows straight into your books.',
                'excerpt_ar' => 'اكتشف كيف يجمع محاسب بين الحسابات العامة السحابية ونظام ERP وPOS متكامل، بحيث تنتقل كل عملية بيع مباشرة إلى دفاترك.',
                'content_en' => file_get_contents($blogDir . '/Cloud_General_Accounting_with_Mohaaseb_The_Smart_ERP_POS_Solution.html'),
                'content_ar' => file_get_contents($blogDir . '/Cloud_General_Accounting_with_Mohaaseb_The_Smart_ERP_POS_Solution_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'Sales and Inventory Management: How Mohaaseb ERP & POS Keeps Your Business in Sync',
                'title_ar' => 'إدارة المبيعات والمخزون: كيف يبقي نظام محاسب ERP وPOS عملك متزامنًا',
                'excerpt_en' => 'See how a unified Mohaaseb ERP + POS system connects every sale to inventory and accounting in real time, eliminating manual reconciliation.',
                'excerpt_ar' => 'تعرف على كيفية ربط نظام محاسب ERP + POS الموحّد لكل عملية بيع بالمخزون والمحاسبة في الوقت الفعلي، دون تسويات يدوية.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_Sales_and_Inventory_Management_ERP_POS_Guide.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_Sales_and_Inventory_Management_ERP_POS_Guide_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'Purchasing & Inventory Management: How Mohaaseb ERP & POS Keeps Stock and Suppliers in Sync',
                'title_ar' => 'إدارة المشتريات والمخزون: كيف يبقي محاسب ERP وPOS مخزونك ومورديك متزامنين',
                'excerpt_en' => 'Learn how Mohaaseb links purchasing directly to live inventory data, automating reorder suggestions and supplier invoicing for smarter buying decisions.',
                'excerpt_ar' => 'تعرف على كيفية ربط محاسب المشتريات مباشرة ببيانات المخزون الحية، وأتمتة اقتراحات إعادة الطلب وفواتير الموردين لاتخاذ قرارات شراء أذكى.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_Purchasing_and_Inventory_Management_ERP_POS_Guide.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_Purchasing_and_Inventory_Management_ERP_POS_Guide_ar.html'),
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
            'Sales and Inventory Management: How Mohaaseb ERP & POS Keeps Your Business in Sync',
            'Purchasing & Inventory Management: How Mohaaseb ERP & POS Keeps Stock and Suppliers in Sync',
        ])->delete();
    }
};
