<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Plan;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use RalphJSmit\Laravel\SEO\Support\ImageMeta;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomeController extends Controller
{
    function index($lang = null)
    {
        if($lang != null){
            app()->setLocale($lang);
            session(['locale' => $lang]);

            return redirect()->route('central-home');
        }

        $sliders = Slider::where('active', true)->orderBy('number', 'asc')->get();
        $blogs = Blog::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('central.site.home',get_defined_vars());
    }

    function blogs()
    {
        $blogs = Blog::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('central.site.blogs', get_defined_vars());
    }

    function blogDetails($slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        $alternates = [
            new AlternateTag('en', url('/en')),
            new AlternateTag('ar', url('/ar')),
        ];

        $seoData = new SEOData(
            title : $blog->title,
            description : $blog->excerpt,
            author: 'codefanz.com',
            image : asset('favicon_io/apple-touch-icon.png'),
            url : url()->current(),
            robots : 'index, follow',
            canonical_url : url('/'),
            enableTitleSuffix: true,
            type: "website",
            site_name: "Mohaaseb",
            favicon: asset('favicon_io/favicon.ico'),
            locale: app()->getLocale() == 'en' ? 'en_US' : 'ar_AR',

            openGraphTitle: $blog->title,
            imageMeta: new ImageMeta(asset('favicon_io/apple-touch-icon.png')),

            twitter_username: "@mohaaseb",

            // المقالات/الصفحات الديناميكية
            published_time: Carbon::now(), // مثال للصفحات الجديدة
            modified_time: Carbon::now(),
            articleBody: null,
            section: null,
            tags: ['ERP', 'POS', 'Accounting', 'Inventory', 'Business Management'],

            // Schema (JSON-LD)
            schema: new SchemaCollection([
                [
                    "@context" => "https://schema.org",
                    "@type" => "SoftwareApplication",
                    "name" => "Mohaaseb ERP",
                    "applicationCategory" => "BusinessApplication",
                    "operatingSystem" => "Web",
                    "url" => url('/'),
                    "offers" => [
                        "@type" => "Offer",
                        "price" => "0",
                        "priceCurrency" => "USD"
                    ]
                ]
            ]),

            alternates: $alternates
        );

        return view('central.site.blog-details', get_defined_vars());
    }

    function contactUs(Request $request)
    {
        $request->validate([
            'fname'=>'required|string|max:255',
            'lname'=>'required|string|max:255',
            'email'=>'required|email|max:255',
            'phone'=>'required|string|max:50',
            'message'=>'required|string|max:2000',
        ]);

        // You can add logic here to handle the contact form submission,
        // such as sending an email or storing the message in the database.
        $contact = Contact::create([
            'name'=>$request->fname . ' ' . $request->lname,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'message'=>$request->message,
        ]);

        Mail::to(env('ADMIN_EMAIL','support@codefanz.com'))->send(new \App\Mail\ContactUsMail($contact));

        return redirect()->back()->with('success','Your message has been sent successfully.');
    }

    function checkout()
    {
        return view('central.site.checkout',get_defined_vars());
    }

    function pricingCompare()
    {
        $plans = Plan::all();
        return view('central.site.pricing-compare',get_defined_vars());
    }
}
