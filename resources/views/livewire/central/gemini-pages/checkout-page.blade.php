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

            <form wire:submit.prevent="completeSubscription" class="space-y-8">

                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">1</div>
                        <h2 class="text-lg font-bold text-brand-dark dark:text-white">Company Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Company Name</label>
                            <input type="text" wire:model.live="data.company_name" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="e.g. Acme Trading Co.">
                            @error('data.company_name') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Company Email</label>
                            <input type="email" wire:model.live="data.company_email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="billing@company.com">
                            @error('data.company_email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Company Phone</label>
                            <input type="text" wire:model.live="data.company_phone" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:text-white transition" placeholder="+1 555 123 456">
                            @error('data.company_phone') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Company Size</label>
                            <select wire:model.live="data.company_size" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                <option value="">Select</option>
                                <option value="1-10">1 - 10 Employees</option>
                                <option value="11-50">11 - 50 Employees</option>
                                <option value="50+">50+ Employees</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Tax / VAT Number (Optional)</label>
                            <input type="text" wire:model.live="data.tax_number" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="300...">
                            @error('data.tax_number') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Country</label>
                            <select wire:model.live="data.country_id" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('data.country_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Currency</label>
                            <select wire:model.live="data.currency_id" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition text-slate-600 dark:text-slate-300">
                                <option value="">Select currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                @endforeach
                            </select>
                            @error('data.currency_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Address (Optional)</label>
                            <input type="text" wire:model.live="data.address" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="Street, city">
                            @error('data.address') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-2">Domain</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <input type="radio" class="w-4 h-4" value="subdomain" wire:model.live="data.domain_mode">
                                    Use a subdomain
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                                    <input type="radio" class="w-4 h-4" value="domain" wire:model.live="data.domain_mode">
                                    Use my custom domain
                                </label>
                            </div>
                            @error('data.domain_mode') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror

                            @if(($data['domain_mode'] ?? 'subdomain') === 'subdomain')
                                <div class="mt-4">
                                    <input type="text" wire:model.live.debounce.500ms="data.subdomain" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="your-company">
                                    <div class="text-xs text-slate-400 mt-2">Domain preview: <span class="font-semibold">{{ $data['final_domain'] ?? '--' }}</span></div>
                                </div>
                            @else
                                <div class="mt-4">
                                    <input type="text" wire:model.live="data.domain" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="company.com">
                                </div>
                            @endif
                            @error('data.final_domain') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
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
                            <input type="text" wire:model.live="data.admin_first_name" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="John">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Last Name</label>
                            <input type="text" wire:model.live="data.admin_last_name" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="Doe">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Work Email</label>
                            <input type="email" wire:model.live="data.admin_email" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="admin@company.com">
                            @error('data.admin_email') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Password</label>
                            <input type="password" wire:model.live="data.admin_password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                            @error('data.admin_password') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1">Confirm Password</label>
                            <input type="password" class="w-full p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:border-brand-500 dark:text-white transition" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                        <div class="w-10 h-10 rounded-full bg-brand-50 dark:bg-slate-700 text-brand-500 dark:text-brand-400 flex items-center justify-center font-bold border border-brand-100 dark:border-slate-600">3</div>
                        <h2 class="text-lg font-bold text-brand-dark dark:text-white">Payment Method</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-2">
                        <label class="cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex flex-col items-center gap-3 hover:border-brand-500 transition {{ ($data['payment_method'] ?? '') == 'credit_card' ? 'border-brand-500 bg-brand-50 dark:bg-slate-700' : 'bg-slate-50 dark:bg-slate-900' }}">
                            <input type="radio" wire:model.live="data.payment_method" value="credit_card" class="hidden">
                            <i class="fa-solid fa-credit-card text-2xl text-slate-400 dark:text-slate-300"></i>
                            <span class="text-sm font-bold text-slate-700 dark:text-white text-center">Credit Card</span>
                        </label>

                        <label class="cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex flex-col items-center gap-3 hover:border-brand-500 transition {{ ($data['payment_method'] ?? '') == 'paypal' ? 'border-brand-500 bg-brand-50 dark:bg-slate-700' : 'bg-slate-50 dark:bg-slate-900' }}">
                            <input type="radio" wire:model.live="data.payment_method" value="paypal" class="hidden">
                            <i class="fa-brands fa-paypal text-2xl text-slate-400 dark:text-slate-300"></i>
                            <span class="text-sm font-bold text-slate-700 dark:text-white text-center">PayPal</span>
                        </label>

                        <label class="cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex flex-col items-center gap-3 hover:border-brand-500 transition {{ ($data['payment_method'] ?? '') == 'bank_transfer' ? 'border-brand-500 bg-brand-50 dark:bg-slate-700' : 'bg-slate-50 dark:bg-slate-900' }}">
                            <input type="radio" wire:model.live="data.payment_method" value="bank_transfer" class="hidden">
                            <i class="fa-solid fa-building-columns text-2xl text-slate-400 dark:text-slate-300"></i>
                            <span class="text-sm font-bold text-slate-700 dark:text-white text-center">Bank Transfer</span>
                        </label>
                    </div>
                    @error('data.payment_method') <div class="text-xs text-red-500 mt-1 mb-4">{{ $message }}</div> @enderror

                    @if(($data['payment_method'] ?? '') === 'bank_transfer')
                        <div class="bg-blue-50 dark:bg-slate-900 border border-blue-100 dark:border-slate-700 rounded-xl p-6 mt-6">
                            <h4 class="text-sm font-bold text-blue-900 dark:text-blue-400 mb-2">Manual Bank Transfer</h4>
                            <p class="text-xs text-blue-800 dark:text-slate-400 mb-4">Please transfer the total amount to the account below, then upload your proof of payment. Your account will be activated once verified.</p>

                            <div class="bg-white dark:bg-slate-800 p-4 rounded-lg mb-4 text-sm font-mono text-slate-700 dark:text-slate-300 space-y-2 border border-blue-50 dark:border-slate-700">
                                <div class="flex justify-between border-b border-slate-100 dark:border-slate-700 pb-1">
                                    <span class="font-bold">Bank Name:</span> <span>Global Corporate Bank</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-100 dark:border-slate-700 pb-1 pt-1">
                                    <span class="font-bold">Account Name:</span> <span>Your Company LLC</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-100 dark:border-slate-700 pb-1 pt-1">
                                    <span class="font-bold">IBAN/Account:</span> <span>US12 3456 7890 1234 5678</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="font-bold">SWIFT/BIC:</span> <span>GCBXXX</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-2">Upload Transfer Receipt <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="data.receipt" class="block w-full text-sm text-slate-500 dark:text-slate-400
                                    file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-brand-50 file:text-brand-700
                                    hover:file:bg-brand-100 dark:file:bg-slate-700 dark:file:text-brand-400
                                    border border-slate-200 dark:border-slate-700 rounded-lg p-2 bg-white dark:bg-slate-800 cursor-pointer"
                                    accept=".pdf, .jpg, .jpeg, .png">
                                <div wire:loading wire:target="data.receipt" class="text-xs text-brand-500 mt-2 font-medium"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Uploading receipt...</div>
                                @error('data.receipt') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-start gap-3">
                    <input type="checkbox" id="terms" wire:model.live="data.terms_conditions_agree" class="mt-1 w-5 h-5 text-brand-500 border-slate-300 dark:border-slate-600 rounded focus:ring-brand-500 cursor-pointer bg-white dark:bg-slate-900">
                    <label for="terms" class="text-sm text-slate-500 dark:text-slate-400">
                        I agree to the <a href="terms.html" class="text-brand-500 hover:underline">Terms of Service</a> and <a href="privacy.html" class="text-brand-500 hover:underline">Privacy Policy</a>.
                    </label>
                </div>
                @error('data.terms_conditions_agree') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror

                <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-xl hover:bg-brand-600 transition shadow-lg shadow-brand-500/30 transform hover:-translate-y-0.5 disabled:opacity-60 disabled:cursor-not-allowed" wire:loading.attr="disabled" @if(empty($data['terms_conditions_agree'])) disabled @endif>
                    <span wire:loading.remove wire:target="completeSubscription">Create My Account & Checkout</span>
                    <span wire:loading wire:target="completeSubscription"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Processing...</span>
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
