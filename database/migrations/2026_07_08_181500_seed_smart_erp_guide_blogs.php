<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/Smart_ERP_Guide_How_Mohaaseb_Powers_Modern_POS_Businesses.html');
        $contentAr_1 = file_get_contents($blogDir . '/Smart_ERP_Guide_How_Mohaaseb_Powers_Modern_POS_Businesses_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/ERP_and_POS_Explained_Why_Mohaaseb_com_Unifies_Both.html');
        $contentAr_2 = file_get_contents($blogDir . '/ERP_and_POS_Explained_Why_Mohaaseb_com_Unifies_Both_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/Mohaaseb_com_Smart_ERP_Guide_Mastering_POS_Operations_End_to_End.html');
        $contentAr_3 = file_get_contents($blogDir . '/Mohaaseb_com_Smart_ERP_Guide_Mastering_POS_Operations_End_to_End_ar.html');

        $blogs = [
            [
                'title_en' => 'The Smart ERP Guide: How Mohaaseb Powers Modern POS Businesses',
                'title_ar' => 'دليل الـERP الذكي: كيف يشغّل محاسب أعمال الـPOS الحديثة',
                'excerpt_en' => 'A smart ERP guide to how Mohaaseb unifies ERP and POS into one real-time platform at mohaaseb.com.',
                'excerpt_ar' => 'دليل ذكي لكيفية جمع محاسب بين ERP وPOS في منصة واحدة لحظية على mohaaseb.com.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'ERP and POS Explained: Why mohaaseb.com Unifies Both',
                'title_ar' => 'ERP وPOS ببساطة: لماذا توحّد mohaaseb.com بينهما',
                'excerpt_en' => 'ERP and POS explained in plain terms, and why mohaaseb.com merges them into a single Mohaaseb platform.',
                'excerpt_ar' => 'شرح مبسّط لـERP وPOS، ولماذا تدمجهما mohaaseb.com في منصة محاسب واحدة.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => "mohaaseb.com's Smart ERP Guide: Mastering POS Operations End to End",
                'title_ar' => 'دليل mohaaseb.com الذكي لـERP: إتقان عمليات الـPOS من البداية للنهاية',
                'excerpt_en' => 'Follow this smart ERP guide to see how mohaaseb.com connects daily POS operations to a full ERP workflow.',
                'excerpt_ar' => 'اتبع هذا الدليل الذكي للـERP لترى كيف تربط mohaaseb.com عمليات الـPOS اليومية بسير عمل ERP متكامل.',
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
            'The Smart ERP Guide: How Mohaaseb Powers Modern POS Businesses',
            'ERP and POS Explained: Why mohaaseb.com Unifies Both',
            "mohaaseb.com's Smart ERP Guide: Mastering POS Operations End to End",
        ])->delete();
    }
};
