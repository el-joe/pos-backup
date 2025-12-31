<?php

namespace App\Console\Commands;

use App\Models\Blog;
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

        // الصفحات الثابتة
        $sitemap->add(url('/'));
        $sitemap->add(url('/ar'));
        $sitemap->add(url('/en'));
        $sitemap->add(url('/pricing'));
        $sitemap->add(url('/pricing/compare'));
        $sitemap->add(url('/blogs'));
        $sitemap->add(url('/faqs'));
        $sitemap->add(url('/ar/faqs'));
        $sitemap->add(url('/en/faqs'));

        Page::published()->get()->each(function (Page $page) use ($sitemap) {
            $sitemap->add(url("/{$page->slug}"));
            $sitemap->add(url("/ar/{$page->slug}"));
            $sitemap->add(url("/en/{$page->slug}"));
        });

        // الصفحات الديناميكية
        Blog::all()->each(function($blog) use ($sitemap) {
            $sitemap->add(url("ar/blogs/{$blog->slug}"));
            $sitemap->add(url("en/blogs/{$blog->slug}"));
        });

        // حفظ الـ sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
