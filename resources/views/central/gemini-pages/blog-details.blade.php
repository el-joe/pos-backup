@extends('layouts.central.gemini.layout')

@section('content')
<header class="pt-32 pb-12 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6 max-w-4xl text-center" data-aos="fade-up">

            <div class="flex items-center justify-center gap-2 text-sm text-slate-400 mb-6 font-medium">
                <a href="blog.html" class="hover:text-brand-500">Blog</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="#" class="text-brand-500 font-bold">Accounting</a>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-brand-dark dark:text-white mb-6 leading-tight">
                The Ultimate Guide to ZATCA E-Invoicing Compliance in 2026
            </h1>

            <div class="flex items-center justify-center gap-4 mb-8">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Author" class="w-12 h-12 rounded-full border-2 border-white dark:border-slate-700 shadow-sm">
                <div class="text-left">
                    <p class="font-bold text-slate-800 dark:text-slate-200 text-sm">Written by Mark Wilson</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Senior Tax Consultant • 8 min read</p>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-2xl relative group">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Article Cover" class="w-full h-auto transform group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>
        </div>
    </header>

    <div class="bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6 py-12 max-w-6xl">
            <div class="flex flex-col lg:flex-row gap-12">

                <div class="lg:w-1/12 hidden lg:flex flex-col items-center gap-6 sticky top-32 h-fit">
                    <span class="text-xs font-bold text-slate-400 uppercase rotate-180" style="writing-mode: vertical-rl;">Share Article</span>
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-[#1877F2] hover:text-white transition"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-[#1DA1F2] hover:text-white transition"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-[#0A66C2] hover:text-white transition"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-green-500 hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                </div>

                <article class="lg:w-8/12 prose max-w-none font-serif text-lg text-slate-600 dark:text-slate-300">
                    <p class="lead text-xl md:text-2xl text-slate-800 dark:text-slate-100 font-sans font-medium mb-8">
                        Electronic invoicing (E-Invoicing) has transformed from a buzzword into a mandatory requirement for businesses across Saudi Arabia. With Phase 2 integration now in full swing, failing to comply isn't just a technical oversight—it's a financial risk.
                    </p>

                    <p>
                        In this guide, we will break down exactly what ZATCA Phase 2 entails, the technical requirements for integration, and how utilizing a cloud ERP like <strong>Mohaaseb</strong> can automate the entire process for you.
                    </p>

                    <h2 id="what-is-phase-2">What is ZATCA Phase 2 (Integration Phase)?</h2>
                    <p>
                        Unlike Phase 1, which simply required generating electronic invoices, Phase 2 requires your systems to <strong>integrate directly with ZATCA's Fatoora portal</strong>. This means every invoice you generate must be cleared (B2B) or reported (B2C) to the authority in real-time or near real-time.
                    </p>

                    <h3 id="key-differences">Key Differences from Phase 1</h3>
                    <ul>
                        <li><strong>Format:</strong> Invoices must be in XML format or PDF/A-3 with embedded XML.</li>
                        <li><strong>Connectivity:</strong> Continuous internet connection is required for clearance.</li>
                        <li><strong>Security:</strong> Cryptographic stamps and UUIDs are mandatory for anti-tampering.</li>
                    </ul>

                    <blockquote class="border-l-4 border-brand-500 pl-6 italic my-8 bg-slate-50 dark:bg-slate-800 py-4 pr-4 rounded-r-lg">
                        "Compliance is no longer about filing returns at the end of the year. It's about data quality at the moment of transaction."
                        <br><span class="text-sm font-bold text-brand-dark dark:text-brand-400 not-italic mt-2 block">- ZATCA Official Guidelines</span>
                    </blockquote>

                    <h2 id="how-to-prepare">How to Prepare Your Business</h2>
                    <p>
                        Preparation involves auditing your current tech stack. If you are still using manual Excel sheets or legacy desktop software that cannot connect to APIs, you will face significant challenges.
                    </p>
                    <p>
                        The checklist for readiness includes:
                    </p>
                    <ol class="list-decimal pl-6 mb-6">
                        <li>Registering devices on the Fatoora portal.</li>
                        <li>Ensuring your ERP generates UUIDs and Cryptographic Stamps.</li>
                        <li>Training staff on the new fields required (e.g., Buyer VAT ID, National Address).</li>
                    </ol>

                    <h2 id="solution">The Automated Solution</h2>
                    <p>
                        Instead of building a custom integration which is costly and requires maintenance, switching to a compliant Cloud ERP is the smartest move.
                        <strong>Mohaaseb ERP</strong> comes pre-integrated with ZATCA.
                    </p>

                    <div class="bg-brand-50 dark:bg-slate-800 border border-brand-100 dark:border-slate-700 rounded-xl p-6 my-8 font-sans">
                        <h4 class="font-bold text-brand-dark dark:text-white mb-2 text-lg"><i class="fa-solid fa-lightbulb text-brand-500 mr-2"></i> Why Choose Mohaaseb?</h4>
                        <p class="text-base mb-0">
                            We handle the XML generation, the API handshake, and the QR code embedding automatically. You just hit "Save Invoice", and we handle the compliance in the background.
                        </p>
                    </div>

                    <h2 id="conclusion">Conclusion</h2>
                    <p>
                        The digital transformation of tax is inevitable. By embracing these changes early and adopting the right tools, you turn a regulatory burden into an operational advantage. Ready to automate your invoicing?
                    </p>
                </article>

                <aside class="lg:w-3/12 space-y-8">

                    <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 sticky top-32">
                        <h4 class="font-bold text-brand-dark dark:text-white mb-4 uppercase text-xs tracking-wider">Table of Contents</h4>
                        <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
                            <li><a href="#what-is-phase-2" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">What is Phase 2?</a></li>
                            <li><a href="#key-differences" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">Key Differences</a></li>
                            <li><a href="#how-to-prepare" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">How to Prepare</a></li>
                            <li><a href="#solution" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">The Automated Solution</a></li>
                            <li><a href="#conclusion" class="hover:text-brand-500 transition block border-l-2 border-transparent hover:border-brand-500 pl-3">Conclusion</a></li>
                        </ul>
                    </div>

                    <div class="bg-brand-900 dark:bg-slate-800 text-white p-8 rounded-2xl text-center border dark:border-slate-700">
                        <div class="w-12 h-12 bg-brand-500 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                            <i class="fa-regular fa-paper-plane"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-2">Weekly Insights</h4>
                        <p class="text-brand-100 dark:text-slate-400 text-sm mb-4">Join 5,000+ subscribers growing their business.</p>
                        <input type="email" placeholder="Email address" class="w-full px-4 py-3 rounded-lg text-slate-800 mb-3 text-sm focus:outline-none border-none">
                        <button class="w-full bg-brand-500 py-3 rounded-lg font-bold text-sm hover:bg-brand-400 transition">Subscribe</button>
                    </div>

                </aside>
            </div>
        </div>
    </div>

    <section class="py-16 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6 max-w-6xl">
            <h3 class="text-2xl font-bold text-brand-dark dark:text-white mb-8">Read Next</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="#" class="group block">
                    <div class="overflow-hidden rounded-xl mb-4 h-48">
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 dark:opacity-80">
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-brand-500 transition">Understanding VAT Returns</h4>
                </a>
                <a href="#" class="group block">
                    <div class="overflow-hidden rounded-xl mb-4 h-48">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 dark:opacity-80">
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-brand-500 transition">5 KPIs for Retail Growth</h4>
                </a>
                <a href="#" class="group block">
                    <div class="overflow-hidden rounded-xl mb-4 h-48">
                        <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=500&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 dark:opacity-80">
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-brand-500 transition">Cloud Security Basics</h4>
                </a>
            </div>
        </div>
    </section>
@endsection
