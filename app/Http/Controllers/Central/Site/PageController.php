<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    function renderPage($lang = null,$slug)
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return view('central.site.page', get_defined_vars());
    }
}
