@extends('layouts.central.gemini.layout')

@section('content')
<main itemscope itemtype="https://schema.org/WebPage">
    <header class="pt-32 pb-12 bg-slate-50 dark:bg-slate-900 text-center transition-colors duration-300">
        <div class="container mx-auto px-6 max-w-3xl">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-4" itemprop="name">
                {{ $page->title }}
            </h1>
            @if($page->short_description)
                <p class="text-lg text-slate-500 dark:text-slate-400 max-w-xl mx-auto" itemprop="description">
                    {{ $page->short_description }}
                </p>
            @endif
        </div>
    </header>

    <div class="bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6 py-12 max-w-4xl">
            <article class="static-page-content bg-white dark:bg-slate-800 p-6 md:p-10 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-slate-600 dark:text-slate-300 leading-relaxed" itemprop="mainContentOfPage">
                {!! $page->content !!}
            </article>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
    .static-page-content h1, .static-page-content h2, .static-page-content h3 {
        font-weight: 800;
        color: rgb(15 23 42 / 1);
        margin-top: 1.75rem;
        margin-bottom: .75rem;
    }
    html.dark .static-page-content h1,
    html.dark .static-page-content h2,
    html.dark .static-page-content h3 {
        color: #fff;
    }
    .static-page-content h1:first-child,
    .static-page-content h2:first-child,
    .static-page-content h3:first-child {
        margin-top: 0;
    }
    .static-page-content h2 { font-size: 1.5rem; }
    .static-page-content h3 { font-size: 1.25rem; }
    .static-page-content p { margin-bottom: 1rem; }
    .static-page-content ul, .static-page-content ol {
        margin: 0 0 1rem 1.25rem;
        list-style: disc;
    }
    .static-page-content ol { list-style: decimal; }
    .static-page-content li { margin-bottom: .5rem; }
    .static-page-content a {
        color: #0d9488;
        text-decoration: underline;
    }
    .static-page-content strong { font-weight: 700; }
</style>
@endpush
