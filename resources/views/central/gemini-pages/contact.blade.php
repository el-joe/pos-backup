@extends('layouts.central.gemini.layout')

@section('content')
    <main itemscope itemtype="https://schema.org/ContactPage">
    <div class="hidden" itemprop="about" itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="Mohaaseb">
        <meta itemprop="email" content="{{ __('gemini-landing.contact_info.sales_email') }}">
        <meta itemprop="telephone" content="{{ __('gemini-landing.contact_info.phone') }}">
    </div>
    <header class="pt-32 pb-12 bg-white dark:bg-slate-900 text-center transition-colors duration-300">
        <div class="container mx-auto px-6">
            <p class="text-brand-500 font-bold uppercase tracking-wide text-sm mb-2">{{ __('gemini-landing.contact_page.badge') }}</p>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-4">{{ __('gemini-landing.contact_page.title') }}</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-xl mx-auto">{{ __('gemini-landing.contact_page.subtitle') }}</p>
        </div>
    </header>

    <section class="flex-grow container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-brand-500 transition">
                        <div class="w-10 h-10 bg-brand-50 dark:bg-slate-700 rounded-lg flex items-center justify-center text-brand-500 mb-4 text-xl"><i class="fa-solid fa-envelope"></i></div>
                        <h3 class="font-bold text-slate-800 dark:text-white mb-1">{{ __('gemini-landing.contact_page.email_sales_title') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ __('gemini-landing.contact_page.email_sales_subtitle') }}</p>
                        <a href="mailto:{{ __('gemini-landing.contact_info.sales_email') }}" class="text-brand-600 font-bold text-sm hover:underline">{{ __('gemini-landing.contact_info.sales_email') }}</a>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-brand-500 transition">
                        <div class="w-10 h-10 bg-blue-50 dark:bg-slate-700 rounded-lg flex items-center justify-center text-blue-500 mb-4 text-xl"><i class="fa-solid fa-headset"></i></div>
                        <h3 class="font-bold text-slate-800 dark:text-white mb-1">{{ __('gemini-landing.contact_page.help_support_title') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ __('gemini-landing.contact_page.help_support_subtitle') }}</p>
                        <a href="mailto:{{ __('gemini-landing.contact_info.support_email') }}" class="text-blue-600 font-bold text-sm hover:underline">{{ __('gemini-landing.contact_info.support_email') }}</a>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 text-lg">{{ __('gemini-landing.contact_page.locations_title') }}</h3>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <i class="fa-solid fa-location-dot text-brand-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-slate-700 dark:text-slate-200">{{ __('gemini-landing.contact_page.riyadh_title') }}</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{!! __('gemini-landing.contact_page.riyadh_address_html') !!}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <i class="fa-solid fa-location-dot text-brand-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-slate-700 dark:text-slate-200">{{ __('gemini-landing.contact_page.cairo_title') }}</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{!! __('gemini-landing.contact_page.cairo_address_html') !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 h-48 bg-slate-200 dark:bg-slate-700 rounded-xl overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <button class="bg-white dark:bg-slate-800 px-4 py-2 rounded-full shadow-lg font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-700 dark:text-white transition">{{ __('gemini-landing.contact_page.view_maps') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-8 lg:p-10 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 transition-colors">
                <form action="{{ route('contact-us') }}" method="POST" class="bg-white dark:bg-slate-800 p-8 rounded-3xl text-slate-700 dark:text-slate-300 shadow-2xl transition-colors duration-300">
                    @csrf
                    <h3 class="text-2xl font-bold text-brand-dark dark:text-white mb-6">{{ __('gemini-landing.home.contact_form_heading') }}</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_first_name') }}</label>
                            <input type="text" name="fname" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_first_name') }}" required>
                            @error('fname')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_last_name') }}</label>
                            <input type="text" name="lname" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_last_name') }}" required>
                            @error('lname')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_phone') }}</label>
                            <input type="text" name="phone" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_phone') }}" required>
                            @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_email') }}</label>
                            <input type="email" name="email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_email') }}" required>
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">{{ __('gemini-landing.home.contact_message') }}</label>
                        <textarea name="message" rows="3" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="{{ __('gemini-landing.home.ph_message') }}"></textarea>
                        @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition shadow-lg">{{ __('gemini-landing.home.contact_submit') }}</button>
                </form>
            </div>

        </div>
    </section>
    </main>
@endsection
