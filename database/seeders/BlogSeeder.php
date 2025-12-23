<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // read file "7 Essential ERP POS System Modules for Small Businesses.html" from /_blogs/ directory
        $contentEn_1 = file_get_contents(url('_blogs/ERP_System_vs_POS_System_Choosing_the_Right_Business_Management_Software.html'));
        $contentAr_1 = file_get_contents(url('_blogs/ERP_System_vs_POS_System_Choosing_the_Right_Business_Management_Software_ar.html'));
        $contentEn_2 = file_get_contents(url('_blogs/7_Essential_ERP_POS_System_Modules_for_Small_Businesses.html'));
        $contentAr_2 = file_get_contents(url('_blogs/7_Essential_ERP_POS_System_Modules_for_Small_Businesses_ar.html'));
        $contentEn_3 = file_get_contents(url('_blogs/Inventory_Management_Software_ERP_Essential_Features.html'));
        $contentAr_3 = file_get_contents(url('_blogs/Inventory_Management_Software_ERP_Essential_Features_ar.html'));
        $contentEn_4 = file_get_contents(url('_blogs/why-smart-erp-pos-systems-like-mohaaseb-are-game-changers-for-small-businesses.html'));
        $contentAr_4 = file_get_contents(url('_blogs/why-smart-erp-pos-systems-like-mohaaseb-are-game-changers-for-small-businesses_ar.html'));
        $contentEn_5 = file_get_contents(url('_blogs/Unlocking_Efficiency_Smart_ERP_and_POS_Systems_That_Transform_Your_Business.html'));
        $contentAr_5 = file_get_contents(url('_blogs/Unlocking_Efficiency_Smart_ERP_and_POS_Systems_That_Transform_Your_Business_ar.html'));
        $contentEn_6 = file_get_contents(url('_blogs/Unlocking_Business_Potential_with_Mohaaseb_The_Ultimate_Smart_ERP_POS_System_for_Inventory_Management.html'));
        $contentAr_6 = file_get_contents(url('_blogs/Unlocking_Business_Potential_with_Mohaaseb_The_Ultimate_Smart_ERP_POS_System_for_Inventory_Management_ar.html'));
        $contentEn_7 = file_get_contents(url('_blogs/Mohaaseb_ERP_System_POS_System_Inventory_Management_Complete_Guide_for_Small_Businesses.html'));
        $contentAr_7 = file_get_contents(url('_blogs/Mohaaseb_ERP_System_POS_System_Inventory_Management_Complete_Guide_for_Small_Businesses_ar.html'));
        $contentEn_8 = file_get_contents(url('_blogs/Mohaaseb_Stock_Management_Best_Practices_with_ERP_System_and_POS_System.html'));
        $contentAr_8 = file_get_contents(url('_blogs/Mohaaseb_Stock_Management_Best_Practices_with_ERP_System_and_POS_System_ar.html'));
        $contentEn_9 = file_get_contents(url('_blogs/Mohaaseb_Invoicing_Automation_From_POS_System_to_ERP_System_Accounting.html'));
        $contentAr_9 = file_get_contents(url('_blogs/Mohaaseb_Invoicing_Automation_From_POS_System_to_ERP_System_Accounting_ar.html'));
        $contentEn_10 = file_get_contents(url('_blogs/Mohaaseb_Multi_Location_Inventory_Managment_and_Stock_Management_with_Large_Reports.html'));
        $contentAr_10 = file_get_contents(url('_blogs/Mohaaseb_Multi_Location_Inventory_Managment_and_Stock_Management_with_Large_Reports_ar.html'));

        $blogs = [
            [
                'title_en' => 'ERP System vs. POS System: Choosing the Right Business Management Software',
                'title_ar' => 'نظام ERP مقابل نظام POS: اختيار برنامج إدارة الأعمال المناسب',
                'excerpt_en' => 'Learn the key differences, benefits, and integration of ERP and POS systems for effective business management.',
                'excerpt_ar' => 'تعرف على الفروقات الرئيسية والفوائد ودمج أنظمة ERP وPOS لإدارة أعمال أكثر فعالية.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(21),
            ],
            [
                'title_en' => '7 Essential ERP POS System Modules for Small Businesses',
                'title_ar' => '7 وحدات أساسية في أنظمة ERP POS للشركات الصغيرة',
                'excerpt_en' => 'Discover the 7 essential ERP POS system modules every small business needs to streamline operations, manage inventory, improve customer experience, and drive growth.',
                'excerpt_ar' => 'اكتشف الوحدات الأساسية السبعة في أنظمة ERP POS التي تحتاجها كل شركة صغيرة لتبسيط العمليات، إدارة المخزون، تحسين تجربة العملاء، ودفع النمو.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
            ],
            [
                'title_en' => 'Smart ERP & POS Systems: The Complete Guide for Modern Businesses',
                'title_ar' => 'أنظمة ERP & POS الذكية: الدليل الكامل للأعمال الحديثة',
                'excerpt_en' => 'A complete in-depth guide explaining how smart ERP and POS systems help businesses manage sales, inventory, accounting, and growth efficiently using automation and data analytics.',
                'excerpt_ar' => 'دليل شامل يشرح كيف تساعد أنظمة ERP وPOS الذكية الأعمال في إدارة المبيعات والمخزون والمحاسبة والنمو بكفاءة باستخدام الأتمتة وتحليل البيانات.',
                'content_en' => $contentEn_3,
                'content_ar' => $contentAr_3,
                'is_published' => true,
                'published_at' => now()->subDays(13),
            ],
            [
                'title_en' => 'Smart ERP & POS Systems: How Mohaaseb Helps Small Businesses Scale Faster',
                'title_ar' => 'أنظمة ERP & POS الذكية: كيف تساعد Mohaaseb الشركات الصغيرة على التوسع بشكل أسرع',
                'excerpt_en' => 'Learn how smart ERP & POS software like Mohaaseb helps small businesses streamline operations, manage inventory, and grow faster with real-time insights.',
                'excerpt_ar' => 'تعرف على كيفية مساعدة برامج ERP & POS الذكية مثل Mohaaseb للشركات الصغيرة في تبسيط العمليات، إدارة المخزون، والنمو بشكل أسرع من خلال الرؤى في الوقت الفعلي.',
                'content_en' => $contentEn_4,
                'content_ar' => $contentAr_4,
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ]
            ,
            [
                'title_en' => 'Unlocking Efficiency: Smart ERP and POS Systems That Transform Your Business',
                'title_ar' => 'فتح آفاق الكفاءة: أنظمة ERP وPOS الذكية التي تُحوّل أعمالك',
                'excerpt_en' => 'Discover how a smart ERP + POS system connects mohaaseb accounting, inventory, sales, and purchases to reduce errors, save time, and give you real-time visibility.',
                'excerpt_ar' => 'اكتشف كيف يربط نظام ERP + POS الذكي محاسبة موحاسب بالمخزون والمبيعات والمشتريات لتقليل الأخطاء وتوفير الوقت ومنحك رؤية فورية.',
                'content_en' => $contentEn_5,
                'content_ar' => $contentAr_5,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title_en' => 'Unlocking Business Potential with Mohaaseb: The Ultimate Smart ERP & POS System for Inventory Management',
                'title_ar' => 'إطلاق إمكانات عملك مع موحاسب: نظام ERP وPOS الذكي المتكامل لإدارة المخزون',
                'excerpt_en' => 'Explore how Mohaaseb ERP & POS improves inventory management with real-time stock updates, barcode scanning, analytics, multi-location control, and secure cloud access.',
                'excerpt_ar' => 'تعرّف على كيف يرفع موحاسب ERP وPOS كفاءة إدارة المخزون عبر تحديثات فورية، باركود، تحليلات، إدارة فروع متعددة، ووصول سحابي آمن.',
                'content_en' => $contentEn_6,
                'content_ar' => $contentAr_6,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title_en' => 'Mohaaseb ERP System & POS System: The Complete Guide for Small Businesses',
                'title_ar' => 'نظام موحاسب ERP ونظام POS: الدليل الكامل للشركات الصغيرة',
                'excerpt_en' => 'A comprehensive guide on how Mohaaseb ERP and POS systems help small businesses manage accounting, inventory, sales, and growth with smart features and automation.',
                'excerpt_ar' => 'دليل شامل حول كيفية مساعدة أنظمة موحاسب ERP وPOS للشركات الصغيرة في إدارة المحاسبة والمخزون والمبيعات والنمو من خلال ميزات ذكية وأتمتة.',
                'content_en' => $contentEn_7,
                'content_ar' => $contentAr_7,
            ],
            [
                'title_en' => 'Mohaaseb Stock Management: Best Practices with ERP System and POS System',
                'title_ar' => 'إدارة مخزون موحاسب: أفضل الممارسات مع نظام ERP ونظام POS',
                'excerpt_en' => 'Learn the best practices for stock management using Mohaaseb ERP and POS systems, including real-time tracking, barcode scanning, multi-location management, and analytics.',
                'excerpt_ar' => 'تعرف على أفضل الممارسات لإدارة المخزون باستخدام أنظمة موحاسب ERP وPOS، بما في ذلك التتبع الفوري، مسح الباركود، إدارة المواقع المتعددة، والتحليلات.',
                'content_en' => $contentEn_8,
                'content_ar' => $contentAr_8,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title_en' => 'Mohaaseb Invoicing Automation: From POS System to ERP System Accounting',
                'title_ar' => 'أتمتة الفواتير في موحاسب: من نظام POS إلى محاسبة نظام ERP',
                'excerpt_en' => 'Discover how Mohaaseb automates invoicing by integrating POS system sales with ERP accounting, reducing errors, saving time, and improving cash flow management.',
                'excerpt_ar' => 'اكتشف كيف تقوم موحاسب بأتمتة الفواتير من خلال دمج مبيعات نظام POS مع محاسبة نظام ERP، مما يقلل الأخطاء ويوفر الوقت ويحسن إدارة التدفق النقدي.',
                'content_en' => $contentEn_9,
                'content_ar' => $contentAr_9,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'Mohaaseb Multi-Location Inventory Management and Stock Management with Large Reports',
                'title_ar' => 'إدارة المخزون متعدد المواقع في موحاسب وإدارة المخزون مع تقارير شاملة',
                'excerpt_en' => 'Learn how Mohaaseb ERP and POS systems enable multi-location inventory management with real-time stock updates, transfer capabilities, and comprehensive reporting.',
                'excerpt_ar' => 'تعرف على كيفية تمكين أنظمة موحاسب ERP وPOS لإدارة المخزون متعدد المواقع من خلال تحديثات فورية للمخزون، قدرات النقل، والتقارير الشاملة.',
                'content_en' => $contentEn_10,
                'content_ar' => $contentAr_10,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($blogs as $data) {
            Blog::create(
                $data
            );
        }
    }
}
