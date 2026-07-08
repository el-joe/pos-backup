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
                'title_en' => 'Best Cloud Financial Management Software: A Complete Guide with Mohaaseb',
                'title_ar' => 'أفضل برنامج إدارة مالية سحابي: دليل شامل مع محاسب',
                'excerpt_en' => 'Discover why Mohaaseb is the best cloud financial management software, combining ERP and POS in one platform for accurate, real-time accounting.',
                'excerpt_ar' => 'اكتشف لماذا يُعد محاسب أفضل برنامج إدارة مالية سحابي، حيث يجمع بين ERP وPOS في منصة واحدة لمحاسبة دقيقة وفورية.',
                'content_en' => file_get_contents($blogDir . '/Best_Cloud_Financial_Management_Software_with_Mohaaseb.html'),
                'content_ar' => file_get_contents($blogDir . '/Best_Cloud_Financial_Management_Software_with_Mohaaseb_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title_en' => 'ERP vs. POS: How Mohaaseb Combines Both for Accountants and Small Businesses',
                'title_ar' => 'ERP مقابل POS: كيف يجمع محاسب بينهما لصالح المحاسبين والشركات الصغيرة',
                'excerpt_en' => 'Learn the difference between ERP and POS systems, and how Mohaaseb connects both to remove manual reconciliation for every accountant.',
                'excerpt_ar' => 'تعرف على الفرق بين أنظمة ERP وPOS، وكيف يربط محاسب بينهما لإلغاء التسوية اليدوية عن كل محاسب.',
                'content_en' => file_get_contents($blogDir . '/ERP_vs_POS_How_Mohaaseb_Combines_Both_for_Accountants.html'),
                'content_ar' => file_get_contents($blogDir . '/ERP_vs_POS_How_Mohaaseb_Combines_Both_for_Accountants_ar.html'),
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title_en' => 'Discover Mohaaseb.com: The All-in-One ERP & POS Accounting Platform',
                'title_ar' => 'اكتشف mohaaseb.com: منصة ERP وPOS المتكاملة لإدارة أعمالك',
                'excerpt_en' => 'A tour of mohaaseb.com, the cloud platform where accounting, inventory, and POS work together in one dashboard.',
                'excerpt_ar' => 'جولة في mohaaseb.com، المنصة السحابية التي تعمل فيها المحاسبة والمخزون ونقطة البيع معًا في لوحة تحكم واحدة.',
                'content_en' => file_get_contents($blogDir . '/Discover_Mohaaseb_com_All_in_One_ERP_POS_Platform.html'),
                'content_ar' => file_get_contents($blogDir . '/Discover_Mohaaseb_com_All_in_One_ERP_POS_Platform_ar.html'),
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
            'Best Cloud Financial Management Software: A Complete Guide with Mohaaseb',
            'ERP vs. POS: How Mohaaseb Combines Both for Accountants and Small Businesses',
            'Discover Mohaaseb.com: The All-in-One ERP & POS Accounting Platform',
        ])->delete();
    }
};
