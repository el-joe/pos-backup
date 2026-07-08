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
                'title_en' => 'Purchase and Inventory Management: How Mohaaseb Connects Buying to Stock',
                'title_ar' => 'إدارة المشتريات والمخزون: كيف يربط محاسب بين الشراء والمخزون',
                'excerpt_en' => 'See how mohaaseb links purchase orders, goods receipt, and inventory management into one ERP + POS workflow so stock stays accurate.',
                'excerpt_ar' => 'تعرف على كيف يربط محاسب بين أوامر الشراء واستلام البضاعة وإدارة المخزون في سير عمل واحد لـ ERP وPOS ليبقى المخزون دقيقًا.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_Purchase_Inventory_Management_Guide.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_Purchase_Inventory_Management_Guide_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'One System, Not Two: How Mohaaseb Unifies ERP and POS',
                'title_ar' => 'نظام واحد لا اثنان: كيف يوحّد محاسب بين ERP وPOS',
                'excerpt_en' => 'Learn how mohaaseb merges ERP accounting and POS checkout into one platform so every sale updates your books and inventory automatically.',
                'excerpt_ar' => 'تعرف على كيف يدمج محاسب بين محاسبة ERP ونقطة البيع POS في منصة واحدة بحيث تُحدّث كل عملية بيع دفاترك ومخزونك تلقائيًا.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_ERP_and_POS_Unified_Guide.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_ERP_and_POS_Unified_Guide_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title_en' => 'What Is Mohaaseb.com? A Full Overview of the ERP + POS Platform',
                'title_ar' => 'ما هو mohaaseb.com؟ نظرة شاملة على منصة ERP + POS',
                'excerpt_en' => 'A complete overview of mohaaseb.com: its ERP, POS, purchasing, and inventory management modules, and why growing businesses run on one platform.',
                'excerpt_ar' => 'نظرة شاملة على mohaaseb.com: وحداته لـ ERP وPOS والمشتريات وإدارة المخزون، ولماذا تعتمد الشركات النامية على منصة واحدة.',
                'content_en' => file_get_contents($blogDir . '/Mohaaseb_com_Platform_Overview.html'),
                'content_ar' => file_get_contents($blogDir . '/Mohaaseb_com_Platform_Overview_ar.html'),
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
            'Purchase and Inventory Management: How Mohaaseb Connects Buying to Stock',
            'One System, Not Two: How Mohaaseb Unifies ERP and POS',
            'What Is Mohaaseb.com? A Full Overview of the ERP + POS Platform',
        ])->delete();
    }
};
