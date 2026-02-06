@extends('layouts.central.gemini.layout')

@section('content')
    <header class="pt-32 pb-12 bg-white dark:bg-slate-900 text-center transition-colors duration-300">
        <div class="container mx-auto px-6">
            <p class="text-brand-500 font-bold uppercase tracking-wide text-sm mb-2">We're here to help</p>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-4">Let's Start a Conversation</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-xl mx-auto">Have questions about pricing, features, or need a custom demo? Our team is ready to answer all your questions.</p>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">

            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-brand-500 transition">
                        <div class="w-10 h-10 bg-brand-50 dark:bg-slate-700 rounded-lg flex items-center justify-center text-brand-500 mb-4 text-xl"><i class="fa-solid fa-envelope"></i></div>
                        <h3 class="font-bold text-slate-800 dark:text-white mb-1">Email Sales</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">For demos and pricing inquiries.</p>
                        <a href="mailto:sales@mohaaseb.com" class="text-brand-600 font-bold text-sm hover:underline">sales@mohaaseb.com</a>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:border-brand-500 transition">
                        <div class="w-10 h-10 bg-blue-50 dark:bg-slate-700 rounded-lg flex items-center justify-center text-blue-500 mb-4 text-xl"><i class="fa-solid fa-headset"></i></div>
                        <h3 class="font-bold text-slate-800 dark:text-white mb-1">Help & Support</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">For existing technical issues.</p>
                        <a href="mailto:support@mohaaseb.com" class="text-blue-600 font-bold text-sm hover:underline">support@mohaaseb.com</a>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 text-lg">Our Locations</h3>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <i class="fa-solid fa-location-dot text-brand-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-slate-700 dark:text-slate-200">Riyadh HQ</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Olaya Street, King Fahad District<br>Riyadh 12211, Saudi Arabia</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <i class="fa-solid fa-location-dot text-brand-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-slate-700 dark:text-slate-200">Cairo Office</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Business Park, New Cairo<br>Cairo, Egypt</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 h-48 bg-slate-200 dark:bg-slate-700 rounded-xl overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <button class="bg-white dark:bg-slate-800 px-4 py-2 rounded-full shadow-lg font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-700 dark:text-white transition">View on Google Maps</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-8 lg:p-10 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 transition-colors">
                <h2 class="text-2xl font-bold text-brand-dark dark:text-white mb-6">Send us a message</h2>
                <form class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">First Name</label>
                            <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Last Name</label>
                            <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Work Email</label>
                        <input type="email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Subject</label>
                        <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 transition text-slate-600 dark:text-slate-300">
                            <option>I want to buy Mohaaseb (Sales)</option>
                            <option>I need technical help (Support)</option>
                            <option>Partnership Inquiry</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Message</label>
                        <textarea rows="4" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition"></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold rounded-xl hover:bg-brand-600 transition shadow-lg shadow-brand-500/30 transform hover:-translate-y-0.5">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </main>
@endsection
