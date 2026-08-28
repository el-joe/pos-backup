<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\SeoService;

class PageController extends Controller
{
    function renderPageWithLang($lang = null,$slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        $seoHtml = $this->buildSeoHtml($page);

        return view('central.site.page', get_defined_vars());
    }

    function renderPage($slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        $seoHtml = $this->buildSeoHtml($page);

        return view('central.site.page', get_defined_vars());
    }

    private function buildSeoHtml(Page $page): string
    {
        return SeoService::page([
            'title' => $page->title . ' | Mohaaseb',
            'description' => (string) $page->short_description,
            'canonical' => route('static-page', ['slug' => $page->slug]),
            'locale' => app()->getLocale() === 'ar' ? 'ar_EG' : 'en_US',
        ]);
    }
}
