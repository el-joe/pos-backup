<footer class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 pt-16 pb-8 transition-colors duration-300">
    @php($lang = $__currentLang ?? app()->getLocale())
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div>
                <a href="#" class="flex items-center gap-2.5 mb-6 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-brand-600 rounded-lg flex items-center justify-center shadow-md text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-slate-800 tracking-tight dark:text-white">
                        @if(app()->getLocale() == 'ar')
                        محاسب
                        @else
                        Mohaaseb
                        @endif
                        <span class="text-brand-500">.</span>
                    </span>
                </a>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs">{{ __('website.footer.brand_description') }}</p>

            </div>
            <div>
                <h4 class="font-bold text-brand-dark dark:text-white mb-4">{{ __('website.footer.legal') }}</h4>
                <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('lang.static-page.localized', ['lang' => $lang, 'slug' => 'about-us']) }}" class="hover:text-brand-500">{{ __('website.footer.legal_links.about_us') }}</a></li>
                    <li><a href="{{ route('lang.static-page.localized', ['lang' => $lang, 'slug' => 'privacy-policy']) }}" class="hover:text-brand-500">{{ __('website.footer.legal_links.privacy_policy') }}</a></li>
                    <li><a href="{{ route('lang.static-page.localized', ['lang' => $lang, 'slug' => 'terms-conditions']) }}" class="hover:text-brand-500">{{ __('website.footer.legal_links.terms_of_service') }}</a></li>
                    <li><a href="{{ route('lang.static-page.localized', ['lang' => $lang, 'slug' => 'refund-cancellation-policy']) }}" class="hover:text-brand-500">{{ __('website.footer.legal_links.refund_cancellation_policy') }}</a></li>
                    <li><a href="{{ route('lang.static-page.localized', ['lang' => $lang, 'slug' => 'fair-usage-policy']) }}" class="hover:text-brand-500">{{ __('website.footer.legal_links.fair_usage_policy') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-brand-dark dark:text-white mb-4">{{ __('website.footer.resources') }}</h4>
                <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="#" class="hover:text-brand-500">{{ __('website.footer.resources_links.documentation') }}</a></li>
                    <li><a href="{{ route('lang.faqs.index', ['lang' => $lang]) }}" class="hover:text-brand-500">{{ __('website.footer.resources_links.faqs') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-brand-dark dark:text-white mb-4">{{ __('website.footer.help_center') }}</h4>
                <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="{{ route('contact') }}" class="hover:text-brand-500">{{ __('website.footer.help_links.contact_form') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col sm:flex-row gap-3 justify-between items-center text-xs text-slate-400">
            <div>&copy; 2025 Codefanz.com {{ __('website.footer.all_rights_reserved') }}</div>
            <a href="{{ url('sitemap.xml') }}" class="hover:text-brand-500">{{ __('website.footer.sitemap') }}</a>
        </div>
    </div>
</footer>
