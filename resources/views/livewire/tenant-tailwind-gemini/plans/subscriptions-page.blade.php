<div class="space-y-6">
    @php
    $totalDays = $currentSubscription ? carbon($currentSubscription->start_date)->diffInDays(carbon($currentSubscription->end_date)) : 0;
    $usedDays = $currentSubscription ? ceil(carbon($currentSubscription->start_date)->diffInDays(now())) : 0;
    $remainingDays = $currentSubscription ? max($totalDays - $usedDays, 0) : 0;
    $progress = $currentSubscription && $totalDays > 0 ? min(($usedDays / $totalDays) * 100, 100) : 0;
    @endphp

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(18rem,0.8fr)]">
        @if($currentSubscription)
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.subscriptions.active_subscription')" icon="fa fa-crown">
            <div class="space-y-6 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-brand-600 px-5 py-4 text-white">
                    <div class="flex items-center gap-3">
                        <i class="fa fa-crown"></i>
                        <span class="text-sm font-semibold uppercase tracking-[0.2em]">{{ __('general.pages.subscriptions.active_subscription') }}</span>
                    </div>
                    <span class="flex items-center gap-2">
                        @if($currentSubscription->is_trial)
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">{{ __('general.pages.subscriptions.free_trial') }}</span>
                        @endif
                        <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">{{ ucfirst($currentSubscription->status) }}</span>
                    </span>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.plan') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $currentSubscription->plan?->name }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.price') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $currentSubscription->is_trial ? __('general.pages.subscriptions.free_trial') : currencyFormat($currentSubscription->price, true) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.start_date') }}</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($currentSubscription->start_date, true, false) }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center dark:border-slate-700 dark:bg-slate-800/60">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.end_date') }}</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ dateTimeFormat($currentSubscription->end_date, true, false) }}</p>
                    </div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                        <span class="text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.subscription_progress') }}</span>
                        <span class="font-semibold text-brand-700 dark:text-brand-300">{{ __('general.pages.subscriptions.days_remaining', ['days' => $remainingDays]) }}</span>
                    </div>
                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                @if(adminCan('subscriptions.renew'))
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="openChangePlanBtn" class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                        <i class="fa fa-right-left"></i> {{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}
                    </button>
                </div>
                @endif
            </div>
        </x-tenant-tailwind-gemini.table-card>
        @endif

        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.subscriptions.account_balance')" icon="fa fa-wallet">
            <div class="flex h-full min-h-[18rem] flex-col items-center justify-center gap-3 p-5 text-center">
                <p class="text-sm font-medium uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.available_balance') }}</p>
                <p class="text-4xl font-semibold text-brand-700 dark:text-brand-300">{{ currencyFormat($accountBalance, true) }}</p>
                @if(adminCan('subscriptions.renew'))
                <button type="button" id="openTopUpBtn" class="inline-flex items-center gap-2 rounded-xl border border-brand-200 px-4 py-2 text-sm font-semibold text-brand-700 hover:bg-brand-50 dark:border-slate-700 dark:text-brand-300 dark:hover:bg-slate-800">
                    <i class="fa fa-plus"></i> Add Balance
                </button>
                @endif
            </div>
        </x-tenant-tailwind-gemini.table-card>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.subscriptions.previous_subscriptions')" icon="fa fa-history">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('general.pages.subscriptions.plan') }}</th>
                            <th>{{ __('general.pages.subscriptions.start_date') }}</th>
                            <th>{{ __('general.pages.subscriptions.end_date') }}</th>
                            <th>{{ __('general.pages.subscriptions.status') }}</th>
                            <th class="text-end">{{ __('general.pages.subscriptions.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subscription->plan?->name }}</td>
                            <td>{{ dateTimeFormat($subscription->start_date, true, false) }}</td>
                            <td>{{ dateTimeFormat($subscription->end_date, true, false) }}</td>
                            <td>
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ ucfirst($subscription->status) }}</span>
                            </td>
                            <td class="text-end fw-semibold">{{ currencyFormat($subscription->price, true) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.subscriptions.no_previous_subscriptions_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    @if(adminCan('subscriptions.renew'))
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" id="changePlanModal">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    <i class="fa fa-right-left mr-2"></i>{{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}
                </h3>
                <button type="button" id="closeChangePlanBtn" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa fa-times text-lg"></i>
                </button>
            </div>

            <div class="space-y-5 px-6 py-5">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('general.pages.subscriptions.plan') }}</label>
                    <select id="selectedPlanId" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:!bg-slate-800 dark:text-white">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ $selectedPlanId == $plan->id ? 'selected' : '' }}>{{ $plan->name }} — {{ currencyFormat($plan->price, true) }} / {{ $plan->isYearly() ? 'year' : 'month' }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="plan_id"></p>
                </div>

                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800 {{ $pricingPreview ? '' : 'hidden' }}" id="pricingPreviewBox">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">{{ __('general.pages.subscriptions.total') }}</span>
                    <span class="text-lg font-extrabold text-brand-700 dark:text-brand-300" id="pricingPreviewAmount">{{ currencyFormat($pricingPreview['final_price'] ?? 0, true) }}</span>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Payment Method</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3" id="changePlanPaymentMethods">
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 transition dark:border-slate-700 dark:!bg-slate-900" data-method="balance">
                            <input type="radio" name="changePlanMethod" class="hidden" value="balance">
                            <i class="fa fa-wallet text-2xl text-brand-500"></i>
                            <span class="text-center text-xs font-bold text-slate-700 dark:text-white">{{ __('general.pages.subscriptions.account_balance') }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ currencyFormat($accountBalance, true) }}</span>
                        </label>
                        @foreach($paymentMethods as $pm)
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 transition dark:border-slate-700 dark:!bg-slate-900" data-method="{{ $pm->id }}" data-manual="{{ $pm->manual ? '1' : '0' }}">
                            <input type="radio" name="changePlanMethod" class="hidden" value="{{ $pm->id }}">
                            <i class="fa-solid fa-money-bill-wave text-2xl text-slate-400"></i>
                            <span class="text-center text-xs font-bold text-slate-700 dark:text-white">{{ $pm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="pay_from_balance"></p>
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="payment_method_id"></p>
                </div>

                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 dark:border-slate-700 dark:!bg-slate-800 hidden" id="changePlanManualBox">
                    <h4 class="mb-1 text-sm font-bold text-blue-900 dark:text-blue-400">Manual Payment</h4>
                    <p class="mb-3 text-xs text-blue-800 dark:text-slate-400">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                    <div class="mb-3 space-y-2 rounded-lg border border-blue-50 bg-white p-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300" id="changePlanManualDetails"></div>

                    <div class="mb-3 flex items-center justify-between rounded-lg border border-blue-50 bg-white p-3 dark:border-slate-700 dark:bg-slate-900 hidden" id="changePlanManualAmountBox">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Amount to transfer</span>
                        <span class="text-lg font-extrabold text-brand-dark dark:text-white" id="changePlanManualAmount"></span>
                    </div>

                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Upload Receipt <span class="text-rose-500">*</span></label>
                    <input type="file" id="changePlanReceiptFile" accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full rounded-lg border border-slate-200 bg-white p-2 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:file:bg-slate-700 dark:file:text-brand-400">
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="receipt_file"></p>
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4 dark:border-slate-700">
                <button type="button" id="cancelChangePlanBtn" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancel</button>
                <button type="button" id="confirmChangePlanBtn" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 disabled:opacity-60">
                    <span id="confirmChangePlanIdle"><i class="fa fa-check mr-1"></i> Confirm</span>
                    <span id="confirmChangePlanBusy" class="hidden"><i class="fa fa-spinner fa-spin mr-1"></i> Processing...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" id="topUpModal">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-xl dark:bg-slate-900">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white"><i class="fa fa-wallet mr-2"></i>Add Balance</h3>
                <button type="button" id="closeTopUpBtn" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa fa-times text-lg"></i>
                </button>
            </div>

            <div class="space-y-5 px-6 py-5">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Amount (USD)</label>
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 dark:border-slate-700 dark:!bg-slate-800">
                        <span class="text-slate-400">$</span>
                        <input type="number" step="0.01" min="1" id="topUpAmount" placeholder="e.g. 50"
                            class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 focus:outline-none focus:ring-0 dark:text-white">
                    </div>
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="amount"></p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Payment Method</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3" id="topUpPaymentMethods">
                        @foreach($paymentMethods as $pm)
                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 transition dark:border-slate-700 dark:!bg-slate-900" data-method="{{ $pm->id }}" data-manual="{{ $pm->manual ? '1' : '0' }}">
                            <input type="radio" name="topUpMethod" class="hidden" value="{{ $pm->id }}">
                            <i class="fa-solid fa-money-bill-wave text-2xl text-slate-400"></i>
                            <span class="text-center text-xs font-bold text-slate-700 dark:text-white">{{ $pm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="payment_method_id"></p>
                </div>

                <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 dark:border-slate-700 dark:!bg-slate-800 hidden" id="topUpManualBox">
                    <h4 class="mb-1 text-sm font-bold text-blue-900 dark:text-blue-400">Manual Payment</h4>
                    <p class="mb-3 text-xs text-blue-800 dark:text-slate-400">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                    <div class="mb-3 space-y-2 rounded-lg border border-blue-50 bg-white p-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300" id="topUpManualDetails"></div>

                    <div class="mb-3 flex items-center justify-between rounded-lg border border-blue-50 bg-white p-3 dark:border-slate-700 dark:bg-slate-900 hidden" id="topUpManualAmountBox">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Amount to transfer</span>
                        <span class="text-lg font-extrabold text-brand-dark dark:text-white" id="topUpManualAmount"></span>
                    </div>

                    <label class="mb-2 block text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Upload Receipt <span class="text-rose-500">*</span></label>
                    <input type="file" id="topUpReceiptFile" accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full rounded-lg border border-slate-200 bg-white p-2 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:file:bg-slate-700 dark:file:text-brand-400">
                    <p class="mt-1 text-xs text-rose-500 hidden" data-error-for="receipt_file"></p>
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4 dark:border-slate-700">
                <button type="button" id="cancelTopUpBtn" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancel</button>
                <button type="button" id="confirmTopUpBtn" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 disabled:opacity-60">
                    <span id="confirmTopUpIdle"><i class="fa fa-check mr-1"></i> Confirm</span>
                    <span id="confirmTopUpBusy" class="hidden"><i class="fa fa-spinner fa-spin mr-1"></i> Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
(function () {
    const csrfToken = '{{ csrf_token() }}';
    const paymentMethods = @json($paymentMethods->keyBy('id'));
    const locale = '{{ app()->getLocale() }}';
    const routes = {
        planPricing: '{{ route('admin.subscriptions.plan-pricing', ['plan' => '__ID__']) }}',
        changePlan: '{{ route('admin.subscriptions.change-plan') }}',
        topUp: '{{ route('admin.subscriptions.top-up') }}',
    };

    function renderManualDetails(container, pm) {
        container.innerHTML = '';
        const details = pm.details || [];
        if (!Array.isArray(details) || details.length === 0) return;
        details.forEach(function (row) {
            const label = (row.label && (row.label[locale] || row.label.en)) || row.key || '';
            const value = (row.value && (row.value[locale] || row.value.en)) || '';
            const line = document.createElement('div');
            line.className = 'flex justify-between gap-4 border-b border-slate-100 pb-1 last:border-b-0 last:pb-0 dark:border-slate-700';
            line.innerHTML = '<span class="font-bold"></span><span class="text-right"></span>';
            line.querySelector('span').textContent = label + ':';
            line.querySelectorAll('span')[1].textContent = value;
            container.appendChild(line);
        });
    }

    function clearErrors(modal) {
        modal.querySelectorAll('[data-error-for]').forEach(function (el) {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }

    function showErrors(modal, errors) {
        clearErrors(modal);
        Object.keys(errors || {}).forEach(function (field) {
            const el = modal.querySelector('[data-error-for="' + field + '"]');
            if (el) {
                el.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                el.classList.remove('hidden');
            }
        });
    }

    function showSuccess(message) {
        if (window.Swal) {
            Swal.fire({ icon: 'success', title: message, showConfirmButton: false, timer: 3000, position: 'center' });
        } else {
            alert(message);
        }
    }

    function showError(message) {
        if (window.Swal) {
            Swal.fire({ icon: 'error', title: message, showConfirmButton: false, timer: 3000, position: 'center' });
        } else {
            alert(message);
        }
    }

    function openModal(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ---- Change Plan modal ----
    const changePlanModal = document.getElementById('changePlanModal');
    if (changePlanModal) {
        const openBtn = document.getElementById('openChangePlanBtn');
        const closeBtn = document.getElementById('closeChangePlanBtn');
        const cancelBtn = document.getElementById('cancelChangePlanBtn');
        const confirmBtn = document.getElementById('confirmChangePlanBtn');
        const planSelect = document.getElementById('selectedPlanId');
        const pricingBox = document.getElementById('pricingPreviewBox');
        const pricingAmount = document.getElementById('pricingPreviewAmount');
        const methodButtons = changePlanModal.querySelectorAll('#changePlanPaymentMethods [data-method]');
        const manualBox = document.getElementById('changePlanManualBox');
        const manualDetails = document.getElementById('changePlanManualDetails');
        const manualAmountBox = document.getElementById('changePlanManualAmountBox');
        const manualAmount = document.getElementById('changePlanManualAmount');
        const receiptInput = document.getElementById('changePlanReceiptFile');

        let currentPricing = null;

        function selectedMethod() {
            const checked = changePlanModal.querySelector('input[name="changePlanMethod"]:checked');
            return checked ? checked.value : null;
        }

        function updateMethodStyles() {
            methodButtons.forEach(function (label) {
                const input = label.querySelector('input');
                label.classList.toggle('border-brand-500', input.checked);
                label.classList.toggle('bg-brand-50', input.checked);
            });
        }

        function updateManualBox() {
            const method = selectedMethod();
            const pm = method && method !== 'balance' ? paymentMethods[method] : null;
            if (pm && pm.manual) {
                manualBox.classList.remove('hidden');
                renderManualDetails(manualDetails, pm);
                if (pm.currency && currentPricing) {
                    const converted = (parseFloat(currentPricing.final_price || 0) * parseFloat(pm.currency.conversion_rate)).toFixed(2);
                    manualAmount.textContent = converted + ' ' + pm.currency.code;
                    manualAmountBox.classList.remove('hidden');
                } else {
                    manualAmountBox.classList.add('hidden');
                }
            } else {
                manualBox.classList.add('hidden');
            }
        }

        function loadPricing() {
            const planId = planSelect.value;
            if (!planId) return;
            fetch(routes.planPricing.replace('__ID__', planId), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    currentPricing = data.pricing;
                    pricingAmount.textContent = Number(currentPricing.final_price || 0).toFixed(2);
                    pricingBox.classList.remove('hidden');
                    updateManualBox();
                });
        }

        methodButtons.forEach(function (label) {
            label.addEventListener('click', function () {
                label.querySelector('input').checked = true;
                updateMethodStyles();
                updateManualBox();
            });
        });

        planSelect.addEventListener('change', loadPricing);

        openBtn && openBtn.addEventListener('click', function () {
            clearErrors(changePlanModal);
            receiptInput.value = '';
            openModal(changePlanModal);
            loadPricing();
        });

        [closeBtn, cancelBtn].forEach(function (btn) {
            btn && btn.addEventListener('click', function () {
                closeModal(changePlanModal);
            });
        });

        confirmBtn.addEventListener('click', function () {
            const method = selectedMethod();
            const formData = new FormData();
            formData.append('plan_id', planSelect.value);
            formData.append('pay_from_balance', method === 'balance' ? '1' : '0');
            if (method && method !== 'balance') {
                formData.append('payment_method_id', method);
            }
            if (receiptInput.files[0]) {
                formData.append('receipt_file', receiptInput.files[0]);
            }

            confirmBtn.disabled = true;
            document.getElementById('confirmChangePlanIdle').classList.add('hidden');
            document.getElementById('confirmChangePlanBusy').classList.remove('hidden');

            fetch(routes.changePlan, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData,
            })
                .then(function (r) { return r.json().then(function (data) { return { status: r.status, data: data }; }); })
                .then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        closeModal(changePlanModal);
                        showSuccess(res.data.message || 'Done.');
                        setTimeout(function () { window.location.reload(); }, 1200);
                    } else if (res.data.redirect) {
                        window.location = res.data.redirect;
                    } else if (res.data.errors) {
                        showErrors(changePlanModal, res.data.errors);
                    } else {
                        showError(res.data.message || 'Something went wrong.');
                    }
                })
                .catch(function () {
                    showError('Something went wrong.');
                })
                .finally(function () {
                    confirmBtn.disabled = false;
                    document.getElementById('confirmChangePlanIdle').classList.remove('hidden');
                    document.getElementById('confirmChangePlanBusy').classList.add('hidden');
                });
        });
    }

    // ---- Top Up modal ----
    const topUpModal = document.getElementById('topUpModal');
    if (topUpModal) {
        const openBtn = document.getElementById('openTopUpBtn');
        const closeBtn = document.getElementById('closeTopUpBtn');
        const cancelBtn = document.getElementById('cancelTopUpBtn');
        const confirmBtn = document.getElementById('confirmTopUpBtn');
        const amountInput = document.getElementById('topUpAmount');
        const methodButtons = topUpModal.querySelectorAll('#topUpPaymentMethods [data-method]');
        const manualBox = document.getElementById('topUpManualBox');
        const manualDetails = document.getElementById('topUpManualDetails');
        const manualAmountBox = document.getElementById('topUpManualAmountBox');
        const manualAmount = document.getElementById('topUpManualAmount');
        const receiptInput = document.getElementById('topUpReceiptFile');

        function selectedMethod() {
            const checked = topUpModal.querySelector('input[name="topUpMethod"]:checked');
            return checked ? checked.value : null;
        }

        function updateMethodStyles() {
            methodButtons.forEach(function (label) {
                const input = label.querySelector('input');
                label.classList.toggle('border-brand-500', input.checked);
                label.classList.toggle('bg-brand-50', input.checked);
            });
        }

        function updateManualBox() {
            const method = selectedMethod();
            const pm = method ? paymentMethods[method] : null;
            if (pm && pm.manual) {
                manualBox.classList.remove('hidden');
                renderManualDetails(manualDetails, pm);
                const amt = parseFloat(amountInput.value || 0);
                if (pm.currency && amt) {
                    const converted = (amt * parseFloat(pm.currency.conversion_rate)).toFixed(2);
                    manualAmount.textContent = converted + ' ' + pm.currency.code;
                    manualAmountBox.classList.remove('hidden');
                } else {
                    manualAmountBox.classList.add('hidden');
                }
            } else {
                manualBox.classList.add('hidden');
            }
        }

        methodButtons.forEach(function (label) {
            label.addEventListener('click', function () {
                label.querySelector('input').checked = true;
                updateMethodStyles();
                updateManualBox();
            });
        });

        amountInput.addEventListener('input', updateManualBox);

        openBtn && openBtn.addEventListener('click', function () {
            clearErrors(topUpModal);
            amountInput.value = '';
            receiptInput.value = '';
            methodButtons.forEach(function (label) { label.querySelector('input').checked = false; });
            updateMethodStyles();
            manualBox.classList.add('hidden');
            openModal(topUpModal);
        });

        [closeBtn, cancelBtn].forEach(function (btn) {
            btn && btn.addEventListener('click', function () {
                closeModal(topUpModal);
            });
        });

        confirmBtn.addEventListener('click', function () {
            const method = selectedMethod();
            const formData = new FormData();
            formData.append('amount', amountInput.value);
            if (method) {
                formData.append('payment_method_id', method);
            }
            if (receiptInput.files[0]) {
                formData.append('receipt_file', receiptInput.files[0]);
            }

            confirmBtn.disabled = true;
            document.getElementById('confirmTopUpIdle').classList.add('hidden');
            document.getElementById('confirmTopUpBusy').classList.remove('hidden');

            fetch(routes.topUp, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData,
            })
                .then(function (r) { return r.json().then(function (data) { return { status: r.status, data: data }; }); })
                .then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        closeModal(topUpModal);
                        showSuccess(res.data.message || 'Done.');
                        setTimeout(function () { window.location.reload(); }, 1200);
                    } else if (res.data.redirect) {
                        window.location = res.data.redirect;
                    } else if (res.data.errors) {
                        showErrors(topUpModal, res.data.errors);
                    } else {
                        showError(res.data.message || 'Something went wrong.');
                    }
                })
                .catch(function () {
                    showError('Something went wrong.');
                })
                .finally(function () {
                    confirmBtn.disabled = false;
                    document.getElementById('confirmTopUpIdle').classList.remove('hidden');
                    document.getElementById('confirmTopUpBusy').classList.add('hidden');
                });
        });
    }
})();
</script>
@endpush
