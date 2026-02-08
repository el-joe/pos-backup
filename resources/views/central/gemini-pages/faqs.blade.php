@extends('layouts.central.gemini.layout')

@section('content')
    <header class="pt-32 pb-16 bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 text-center transition-colors duration-300">
        <div class="container mx-auto px-6" data-aos="fade-up">
            <p class="text-brand-500 font-bold uppercase tracking-wide text-sm mb-2">{{ __('gemini-landing.faqs_page.badge') }}</p>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-6">{{ __('gemini-landing.faqs_page.title') }}</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-8">
                {{ __('gemini-landing.faqs_page.subtitle') }}
            </p>
        </div>
    </header>

    <section class="py-16 bg-white dark:bg-slate-900 min-h-screen transition-colors duration-300">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-brand-dark dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">{{ __('gemini-landing.faqs_page.section_general') }}</h3>
                <div class="space-y-4">
                    @foreach ($faqs as $faq)
                        <div class="faq-item bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden cursor-pointer transition-colors duration-300" onclick="toggleFaq(this)">
                            <div class="p-5 flex justify-between items-center font-bold text-slate-800 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition">
                                <span>{!! $faq->question !!}</span>
                                <i class="fa-solid fa-chevron-down faq-icon transition-transform duration-300 text-slate-400"></i>
                            </div>
                            <div class="faq-content px-5 pb-5 text-slate-600 dark:text-slate-300 border-t border-slate-200 dark:border-slate-700 border-opacity-50">
                                <p class="pt-3">{!! $faq->answer !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-brand-50 dark:bg-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-3xl mx-auto" data-aos="zoom-in">
                <div class="w-16 h-16 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl text-brand-500 shadow-md">
                    <i class="fa-regular fa-comments"></i>
                </div>
                <h2 class="text-3xl font-bold text-brand-dark dark:text-white mb-4">{{ __('gemini-landing.faqs_page.cta_title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-8">{{ __('gemini-landing.faqs_page.cta_subtitle') }}</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('contact') }}" class="px-8 py-3 bg-brand-500 text-white font-bold rounded-lg hover:bg-brand-600 transition shadow-lg">{{ __('gemini-landing.faqs_page.cta_contact_support') }}</a>
                    <a href="{{ __('gemini-landing.faqs_page.whatsapp_url') }}" target="_blank" class="px-8 py-3 bg-white dark:bg-slate-700 text-slate-700 dark:text-white font-bold rounded-lg hover:text-brand-500 border border-slate-200 dark:border-slate-600 transition">{{ __('gemini-landing.faqs_page.cta_whatsapp') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
        <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav { backdrop-filter: blur(10px); }
        /* Accordion Styles */
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            opacity: 0;
        }
        .faq-item.active .faq-content {
            max-height: 500px;
            opacity: 1;
        }
        .faq-item.active .faq-icon {
            transform: rotate(180deg);
            color: #00d2b4;
        }
    </style>
    <script>
        function toggleFaq(element) {
            const allFaqs = document.querySelectorAll('.faq-item');
            allFaqs.forEach(item => {
                if (item !== element) {
                    item.classList.remove('active');
                }
            });
            element.classList.toggle('active');
        }
    </script>
@endpush
