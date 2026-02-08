@extends('layouts.central.gemini.layout')

@section('content')
    <header class="pt-32 pb-12 text-center bg-gradient-to-b from-brand-50 to-slate-50 dark:from-slate-900 dark:to-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-brand-dark dark:text-white mb-4">Compare Our Solutions</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 mb-8 max-w-2xl mx-auto">First, select the system you need. Then, choose the plan that fits your scale.</p>

            <div class="flex flex-wrap justify-center gap-2 mb-10 p-1 bg-slate-200 dark:bg-slate-800 rounded-2xl max-w-fit mx-auto">
                <button onclick="switchSystem('pos')" id="btn-pos" class="system-tab active-tab px-8 py-3 rounded-xl font-bold transition-all duration-300">
                    <i class="fa-solid fa-cash-register mr-2"></i> POS & ERP
                </button>
                <button onclick="switchSystem('hrm')" id="btn-hrm" class="system-tab px-8 py-3 rounded-xl font-bold text-slate-500 transition-all duration-300">
                    <i class="fa-solid fa-users-gears mr-2"></i> HRM System
                </button>
                <button onclick="switchSystem('booking')" id="btn-booking" class="system-tab px-8 py-3 rounded-xl font-bold text-slate-500 transition-all duration-300">
                    <i class="fa-solid fa-calendar-check mr-2"></i> Booking System
                </button>
            </div>

            <div class="flex items-center justify-center gap-4 mb-12">
                <span class="text-slate-600 dark:text-slate-300 font-semibold">Monthly</span>
                <div class="relative inline-block w-14 h-8 align-middle select-none">
                    <input type="checkbox" id="billing-toggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer top-1 left-1 transition-all duration-300" onchange="updateUI()"/>
                    <label for="billing-toggle" class="toggle-label block overflow-hidden h-8 rounded-full bg-slate-300 dark:bg-slate-700 cursor-pointer transition-colors duration-300"></label>
                </div>
                <span class="text-slate-400 dark:text-slate-500 font-semibold">Yearly <span class="text-brand-500 text-xs font-bold bg-brand-100 px-2 py-0.5 rounded-full ml-1">-20%</span></span>
            </div>
        </div>
    </header>

    <section class="pb-24 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16" id="pricing-grid">
                </div>

            <div class="max-w-5xl mx-auto bg-white dark:bg-slate-800 rounded-3xl shadow-sm overflow-hidden border border-slate-100 dark:border-slate-700">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white" id="table-title">POS Features Comparison</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead id="feature-table-head"></thead>
                        <tbody id="feature-table-body" class="text-slate-600 dark:text-slate-300">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <style>
        .active-tab { background-color: white; color: #00d2b4; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .dark .active-tab { background-color: #1e293b; color: #00d2b4; }
        .toggle-checkbox:checked { transform: translateX(24px); border-color: #00d2b4; }
        .toggle-checkbox:checked + .toggle-label { background-color: #00d2b4; }
    </style>

    <script>
        const systemData = @json($systemData ?? []);

        let activeSystem = 'pos';

        function switchSystem(sys) {
            activeSystem = sys;
            // Update Tab UI
            document.querySelectorAll('.system-tab').forEach(btn => {
                btn.classList.remove('active-tab');
                btn.classList.add('text-slate-500');
            });
            document.getElementById('btn-' + sys).classList.add('active-tab');
            document.getElementById('btn-' + sys).classList.remove('text-slate-500');

            updateUI();
        }

        function updateUI() {
            const data = systemData[activeSystem];
            const isYearly = document.getElementById('billing-toggle').checked;

            if (!data) return;

            // 1. Update Plan Cards
            const grid = document.getElementById('pricing-grid');
            grid.innerHTML = data.plans.map(plan => `
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 border-2 ${plan.popular ? 'border-brand-500 scale-105 shadow-xl' : 'border-transparent shadow-sm'} relative transition-all duration-300">
                    ${plan.popular ? '<div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-brand-500 text-white px-4 py-1 rounded-full text-xs font-bold uppercase">Best Value</div>' : ''}
                    <h3 class="text-xl font-bold dark:text-white mb-2">${plan.name}</h3>
                    ${plan.desc ? `<p class="text-sm text-slate-500 mb-6">${plan.desc}</p>` : '<div class="mb-6"></div>'}
                    <div class="flex items-baseline mb-6">
                        <span class="text-4xl font-extrabold dark:text-white">$${isYearly ? plan.yearly : plan.price}</span>
                        <span class="text-slate-500 ml-1">/mo</span>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-600 dark:text-slate-400 mb-8">
                        ${plan.features.map(f => `<li><i class="fa-solid fa-check text-brand-500 mr-2"></i> ${f}</li>`).join('')}
                    </ul>
                    <button class="w-full py-3 rounded-xl font-bold transition ${plan.popular ? 'bg-brand-500 text-white hover:bg-brand-600' : 'bg-slate-100 dark:bg-slate-700 dark:text-white hover:bg-slate-200'}">Choose ${plan.name}</button>
                </div>
            `).join('');

            // 2. Update Table
            document.getElementById('table-title').textContent = data.title + ' Comparison';

            const thead = document.getElementById('feature-table-head');
            const recommendedIndex = Math.max(0, data.plans.findIndex(p => p.popular));
            thead.innerHTML = `
                <tr class="text-slate-400 dark:text-slate-500 text-xs uppercase tracking-wider">
                    <th class="py-4 px-8 font-medium">Core Features</th>
                    ${data.plans.map((plan, idx) => `
                        <th class="py-4 px-8 text-center ${idx === recommendedIndex ? 'text-brand-500' : ''}">${plan.name}</th>
                    `).join('')}
                </tr>
            `;

            const tableBody = document.getElementById('feature-table-body');
            tableBody.innerHTML = data.table.map(row => `
                <tr class="border-t border-slate-50 dark:border-slate-700/50">
                    <td class="py-4 px-8 font-semibold">${row[0]}</td>
                    ${row.slice(1).map((cell, idx) => {
                        const columnIdx = idx;
                        const isRecommended = columnIdx === recommendedIndex;
                        const checkIcon = isRecommended
                            ? '<i class="fa-solid fa-circle-check text-brand-500"></i>'
                            : '<i class="fa-solid fa-circle-check text-green-500"></i>';
                        const crossIcon = '<i class="fa-solid fa-circle-xmark text-slate-300"></i>';

                        const rendered = cell === '✓' ? checkIcon : (cell === '×' ? crossIcon : cell);
                        const classes = isRecommended ? 'font-bold text-brand-500' : '';
                        return `<td class="py-4 px-8 text-center text-sm ${classes}">${rendered}</td>`;
                    }).join('')}
                </tr>
            `).join('');
        }

        // Initialize
        switchSystem('pos');
    </script>
@endpush
