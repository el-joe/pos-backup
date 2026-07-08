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
                'title_en' => 'ERP, CRM & POS Unified: How Mohaaseb Brings Every System Together',
                'title_ar' => 'ERP وCRM وPOS في منصة واحدة: كيف يوحّد محاسب جميع أنظمتك',
                'excerpt_en' => 'See how Mohaaseb merges ERP, CRM, and POS into one cloud platform so sales, customers, and accounting always stay in sync.',
                'excerpt_ar' => 'تعرف على كيف يدمج محاسب أنظمة ERP وCRM وPOS في منصة سحابية واحدة لتبقى المبيعات والعملاء والمحاسبة متزامنة دائمًا.',
                'content_en' => file_get_contents($blogDir . '/ERP_CRM_POS_Unified_by_Mohaaseb.html'),
                'content_ar' => file_get_contents($blogDir . '/ERP_CRM_POS_Unified_by_Mohaaseb_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'The Complete ERP Guide for Mohaaseb.com Users',
                'title_ar' => 'الدليل الكامل لـ ERP لمستخدمي mohaaseb.com',
                'excerpt_en' => 'A practical guide to how Mohaaseb handles ERP essentials on mohaaseb.com and connects them directly to your POS.',
                'excerpt_ar' => 'دليل عملي حول كيفية تعامل محاسب مع أساسيات ERP على mohaaseb.com وربطها مباشرة بنقطة البيع.',
                'content_en' => file_get_contents($blogDir . '/Complete_ERP_Guide_for_Mohaaseb_com.html'),
                'content_ar' => file_get_contents($blogDir . '/Complete_ERP_Guide_for_Mohaaseb_com_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => "POS That Talks to Your ERP: Inside Mohaaseb's Unified System",
                'title_ar' => 'POS يتحدث مباشرة مع ERP: داخل نظام محاسب الموحّد',
                'excerpt_en' => 'Discover how every sale on the Mohaaseb POS updates your ERP inventory and accounting instantly, with no manual reconciliation.',
                'excerpt_ar' => 'اكتشف كيف تُحدّث كل عملية بيع على نقطة بيع محاسب مخزون ERP والمحاسبة فورًا، دون أي مطابقة يدوية.',
                'content_en' => file_get_contents($blogDir . '/POS_That_Talks_to_ERP_Mohaaseb.html'),
                'content_ar' => file_get_contents($blogDir . '/POS_That_Talks_to_ERP_Mohaaseb_ar.html'),
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
            'ERP, CRM & POS Unified: How Mohaaseb Brings Every System Together',
            'The Complete ERP Guide for Mohaaseb.com Users',
            "POS That Talks to Your ERP: Inside Mohaaseb's Unified System",
        ])->delete();
    }
};
