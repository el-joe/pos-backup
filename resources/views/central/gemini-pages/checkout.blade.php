@extends('layouts.central.gemini.layout')

@section('content')
    <main class="flex-grow container mx-auto px-6 py-28" itemscope itemtype="https://schema.org/WebPage">
        <div class="flex flex-col lg:flex-row gap-12 max-w-6xl mx-auto">

            <div class="lg:w-2/3">
                <h1 class="text-2xl font-bold text-brand-dark dark:text-white mb-2">Complete your registration</h1>
                <p class="text-slate-500 dark:text-slate-400 mb-8">
                    @if($hasAnyFreeTrial ?? false)
                        Selected plans with 3 months free trial will be free now, and only non-trial plans are charged today.
                    @else
                        Choose your plan and complete registration.
                    @endif
                </p>

                <form action="#" method="POST" class="space-y-8">

                    <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                        <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">1</div>
                            <h2 class="text-lg font-bold text-brand-dark dark:text-white">Company Details</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Company Name</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="e.g. Acme Trading Co.">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Industry</label>
                                <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                    <option>Retail & POS</option>
                                    <option>Services & Consulting</option>
                                    <option>Manufacturing</option>
                                    <option>Real Estate</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Company Size</label>
                                <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                    <option>1 - 10 Employees</option>
                                    <option>11 - 50 Employees</option>
                                    <option>50+ Employees</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Tax / VAT Number (Optional)</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="300...">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Country</label>
                                <select class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                    <option>Saudi Arabia</option>
                                    <option>Egypt</option>
                                    <option>United Arab Emirates</option>
                                    <option>Qatar</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                        <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">2</div>
                            <h2 class="text-lg font-bold text-brand-dark dark:text-white">Admin Login Details</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">First Name</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="John">
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Last Name</label>
                                <input type="text" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="Doe">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Work Email</label>
                                <input type="email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="admin@company.com">
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Password</label>
                                <input type="password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Confirm Password</label>
                                <input type="password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="mt-6 flex items-start gap-3">
                            <input type="checkbox" id="terms" class="mt-1 w-5 h-5 text-brand-500 border-slate-300 dark:border-slate-600 rounded focus:ring-brand-500 cursor-pointer bg-white dark:bg-slate-900">
                            <label for="terms" class="text-sm text-slate-500 dark:text-slate-400">
                                I agree to the <a href="terms.html" class="text-brand-500 hover:underline">Terms of Service</a> and <a href="privacy.html" class="text-brand-500 hover:underline">Privacy Policy</a>.
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-xl hover:bg-brand-600 transition shadow-lg shadow-brand-500/30 transform hover:-translate-y-0.5">
                        Create My Account
                    </button>

                    <p class="text-center text-sm text-slate-400 dark:text-slate-500 mt-4"><i class="fa-solid fa-lock mr-1"></i> Your data is encrypted and secure.</p>

                </form>
            </div>

            <div class="lg:w-1/3">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 sticky top-8 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-brand-dark dark:text-white mb-4 border-b border-slate-100 dark:border-slate-700 pb-4">Order Summary</h3>

                    <div class="bg-brand-50 dark:bg-slate-700 border border-brand-100 dark:border-slate-600 rounded-xl p-4 mb-6">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wide">Selected Systems</span>
                            <a href="{{ route('pricing') }}" class="text-xs font-bold text-slate-400 hover:text-brand-500 underline">Change</a>
                        </div>
                        <div class="space-y-2">
                            @foreach(($selectedSystemsSummary ?? []) as $systemSummary)
                                <div class="flex items-center justify-between text-sm">
                                    <div>
                                        <div class="font-semibold text-brand-900 dark:text-white">{{ $systemSummary['module_title'] ?? 'System' }}</div>
                                        <div class="text-slate-500 dark:text-slate-300">{{ $systemSummary['plan_name'] ?? 'Plan' }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if(((int) ($systemSummary['free_trial_months'] ?? 0)) > 0)
                                            <div class="font-bold text-green-600 dark:text-green-400">Free now</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-300">3 months trial</div>
                                        @else
                                            <div class="font-bold text-brand-700 dark:text-brand-300">${{ number_format((float) ($systemSummary['price'] ?? 0), 2) }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-300">/{{ ($period ?? 'month') === 'year' ? 'yr' : 'mo' }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">Includes:</h4>
                        <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @forelse(($selectedFeatureNames ?? []) as $featureName)
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500 text-xs"></i> {{ $featureName }}</li>
                            @empty
                                <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500 text-xs"></i> Core modules enabled</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4 flex justify-between items-center mb-2">
                        <span class="font-bold text-slate-700 dark:text-white">Total due today</span>
                        <span class="text-2xl font-extrabold text-brand-dark dark:text-white">
                            @if(((float) ($selectedDueNow ?? 0)) <= 0)
                                Free
                            @else
                                ${{ number_format((float) ($selectedDueNow ?? 0), 2) }}
                            @endif
                        </span>
                    </div>
                    @if($hasAnyFreeTrial ?? false)
                        <p class="text-xs text-right text-slate-400 mb-6">
                            Plans with 3 months free trial are free today. Non-trial plans are billed now.
                        </p>
                    @else
                        <p class="text-xs text-right text-slate-400 mb-6">
                            Due today: ${{ number_format((float) ($selectedDueNow ?? 0), 2) }}, billed {{ ($period ?? 'month') === 'year' ? 'yearly' : 'monthly' }}.
                        </p>
                    @endif

                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg flex justify-center gap-4 text-2xl text-slate-400 grayscale opacity-70">
                        <i class="fa-brands fa-cc-visa"></i>
                        <i class="fa-brands fa-cc-mastercard"></i>
                        <i class="fa-brands fa-cc-amex"></i>
                    </div>
                </div>
            </div>

        </div>
    </main>

@endsection
