<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected array $titles = [
        'Manage Purchases and Stock with Mohaaseb: The ERP and POS System Built for Growing Businesses',
        "From ERP to POS: How Mohaaseb.com Simplifies Purchase Orders and Stock Control",
        "5 Smart Ways Mohaaseb's POS and ERP Tools Help You Manage Purchases and Stock",
    ];

    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $blogs = [
            [
                'title_en' => $this->titles[0],
                'title_ar' => 'إدارة المشتريات والمخزون مع محاسب: نظام ERP وPOS المصمم للأعمال المتنامية',
                'excerpt_en' => 'See how Mohaaseb combines ERP and POS in one system so you can manage purchases and stock with real-time accuracy instead of spreadsheets.',
                'excerpt_ar' => 'تعرف على كيف يجمع محاسب بين ERP وPOS في نظام واحد لإدارة المشتريات والمخزون بدقة فورية بدلاً من الجداول.',
                'content_en' => file_get_contents($blogDir.'/Manage_Purchases_and_Stock_with_Mohaaseb_ERP_POS.html'),
                'content_ar' => file_get_contents($blogDir.'/Manage_Purchases_and_Stock_with_Mohaaseb_ERP_POS_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => $this->titles[1],
                'title_ar' => 'من ERP إلى POS: كيف يبسّط mohaaseb.com أوامر الشراء والتحكم في المخزون',
                'excerpt_en' => 'Mohaaseb.com connects purchase orders to real-time stock control by running ERP purchasing and POS sales on one shared ledger.',
                'excerpt_ar' => 'يربط mohaaseb.com أوامر الشراء بالتحكم الفوري في المخزون من خلال تشغيل مشتريات ERP ومبيعات POS على دفتر واحد مشترك.',
                'content_en' => file_get_contents($blogDir.'/From_ERP_to_POS_Mohaaseb_Purchase_Orders_Stock_Control.html'),
                'content_ar' => file_get_contents($blogDir.'/From_ERP_to_POS_Mohaaseb_Purchase_Orders_Stock_Control_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => $this->titles[2],
                'title_ar' => 'خمس طرق ذكية يساعدك بها محاسب في إدارة المشتريات والمخزون عبر أدوات POS وERP',
                'excerpt_en' => 'Five concrete ways Mohaaseb\'s combined POS and ERP tools help you manage purchases and stock better than separate systems.',
                'excerpt_ar' => 'خمس طرق ملموسة تساعدك بها أدوات محاسب المشتركة بين POS وERP على إدارة المشتريات والمخزون بشكل أفضل من الأنظمة المنفصلة.',
                'content_en' => file_get_contents($blogDir.'/5_Smart_Ways_Mohaaseb_POS_ERP_Manage_Purchases_and_Stock.html'),
                'content_ar' => file_get_contents($blogDir.'/5_Smart_Ways_Mohaaseb_POS_ERP_Manage_Purchases_and_Stock_ar.html'),
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($blogs as $data) {
            Blog::updateOrCreate(['title_en' => $data['title_en']], $data);
        }
    }

    public function down(): void
    {
        Blog::whereIn('title_en', $this->titles)->delete();
    }
};
