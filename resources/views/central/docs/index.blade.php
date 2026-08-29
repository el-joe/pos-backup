@extends('layouts.central.gemini.layout')

@section('content')
    <main>
        <header class="pt-32 pb-16 bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 text-center transition-colors duration-300">
            <div class="container mx-auto px-6" data-aos="fade-up">
                <p class="text-brand-500 font-bold uppercase tracking-wide text-sm mb-2">{{ __('Documentation') }}</p>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-6">{{ __('Mohaaseb Documentation') }}</h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-8">
                    {{ __('Everything you need to get the most out of your POS, Accounting and ERP system.') }}
                </p>
            </div>
        </header>

        <section class="py-16 bg-white dark:bg-slate-900 min-h-screen transition-colors duration-300">
            <div class="container mx-auto px-6">
                @php
                    $sectionMeta = [
                        'getting-started' => ['label' => __('Getting Started'), 'icon' => 'fa-rocket', 'desc' => __('New to Mohaaseb? Start here.')],
                        'pos' => ['label' => __('Point of Sale (POS)'), 'icon' => 'fa-cash-register', 'desc' => __('Selling, checkout and the POS terminal.')],
                        'sales' => ['label' => __('Sales & Purchases'), 'icon' => 'fa-file-invoice-dollar', 'desc' => __('Manage sales orders and invoices.')],
                        'purchases' => ['label' => __('Sales & Purchases'), 'icon' => 'fa-truck', 'desc' => __('Manage purchases and suppliers.')],
                        'inventory' => ['label' => __('Inventory'), 'icon' => 'fa-boxes-stacked', 'desc' => __('Products, stock and warehouses.')],
                        'accounting' => ['label' => __('Accounting'), 'icon' => 'fa-calculator', 'desc' => __('Chart of accounts, journals and reports.')],
                        'hrm' => ['label' => __('HRM'), 'icon' => 'fa-users', 'desc' => __('Employees, payroll and attendance.')],
                        'contracting' => ['label' => __('Contracting'), 'icon' => 'fa-helmet-safety', 'desc' => __('Projects, contracts and progress billing.')],
                        'settings' => ['label' => __('Settings'), 'icon' => 'fa-gear', 'desc' => __('Configure your system to fit your business.')],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($pagesBySection as $section => $pages)
                        @php
                            $meta = $sectionMeta[$section] ?? ['label' => \Illuminate\Support\Str::title(str_replace('-', ' ', (string) $section)), 'icon' => 'fa-book', 'desc' => ''];
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6 transition-colors duration-300">
                            <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 text-xl text-brand-500 shadow-sm">
                                <i class="fa-solid {{ $meta['icon'] }}"></i>
                            </div>
                            <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-1">{{ $meta['label'] }}</h3>
                            @if ($meta['desc'])
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $meta['desc'] }}</p>
                            @endif
                            <ul class="space-y-2">
                                @foreach ($pages as $page)
                                    <li>
                                        <a href="{{ route('docs.show', ['slug' => $page->slug]) }}"
                                           class="text-slate-700 dark:text-slate-300 hover:text-brand-500 dark:hover:text-brand-400 transition flex items-center gap-2">
                                            <i class="fa-solid fa-chevron-right text-xs text-brand-500"></i>
                                            {{ $page->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-slate-500 dark:text-slate-400 py-12">
                            {{ __('Documentation is coming soon.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
@endsection
