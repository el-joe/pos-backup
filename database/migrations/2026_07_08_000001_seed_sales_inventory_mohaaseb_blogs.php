<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/Sales_and_Inventory_Management_System_with_Mohaaseb_ERP_POS.html');
        $contentAr_1 = file_get_contents($blogDir . '/Sales_and_Inventory_Management_System_with_Mohaaseb_ERP_POS_ar.html');

        $contentEn_2 = file_get_contents($blogDir . '/Mohaaseb_com_Smart_Sales_and_Inventory_Management_for_Growing_Businesses.html');
        $contentAr_2 = file_get_contents($blogDir . '/Mohaaseb_com_Smart_Sales_and_Inventory_Management_for_Growing_Businesses_ar.html');

        $contentEn_3 = file_get_contents($blogDir . '/Sales_and_Inventory_Management_System_Explained_ERP_POS_and_Mohaaseb.html');
        $contentAr_3 = file_get_contents($blogDir . '/Sales_and_Inventory_Management_System_Explained_ERP_POS_and_Mohaaseb_ar.html');

        $blogs = [
            [
                'title_en' => 'The Complete Guide to a Sales and Inventory Management System with Mohaaseb ERP & POS',
                'title_ar' => 'الدليل الكامل لنظام إدارة المبيعات والمخزون مع محاسب ERP وPOS',
                'excerpt_en' => 'Discover how Mohaaseb unifies ERP and POS into one sales and inventory management system that keeps stock, sales, and accounting in sync.',
                'excerpt_ar' => 'اكتشف كيف يوحّد محاسب بين ERP وPOS في نظام واحد لإدارة المبيعات والمخزون يحافظ على تزامن المخزون والمبيعات والمحاسبة.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'How Mohaaseb.com Powers Smart Sales and Inventory Management for Growing Businesses',
                'title_ar' => 'كيف يقدّم mohaaseb.com إدارة ذكية للمبيعات والمخزون للشركات النامية',
                'excerpt_en' => 'See why growing businesses replace disconnected POS and accounting tools with a single sales and inventory management system built on mohaaseb.com.',
                'excerpt_ar' => 'تعرف على سبب استبدال الشركات النامية لأدوات نقطة البيع والمحاسبة المنفصلة بنظام واحد لإدارة المبيعات والمخزون مبني على mohaaseb.com.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title_en' => 'Sales and Inventory Management System Explained: ERP, POS, and Mohaaseb in Action',
                'title_ar' => 'شرح نظام إدارة المبيعات والمخزون: ERP وPOS ومحاسب في العمل',
                'excerpt_en' => 'A plain-language breakdown of ERP vs. POS and how Mohaaseb combines both into one sales and inventory management system.',
                'excerpt_ar' => 'شرح مبسط للفرق بين ERP وPOS وكيف يجمع محاسب بينهما في نظام واحد لإدارة المبيعات والمخزون.',
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
            'The Complete Guide to a Sales and Inventory Management System with Mohaaseb ERP & POS',
            'How Mohaaseb.com Powers Smart Sales and Inventory Management for Growing Businesses',
            'Sales and Inventory Management System Explained: ERP, POS, and Mohaaseb in Action',
        ])->delete();
    }
};
