<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Plan;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
