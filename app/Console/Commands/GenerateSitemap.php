<?php

namespace App\Console\Commands;

use App\Helpers\SeoHelper;
use App\Models\Blog;
use App\Models\Country;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        $locales = SeoHelper::getAllLocalesWithCountry();

        // الصفحات الثابتة
        $sitemap->add(url('/'));
        foreach ($locales as $locale) {
            $sitemap->add(url("/{$locale}"));
            $sitemap->add(url("/{$locale}/faqs"));
        }
        // $sitemap->add(url('/ar'));
        // $sitemap->add(url('/en'));
        $sitemap->add(url('/pricing'));
        $sitemap->add(url('/pricing/compare'));
        $sitemap->add(url('/blogs'));
        $sitemap->add(url('/faqs'));

        Page::published()->get()->each(function (Page $page) use ($sitemap,$locales) {
            $sitemap->add(url("/{$page->slug}"));
            foreach ($locales as $locale) {
                $sitemap->add(url("/{$locale}/{$page->slug}"));
            }
        });

        // الصفحات الديناميكية
        Blog::all()->each(function($blog) use ($sitemap, $locales) {
            foreach ($locales as $locale) {
                $sitemap->add(url("/{$locale}/blogs/{$blog->slug}"));
            }
        });

        // حفظ الـ sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
