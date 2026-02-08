@extends('layouts.central.gemini.layout')

@section('content')
    <header class="pt-32 pb-12 text-center bg-gradient-to-b from-brand-50 to-slate-50 dark:from-slate-900 dark:to-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-4">Build Your Custom Suite</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">Select the modules you need. Save 15% when you select 2 or more!</p>

            <div class="flex items-center justify-center gap-4 mb-12">
                <span class="text-slate-600 dark:text-slate-300 font-semibold">Monthly Billing</span>
                <input type="checkbox" id="billing-toggle" class="toggle-checkbox hidden" onchange="calculateTotal()"/>
                <label for="billing-toggle" class="relative inline-block w-14 h-8 bg-slate-300 rounded-full cursor-pointer transition-colors duration-300" id="toggle-bg">
                    <span class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300" id="toggle-dot"></span>
                </label>
                <span class="text-slate-400 dark:text-slate-500 font-semibold">Yearly (-20%)</span>
            </div>
        </div>
    </header>

    <section class="pb-32 bg-slate-50 dark:bg-slate-900">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                <div class="module-card bg-white dark:bg-slate-800 rounded-2xl p-8 border-2 border-transparent transition-all" id="card-pos">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <i class="fa-solid fa-cash-register text-3xl text-brand-500 mb-4"></i>
                            <h3 class="text-xl font-bold dark:text-white">POS & ERP</h3>
                        </div>
                        <input type="checkbox" class="module-checkbox w-6 h-6 accent-brand-500" id="check-pos" onchange="calculateTotal()">
                    </div>
                    <select id="tier-pos" class="w-full p-2 rounded-lg bg-slate-100 dark:bg-slate-700 dark:text-white mb-4" onchange="calculateTotal()">
                        <option value="starter">Starter ($29/mo)</option>
                        <option value="pro" selected>Professional ($79/mo)</option>
                        <option value="ent">Enterprise ($149/mo)</option>
                    </select>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Inventory Management</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i>ZATCA / Tax Ready</li>
                    </ul>
                </div>

                <div class="module-card bg-white dark:bg-slate-800 rounded-2xl p-8 border-2 border-transparent transition-all" id="card-hrm">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <i class="fa-solid fa-users text-3xl text-blue-500 mb-4"></i>
                            <h3 class="text-xl font-bold dark:text-white">HRM System</h3>
                        </div>
                        <input type="checkbox" class="module-checkbox w-6 h-6 accent-blue-500" id="check-hrm" onchange="calculateTotal()">
                    </div>
                    <select id="tier-hrm" class="w-full p-2 rounded-lg bg-slate-100 dark:bg-slate-700 dark:text-white mb-4" onchange="calculateTotal()">
                        <option value="starter">Starter ($19/mo)</option>
                        <option value="pro" selected>Professional ($59/mo)</option>
                        <option value="ent">Enterprise ($99/mo)</option>
                    </select>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li><i class="fa-solid fa-check text-blue-500 mr-2"></i>Payroll & Attendance</li>
                        <li><i class="fa-solid fa-check text-blue-500 mr-2"></i>Employee Portal</li>
                    </ul>
                </div>

                <div class="module-card bg-white dark:bg-slate-800 rounded-2xl p-8 border-2 border-transparent transition-all" id="card-booking">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <i class="fa-solid fa-calendar-check text-3xl text-purple-500 mb-4"></i>
                            <h3 class="text-xl font-bold dark:text-white">Booking System</h3>
                        </div>
                        <input type="checkbox" class="module-checkbox w-6 h-6 accent-purple-500" id="check-booking" onchange="calculateTotal()">
                    </div>
                    <select id="tier-booking" class="w-full p-2 rounded-lg bg-slate-100 dark:bg-slate-700 dark:text-white mb-4" onchange="calculateTotal()">
                        <option value="starter">Starter ($25/mo)</option>
                        <option value="pro" selected>Professional ($69/mo)</option>
                        <option value="ent">Enterprise ($119/mo)</option>
                    </select>
                    <ul class="text-sm text-slate-500 space-y-2">
                        <li><i class="fa-solid fa-check text-purple-500 mr-2"></i>Online Appointments</li>
                        <li><i class="fa-solid fa-check text-purple-500 mr-2"></i>Customer CRM</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 p-6 shadow-2xl z-50 transition-transform translate-y-full" id="summary-bar">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <span class="text-slate-500 dark:text-slate-400">Total Selected:</span>
                <span id="selected-count" class="font-bold dark:text-white ml-2">0 Modules</span>
                <span id="bundle-badge" class="hidden ml-4 bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-bold">Multi-Module Discount Applied!</span>
            </div>
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <p class="text-sm text-slate-400 line-through" id="old-price"></p>
                    <p class="text-3xl font-extrabold text-brand-500" id="total-price">$0.00<span class="text-sm text-slate-500 font-normal">/mo</span></p>
                </div>
                <button class="bg-brand-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-brand-600 transition shadow-lg">Proceed to Checkout</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const prices = {
            pos: { starter: 29, pro: 79, ent: 149 },
            hrm: { starter: 19, pro: 59, ent: 99 },
            booking: { starter: 25, pro: 69, ent: 119 }
        };

        function calculateTotal() {
            let total = 0;
            let count = 0;
            const isYearly = document.getElementById('billing-toggle').checked;
            const summaryBar = document.getElementById('summary-bar');

            // Toggle Visuals
            document.getElementById('toggle-dot').style.transform = isYearly ? 'translateX(24px)' : 'translateX(0)';
            document.getElementById('toggle-bg').style.backgroundColor = isYearly ? '#00d2b4' : '#cbd5e1';

            ['pos', 'hrm', 'booking'].forEach(mod => {
                const checkbox = document.getElementById('check-' + mod);
                const tier = document.getElementById('tier-' + mod).value;
                const card = document.getElementById('card-' + mod);

                if (checkbox.checked) {
                    let price = prices[mod][tier];
                    if (isYearly) price = price * 0.8; // 20% off
                    total += price;
                    count++;
                    card.classList.add('border-brand-500', 'ring-4', 'ring-brand-500/10');
                } else {
                    card.classList.remove('border-brand-500', 'ring-4', 'ring-brand-500/10');
                }
            });

            // Apply Bundle Discount (15% off if more than 1 module selected)
            const oldPriceElement = document.getElementById('old-price');
            const bundleBadge = document.getElementById('bundle-badge');

            if (count >= 2) {
                oldPriceElement.textContent = '$' + total.toFixed(2);
                total = total * 0.85;
                bundleBadge.classList.remove('hidden');
            } else {
                oldPriceElement.textContent = '';
                bundleBadge.classList.add('hidden');
            }

            // Update UI
            document.getElementById('total-price').innerHTML = `$${total.toFixed(2)}<span class="text-sm text-slate-500 font-normal">/${isYearly ? 'yr' : 'mo'}</span>`;
            document.getElementById('selected-count').textContent = count + ' Module' + (count !== 1 ? 's' : '');

            // Show/Hide Summary Bar
            if (count > 0) {
                summaryBar.classList.remove('translate-y-full');
            } else {
                summaryBar.classList.add('translate-y-full');
            }
        }
    </script>
@endpush
