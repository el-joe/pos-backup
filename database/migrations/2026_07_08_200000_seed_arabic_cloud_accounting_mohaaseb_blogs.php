<?php

use App\Models\Blog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $blogDir = public_path('_blogs');

        $contentEn_1 = file_get_contents($blogDir . '/Arabic_Cloud_Accounting_App_Why_Mohaaseb_Leads.html');
        $contentAr_1 = file_get_contents($blogDir . '/Arabic_Cloud_Accounting_App_Why_Mohaaseb_Leads_ar.html');
        $contentEn_2 = file_get_contents($blogDir . '/How_a_Mohaaseb_Handles_Month_End_Closing_in_Mohaaseb.html');
        $contentAr_2 = file_get_contents($blogDir . '/How_a_Mohaaseb_Handles_Month_End_Closing_in_Mohaaseb_ar.html');
        $contentEn_3 = file_get_contents($blogDir . '/Mohaaseb_com_Vs_Traditional_Accounting_Software.html');
        $contentAr_3 = file_get_contents($blogDir . '/Mohaaseb_com_Vs_Traditional_Accounting_Software_ar.html');

        $blogs = [
            [
                'title_en' => 'Why Mohaaseb Is the Arabic Cloud Accounting App Growing Businesses Trust',
                'title_ar' => 'لماذا يُعد محاسب برنامج محاسبة سحابي عربي تثق به الشركات النامية',
                'excerpt_en' => 'A true Arabic cloud accounting app means native RTL, bilingual documents, and regional compliance. See why Mohaaseb was built local-first.',
                'excerpt_ar' => 'برنامج محاسبة سحابي عربي حقيقي يعني واجهات RTL أصيلة، ومستندات ثنائية اللغة، وتوافقًا إقليميًا. تعرف على كيف بُني محاسب محليًا من الأساس.',
                'content_en' => $contentEn_1,
                'content_ar' => $contentAr_1,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'How Your محاسب Closes the Month Faster with Mohaaseb',
                'title_ar' => 'كيف يُغلق محاسبك الشهر بشكل أسرع مع محاسب',
                'excerpt_en' => 'Month-end closing gets fast when accounting, ERP, and POS live in one system. See how Mohaaseb turns closing into a review, not a rebuild.',
                'excerpt_ar' => 'يصبح إغلاق نهاية الشهر سريعًا عندما تعيش المحاسبة وERP ونقطة البيع في نظام واحد. تعرف على كيف يحوّل محاسب الإغلاق إلى مراجعة لا إعادة بناء.',
                'content_en' => $contentEn_2,
                'content_ar' => $contentAr_2,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'Mohaaseb.com vs. Traditional Accounting Software: What Changes',
                'title_ar' => 'mohaaseb.com مقابل برامج المحاسبة التقليدية: ما الذي يتغير',
                'excerpt_en' => 'Compare traditional accounting software to mohaaseb.com side by side and see who benefits most from switching to a unified cloud platform.',
                'excerpt_ar' => 'قارن بين برامج المحاسبة التقليدية وmohaaseb.com جنبًا إلى جنب، واكتشف من يستفيد أكثر من التحول إلى منصة سحابية موحّدة.',
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
            'Why Mohaaseb Is the Arabic Cloud Accounting App Growing Businesses Trust',
            'How Your محاسب Closes the Month Faster with Mohaaseb',
            'Mohaaseb.com vs. Traditional Accounting Software: What Changes',
        ])->delete();
    }
};
