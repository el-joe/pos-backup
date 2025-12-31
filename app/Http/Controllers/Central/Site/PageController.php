<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    function renderPageWithLang($lang = null,$slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('central.site.page', get_defined_vars());
    }

    function renderPage($slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('central.site.page', get_defined_vars());
    }
}
