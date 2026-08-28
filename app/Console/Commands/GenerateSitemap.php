<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
    protected $description = 'Generate public/sitemap.xml and public/robots.txt';

    private const LOCALES = ['en', 'ar'];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateSitemap();
        $this->generateRobots();

        $this->info('Sitemap and robots.txt generated successfully!');
    }

    private function generateSitemap(): void
    {
        $urls = [];

        // Homepage (en + ar)
        $homeAlternates = collect(self::LOCALES)->map(fn ($locale) => [
            'lang' => $locale,
            'href' => url("/{$locale}"),
        ])->all();

        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
            'alternates' => $homeAlternates,
        ];

        foreach (self::LOCALES as $locale) {
            $urls[] = [
                'loc' => url("/{$locale}"),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
                'alternates' => $homeAlternates,
            ];
        }

        // Pricing
        $urls[] = [
            'loc' => url('/pricing'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ];

        // FAQs
        $urls[] = [
            'loc' => url('/faqs'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];

        // Blogs index
        $urls[] = [
            'loc' => url('/blogs'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];

        // Published blogs (en + ar)
        Blog::published()->get()->each(function (Blog $blog) use (&$urls) {
            $lastmod = ($blog->updated_at ?? Carbon::now())->toAtomString();

            $alternates = collect(self::LOCALES)->map(fn ($locale) => [
                'lang' => $locale,
                'href' => url("/{$locale}/blogs/{$blog->slug}"),
            ])->all();

            foreach (self::LOCALES as $locale) {
                $urls[] = [
                    'loc' => url("/{$locale}/blogs/{$blog->slug}"),
                    'lastmod' => $lastmod,
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                    'alternates' => $alternates,
                ];
            }
        });

        // Published static pages
        Page::published()->get()->each(function (Page $page) use (&$urls) {
            $urls[] = [
                'loc' => url("/{$page->slug}"),
                'lastmod' => ($page->updated_at ?? Carbon::now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        });

        // Contact
        $urls[] = [
            'loc' => url('/contact'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'yearly',
            'priority' => '0.5',
        ];

        $xml = $this->buildXml($urls);

        file_put_contents(public_path('sitemap.xml'), $xml);
    }

    private function buildXml(array $urls): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $dom->appendChild($urlset);

        foreach ($urls as $entry) {
            $urlEl = $dom->createElement('url');

            $loc = $dom->createElement('loc');
            $loc->appendChild($dom->createTextNode($entry['loc']));
            $urlEl->appendChild($loc);

            $lastmod = $dom->createElement('lastmod');
            $lastmod->appendChild($dom->createTextNode($entry['lastmod']));
            $urlEl->appendChild($lastmod);

            $changefreq = $dom->createElement('changefreq');
            $changefreq->appendChild($dom->createTextNode($entry['changefreq']));
            $urlEl->appendChild($changefreq);

            $priority = $dom->createElement('priority');
            $priority->appendChild($dom->createTextNode($entry['priority']));
            $urlEl->appendChild($priority);

            foreach (($entry['alternates'] ?? []) as $alternate) {
                $link = $dom->createElement('xhtml:link');
                $link->setAttribute('rel', 'alternate');
                $link->setAttribute('hreflang', $alternate['lang']);
                $link->setAttribute('href', $alternate['href']);
                $urlEl->appendChild($link);
            }

            $urlset->appendChild($urlEl);
        }

        return $dom->saveXML();
    }

    private function generateRobots(): void
    {
        $sitemapUrl = rtrim(config('app.url'), '/') . '/sitemap.xml';

        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /admin/',
            'Disallow: /login/',
            'Disallow: /dashboard/',
            'Disallow: /api/',
            'Disallow: /private/',
            'Disallow: /*.json$',
            'Disallow: /*?*search=',
            'Disallow: /*?*q=',
            'Disallow: /cpanel',
            'Disallow: /register',
            'Disallow: /payment',
            '',
            'User-agent: AhrefsBot',
            'Disallow: /',
            '',
            'User-agent: SemrushBot',
            'Disallow: /',
            '',
            'User-agent: MJ12bot',
            'Disallow: /',
            '',
            "Sitemap: {$sitemapUrl}",
        ];

        file_put_contents(public_path('robots.txt'), implode("\n", $lines) . "\n");
    }
}
