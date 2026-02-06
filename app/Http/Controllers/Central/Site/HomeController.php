<?php

namespace App\Http\Controllers\Central\Site;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Faq;
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
        $sliders = Slider::where('active', true)->orderBy('number', 'asc')->get();
        $blogs = Blog::published()
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return landingLayoutView('home',get_defined_vars());
    }

    function blogs($lang = null)
    {
        $blogs = Blog::published()
            ->orderByDesc('id')
            ->paginate(12);

        $seoData = SeoHelper::render('blogs');

        return landingLayoutView('blogs', get_defined_vars());
    }

    function blogDetailsNoLang($slug)
    {
        return $this->blogDetails(app()->getLocale(), $slug);
    }

    function blogDetails($lang, $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        $imageUrl = $blog->og_image_path;
        $publishedAt = $blog->published_at ?: $blog->created_at;

        $seoData = SeoHelper::render('blog-details', [
            'title' => $blog->title,
            'description' => $blog->excerpt,
            'image' => $imageUrl,
            'published_time' => $publishedAt,
            'modified_time' => $blog->updated_at,
            'slug' => $slug,
            'canonical_url' => url("/{$lang}/blogs/{$slug}"),
            'content' => $blog->content,
        ]);

        return landingLayoutView('blog-details', get_defined_vars());
    }

    function faqs($lang = null)
    {
        if($lang == null){
            $lang = app()->getLocale() . '-' . session('country', 'eg');
        }
        $faqs = Faq::published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $seoData = SeoHelper::render('faqs', [
            'canonical_url' => url("/{$lang}/faqs"),
            'faq_items' => $faqs->map(fn (Faq $faq) => [
                'question' => $faq->question,
                'answer' => strip_tags($faq->answer),
            ])->all(),
        ]);

        return landingLayoutView('faqs', get_defined_vars());
    }

    function contactUsView()
    {
        return landingLayoutView('contact', get_defined_vars());
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

        Mail::to(env('ADMIN_EMAIL','eljoe1717@gmail.com'))->send(new \App\Mail\ContactUsMail($contact));

        return redirect()->back()->with('success','Your message has been sent successfully.');
    }

    function checkout()
    {
        return landingLayoutView('checkout',get_defined_vars());
    }

    function pricingCompare()
    {
        $plans = Plan::all();

        $seoData = SeoHelper::render('pricing-compare');
        return landingLayoutView('pricing-compare',get_defined_vars());
    }

    function pricing()
    {
        $seoData = SeoHelper::render('pricing');
        return landingLayoutView('pricing',get_defined_vars());
    }

    function changeLanguage($locale) {
        if (!in_array($locale, ['en', 'ar'], true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        // redirect to same route but with new lang parameter
        $previousUrl = url()->previous();
        // refactor this to be like en-us or ar-eg
        $newUrl = preg_replace('/\/(en|ar)(-[a-zA-Z]{2})?/', '/' . $locale . '-' . session('country', 'eg'), $previousUrl);

        // if(str_contains($previousUrl, '/en/') || str_contains($previousUrl, '/ar/')){
        //     $newUrl = preg_replace('/\/(en|ar)\//', '/' . $locale . '/', $previousUrl);
        // }else{
        //     if($previousUrl == url('/')){
        //         $newUrl = url('/' . $locale);
        //     }else{
        //         $newUrl = $previousUrl;
        //     }
        // }
        return redirect($newUrl);
    }
}
