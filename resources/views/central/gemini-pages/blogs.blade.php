@extends('layouts.central.gemini.layout')

@section('content')

<header class="pt-32 pb-16 bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 text-center transition-colors duration-300">
        <div class="container mx-auto px-6" data-aos="fade-up">
            <p class="text-brand-500 font-bold uppercase tracking-wide text-sm mb-2">Knowledge Hub</p>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-6">Insights & Resources</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-8">
                Expert advice on scaling your business, managing finances, and optimizing HR operations with the latest cloud technology.
            </p>

            <div class="max-w-xl mx-auto relative">
                <input type="text" placeholder="Search articles..." class="w-full py-4 pl-6 pr-12 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white shadow-sm focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 dark:focus:ring-brand-900 transition">
                <button class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-500">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
            </div>
        </div>
    </header>

    <section class="py-12 bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="bg-brand-900 dark:bg-slate-700 rounded-3xl overflow-hidden shadow-2xl flex flex-col lg:flex-row" data-aos="zoom-in">
                <div class="lg:w-1/2 relative min-h-[300px]">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Featured" class="absolute inset-0 w-full h-full object-cover opacity-80 hover:opacity-100 transition duration-700">
                </div>
                <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center text-white">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-brand-500 text-xs font-bold px-3 py-1 rounded-full text-white">Featured</span>
                        <span class="text-slate-300 text-sm"><i class="fa-regular fa-clock mr-1"></i> 5 min read</span>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 leading-tight text-white">The Ultimate Guide to ZATCA E-Invoicing Compliance in 2026</h2>
                    <p class="text-brand-100 dark:text-slate-300 mb-6 leading-relaxed">
                        Avoid penalties and streamline your tax reporting. Learn exactly what your business needs to do to stay compliant with the latest regulations in Saudi Arabia.
                    </p>
                    <a href="blog-post.html" class="inline-flex items-center font-bold text-brand-400 hover:text-white transition">
                        Read Full Article <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">

            <div class="flex flex-wrap justify-center gap-4 mb-12" data-aos="fade-up">
                <button class="px-6 py-2 rounded-full bg-brand-500 text-white font-semibold shadow-lg shadow-brand-500/30">All</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">Accounting</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">HR & Payroll</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">Retail / POS</button>
                <button class="px-6 py-2 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-medium hover:border-brand-500 hover:text-brand-500 dark:hover:text-brand-400 transition">Success Stories</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <article class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group border border-transparent dark:border-slate-700" data-aos="fade-up" data-aos-delay="0">
                    <div class="h-48 overflow-hidden relative">
                        <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-brand-600 uppercase">Accounting</div>
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="Blog Image">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-400 mb-3 gap-4">
                            <span>Jan 22, 2026</span>
                            <span>• By Admin</span>
                        </div>
                        <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3 group-hover:text-brand-500 transition-colors">5 Signs You've Outgrown Excel</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-3">Using spreadsheets for inventory? Here is why that's costing you money and how a Cloud ERP fixes it.</p>
                        <a href="#" class="text-brand-600 dark:text-brand-400 font-bold text-sm hover:underline">Read More &rarr;</a>
                    </div>
                </article>

                <article class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group border border-transparent dark:border-slate-700" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-48 overflow-hidden relative">
                        <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-blue-600 uppercase">HR Tech</div>
                        <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="Blog Image">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-400 mb-3 gap-4">
                            <span>Jan 20, 2026</span>
                            <span>• By Sarah J.</span>
                        </div>
                        <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3 group-hover:text-brand-500 transition-colors">Automating Payroll: A Guide</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-3">Reduce errors and save hours every month by switching to an automated payroll system linked to attendance.</p>
                        <a href="#" class="text-brand-600 dark:text-brand-400 font-bold text-sm hover:underline">Read More &rarr;</a>
                    </div>
                </article>

                <article class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group border border-transparent dark:border-slate-700" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-48 overflow-hidden relative">
                        <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur px-3 py-1 rounded text-xs font-bold text-yellow-600 uppercase">Retail</div>
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="Blog Image">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-400 mb-3 gap-4">
                            <span>Jan 15, 2026</span>
                            <span>• By Tech Team</span>
                        </div>
                        <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3 group-hover:text-brand-500 transition-colors">POS vs ERP: What Do You Need?</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-3">Understanding the difference between a simple Point of Sale and a fully integrated ERP system.</p>
                        <a href="#" class="text-brand-600 dark:text-brand-400 font-bold text-sm hover:underline">Read More &rarr;</a>
                    </div>
                </article>

                </div>

            <div class="flex justify-center mt-16 gap-2">
                <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-brand-500 hover:text-brand-500"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-brand-500 text-white font-bold">1</button>
                <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-brand-500 hover:text-brand-500">2</button>
                <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-brand-500 hover:text-brand-500">3</button>
                <button class="w-10 h-10 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-brand-500 hover:text-brand-500"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white dark:bg-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-r from-brand-900 to-brand-700 rounded-3xl p-8 md:p-16 text-center text-white relative overflow-hidden" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

                <h2 class="text-3xl lg:text-4xl font-bold mb-4 relative z-10">Stay Ahead of the Curve</h2>
                <p class="text-brand-100 mb-8 max-w-xl mx-auto relative z-10">Join 10,000+ business owners receiving weekly tips on ERP, Tax Compliance, and Business Growth.</p>

                <form class="max-w-md mx-auto relative z-10 flex flex-col sm:flex-row gap-4">
                    <input type="email" placeholder="Enter your email address" class="flex-1 py-4 px-6 rounded-full text-slate-800 bg-white focus:outline-none focus:ring-4 focus:ring-brand-500/50">
                    <button type="button" class="bg-brand-500 text-white font-bold py-4 px-8 rounded-full hover:bg-brand-400 transition shadow-lg">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

@endsection
