@extends('layouts.central.gemini.layout')

@section('title',__('website.home.title'))

@section('content')
<header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-b from-brand-50 to-white dark:from-slate-900 dark:to-slate-800 transition-colors duration-300">
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-full bg-brand-100 dark:bg-brand-900/20 opacity-50 blur-[100px] rounded-bl-full"></div>

        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16">

                <div class="lg:w-1/2" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-slate-800 border border-brand-100 dark:border-slate-700 shadow-sm text-brand-600 dark:text-brand-400 text-sm font-bold mb-6">
                        <i class="fa-solid fa-check-circle"></i> VAT Compliant & Secure
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-brand-dark dark:text-white leading-tight mb-6">
                        Empower Your Business with <br>
                        <span class="text-gradient">Mohaaseb ERP</span>
                    </h1>
                    <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-lg leading-relaxed">
                        The all-in-one cloud ecosystem. Seamlessly integrate your <strong>Accounting</strong>, <strong>HR</strong>, <strong>POS</strong>, and <strong>Booking</strong> operations into one powerful dashboard.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="contact.html" class="px-8 py-4 bg-brand-500 text-white rounded-xl font-bold hover:bg-brand-600 transition shadow-xl shadow-brand-500/20 flex items-center justify-center gap-2">
                            Request Demo <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white dark:bg-slate-800 text-brand-dark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl font-bold hover:border-brand-500 hover:text-brand-500 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-play"></i> Watch Video
                        </a>
                    </div>
                </div>

                <div class="lg:w-1/2 relative" data-aos="fade-left" data-aos-duration="1200">
                    <div class="relative z-10 animate-float">
                        <div class="rounded-2xl shadow-2xl overflow-hidden border-4 border-white dark:border-slate-700 bg-slate-900">
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                                 alt="Mohaaseb ERP Financial Analytics Dashboard"
                                 class="w-full h-auto object-cover opacity-90 hover:opacity-100 transition duration-500">
                        </div>

                        <div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-brand-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-brand-600 dark:text-brand-400 text-xl">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Growth</p>
                                <p class="text-lg font-bold text-brand-dark dark:text-white">+128%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="about" class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-1/2" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Team analyzing data" class="rounded-3xl shadow-2xl opacity-90 dark:opacity-80">
                </div>
                <div class="md:w-1/2" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-3xl lg:text-4xl font-bold text-brand-dark dark:text-white mb-6">About Our ERP System</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-lg mb-6 leading-relaxed">
                        Mohaaseb isn't just software; it's the engine of your enterprise. We replace disconnected tools with a unified platform that speaks one language: <strong>Efficiency</strong>.
                    </p>
                    <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                        Our cloud-based solution is designed for the Middle Eastern market, ensuring full compliance with local tax regulations (VAT/ZATCA) while providing world-class performance for global scalability.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-brand-500 text-xl"></i>
                            <span class="font-semibold text-brand-dark dark:text-white">Real-time Data Sync</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-brand-500 text-xl"></i>
                            <span class="font-semibold text-brand-dark dark:text-white">Bank-Grade Security</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-brand-500 text-xl"></i>
                            <span class="font-semibold text-brand-dark dark:text-white">Multi-Branch Management</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-slate-50 dark:bg-slate-800 transition-colors duration-300">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-3xl lg:text-4xl font-bold text-brand-dark dark:text-white mb-4">Our Unique ERP Features</h2>
                <p class="text-slate-500 dark:text-slate-400">Four pillars of success integrated into one seamless application.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-brand-500 group" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-slate-800 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3">HR Management</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Automate payroll, attendance tracking, and employee performance reviews.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-brand-accent group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-yellow-50 dark:bg-slate-800 text-yellow-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3">Booking System</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Smart reservation management for services, resources, and appointments.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-brand-500 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-brand-100 dark:bg-slate-800 text-brand-600 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-brand-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3">Cloud POS</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Fast, offline-capable Point of Sale that syncs inventory in real-time.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border-b-4 border-slate-800 dark:border-slate-600 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:bg-slate-800 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mb-3">Full ERP</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Comprehensive financial reporting, supply chain, and inventory control.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-24 bg-brand-900 dark:bg-slate-950 text-white relative transition-colors duration-300">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-white">What Our Clients Say</h2>
                    <p class="text-brand-100 mt-2">Trusted by 5,000+ businesses across the region.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/10" data-aos="fade-up">
                    <div class="flex text-brand-accent mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="text-brand-50 italic mb-6">"Mohaaseb revolutionized how we handle our accounts. The POS system is incredibly fast and the reports are detailed."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center font-bold text-white">SJ</div>
                        <div>
                            <h4 class="font-bold text-white">Sarah Jenkins</h4>
                            <p class="text-xs text-brand-100">Retail Manager</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/10" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex text-brand-accent mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="text-brand-50 italic mb-6">"The HR module saved us hours of paperwork. Finally, an ERP that feels modern and easy to use."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center font-bold text-white">AH</div>
                        <div>
                            <h4 class="font-bold text-white">Ahmed Hassan</h4>
                            <p class="text-xs text-brand-100">Operations Director</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/10" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex text-brand-accent mb-4"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                    <p class="text-brand-50 italic mb-6">"Support is fantastic. They helped us migrate our data seamlessly. Highly recommended for growing companies."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center font-bold text-white">MR</div>
                        <div>
                            <h4 class="font-bold text-white">Michael Ross</h4>
                            <p class="text-xs text-brand-100">CEO, TechFlow</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="blog" class="py-24 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-brand-dark dark:text-white">Our Latest Insights</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-2">Expert advice on finance, management, and technology.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article class="group cursor-pointer" data-aos="fade-up">
                    <div class="overflow-hidden rounded-2xl mb-4 h-52">
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="Finance">
                    </div>
                    <span class="text-xs font-bold text-brand-500 uppercase">Accounting</span>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mt-2 mb-2 group-hover:text-brand-500 transition-colors">Mastering VAT Compliance in 2025</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2">A comprehensive guide to understanding new tax regulations and how Mohaaseb automates it.</p>
                    <a href="blog.html" class="inline-block mt-4 text-brand-600 dark:text-brand-400 font-bold hover:underline">Read More &rarr;</a>
                </article>

                <article class="group cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                    <div class="overflow-hidden rounded-2xl mb-4 h-52">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="Analytics">
                    </div>
                    <span class="text-xs font-bold text-brand-500 uppercase">Strategy</span>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mt-2 mb-2 group-hover:text-brand-500 transition-colors">5 KPIs Every Retailer Must Track</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2">Discover the key metrics that drive profitability in the retail sector using POS data.</p>
                    <a href="blog.html" class="inline-block mt-4 text-brand-600 dark:text-brand-400 font-bold hover:underline">Read More &rarr;</a>
                </article>

                <article class="group cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                    <div class="overflow-hidden rounded-2xl mb-4 h-52">
                        <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90 dark:opacity-80" alt="Tech">
                    </div>
                    <span class="text-xs font-bold text-brand-500 uppercase">Technology</span>
                    <h3 class="text-xl font-bold text-brand-dark dark:text-white mt-2 mb-2 group-hover:text-brand-500 transition-colors">Cloud vs. On-Premise ERP</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2">Why moving to the cloud is safer, cheaper, and more efficient for your business.</p>
                    <a href="blog.html" class="inline-block mt-4 text-brand-600 dark:text-brand-400 font-bold hover:underline">Read More &rarr;</a>
                </article>
            </div>
        </div>
    </section>

    <section id="contact" class="py-24 bg-gradient-to-br from-brand-dark to-slate-900 text-white relative overflow-hidden">
        <div class="absolute -right-20 top-0 w-96 h-96 bg-brand-500 opacity-20 blur-[100px] rounded-full"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row gap-16">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <h2 class="text-4xl font-bold mb-6">Get in Touch</h2>
                    <p class="text-slate-300 text-lg mb-8">Ready to transform your business? Schedule a free demo with our solution architects.</p>

                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-brand-400 text-xl"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase">Call Us</p>
                                <p class="text-lg font-bold">+966 55 123 4567</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center text-brand-400 text-xl"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <p class="text-xs text-slate-400 uppercase">Email Us</p>
                                <p class="text-lg font-bold">sales@mohaaseb.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2" data-aos="fade-left">
                    <form class="bg-white dark:bg-slate-800 p-8 rounded-3xl text-slate-700 dark:text-slate-300 shadow-2xl transition-colors duration-300">
                        <h3 class="text-2xl font-bold text-brand-dark dark:text-white mb-6">Book a Demo</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Name</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="Your Name">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Company</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="Company Name">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Email</label>
                            <input type="email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="you@example.com">
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Message</label>
                            <textarea rows="3" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:text-white" placeholder="Tell us about your needs..."></textarea>
                        </div>
                        <button type="button" class="w-full py-4 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition shadow-lg">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endsection
