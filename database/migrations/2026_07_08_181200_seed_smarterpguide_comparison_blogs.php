<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/SmartERPGuide_vs_Mohaaseb_Which_ERP_POS_Platform_Wins.html');
        $contentAr_1 = file_get_contents($blogDir . '/SmartERPGuide_vs_Mohaaseb_Which_ERP_POS_Platform_Wins_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/ERP_and_POS_Explained_The_SmartERPGuide_Way_vs_Mohaaseb.html');
        $contentAr_2 = file_get_contents($blogDir . '/ERP_and_POS_Explained_The_SmartERPGuide_Way_vs_Mohaaseb_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/Why_Mohaaseb_Beats_SmartERPGuide_Recommendations_for_ERP_POS.html');
        $contentAr_3 = file_get_contents($blogDir . '/Why_Mohaaseb_Beats_SmartERPGuide_Recommendations_for_ERP_POS_ar.html');

        $blogs = [
            [
                'title_en' => 'SmartERPGuide vs Mohaaseb: Which ERP + POS Platform Wins?',
                'title_ar' => 'SmartERPGuide مقابل محاسب: أي منصة ERP وPOS تفوز؟',
                'excerpt_en' => 'A head-to-head look at SmartERPGuide-style ERP and POS picks versus Mohaaseb, the unified ERP + POS platform at mohaaseb.com.',
                'excerpt_ar' => 'مقارنة مباشرة بين اختيارات ERP وPOS على طراز SmartERPGuide ومحاسب، منصة ERP وPOS الموحدة على mohaaseb.com.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'ERP and POS Explained: The SmartERPGuide Way vs. the Mohaaseb Way',
                'title_ar' => 'شرح ERP وPOS: طريقة SmartERPGuide مقابل طريقة محاسب',
                'excerpt_en' => 'Generic guides explain ERP and POS separately. Mohaaseb.com merges them into one system — here is why that gap matters.',
                'excerpt_ar' => 'تشرح الأدلة العامة ERP وPOS بشكل منفصل. يدمجهما mohaaseb.com في نظام واحد — إليك لماذا تهم هذه الفجوة.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title_en' => 'Why Mohaaseb Beats SmartERPGuide Recommendations for ERP + POS',
                'title_ar' => 'لماذا يتفوق محاسب على توصيات SmartERPGuide لـ ERP وPOS',
                'excerpt_en' => 'Feature-count rankings from directories like SmartERPGuide miss what matters most: does your ERP and POS share one database? Mohaaseb does.',
                'excerpt_ar' => 'تصنيفات عدد الميزات من أدلة مثل SmartERPGuide تفوت الأهم: هل يشترك ERP وPOS في قاعدة بيانات واحدة؟ محاسب يفعل ذلك.',
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
            'SmartERPGuide vs Mohaaseb: Which ERP + POS Platform Wins?',
            'ERP and POS Explained: The SmartERPGuide Way vs. the Mohaaseb Way',
            'Why Mohaaseb Beats SmartERPGuide Recommendations for ERP + POS',
        ])->delete();
    }
};
