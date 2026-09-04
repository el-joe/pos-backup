<div class="row gx-4 py-5">

    {{-- TOP SUMMARY CARDS --}}
    <div class="row mb-4">
        {{-- CURRENT SUBSCRIPTION --}}
        @if($currentSubscription)
        @php
            $statusBadge = [
                'paid' => 'success',
                'expired' => 'danger',
                'cancelled' => 'warning',
            ][$currentSubscription->status] ?? 'secondary';
        @endphp

        @if($currentSubscription->status === 'paid' && !is_null($daysRemaining) && $daysRemaining < 30)
            <div class="col-12 mb-3">
                <div class="alert alert-warning d-flex align-items-center mb-0">
                    <i class="fa fa-triangle-exclamation me-2"></i>
                    {{ __('general.pages.subscriptions.expires_in_days', ['days' => $daysRemaining]) }}
                </div>
            </div>
        @endif

        <div class="col-8 mb-4">
            <div class="card shadow-sm overflow-hidden">

                {{-- STATUS BAR --}}
                <div class="bg-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fa fa-crown me-2"></i>
                        <strong>{{ __('general.pages.subscriptions.active_subscription') }}</strong>
                    </div>
                    <span>
                        @if($currentSubscription->is_trial)
                            <span class="badge bg-info me-1">{{ __('general.pages.subscriptions.free_trial') }}</span>
                        @endif
                        <span class="badge bg-{{ $statusBadge }}">
                            {{ ucfirst($currentSubscription->status) }}
                        </span>
                    </span>
                </div>

                <div class="card-body">

                    {{-- PLAN INFO --}}
                    <div class="row text-center mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.plan') }}</div>
                            <div class="fw-bold fs-5">
                                {{ $currentSubscription->plan?->localizedName() }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.price') }}</div>
                            <div class="fw-bold fs-5">
                                {{ $currentSubscription->is_trial ? __('general.pages.subscriptions.free_trial') : currencyFormat($currentSubscription->price, true) }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.start_date') }}</div>
                            <div class="fw-semibold">
                                {{ dateTimeFormat($currentSubscription->start_date,true,false) }}
                            </div>
                        </div>

                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-muted small">{{ __('general.pages.subscriptions.end_date') }}</div>
                            <div class="fw-semibold">
                                {{ dateTimeFormat($currentSubscription->end_date,true,false) }}
                            </div>
                        </div>
                    </div>

                    {{-- PROGRESS --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">{{ __('general.pages.subscriptions.subscription_progress') }}</small>
                            <small class="fw-semibold text-primary">
                                {{ __('general.pages.subscriptions.days_remaining', ['days' => $daysRemaining]) }}
                            </small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div
                                class="progress-bar bg-success"
                                style="width: {{ $percentRemaining }}%">
                            </div>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    @if(adminCan('subscriptions.renew'))
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary" id="openChangePlanBtn">
                            <i class="fa fa-right-left me-1"></i> {{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}
                        </button>
                    </div>
                    @endif

                </div>

                {{-- CARD ARROWS --}}
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>

            </div>
        </div>
        @endif


        {{-- ACCOUNT BALANCE --}}
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-wallet me-2"></i> {{ __('general.pages.subscriptions.account_balance') }}
                    </h5>
                </div>

                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-muted mb-2">{{ __('general.pages.subscriptions.available_balance') }}</div>
                    <div class="display-6 fw-bold text-primary mb-3">
                        {{ currencyFormat($accountBalance,true) }}
                    </div>
                    @if(adminCan('subscriptions.renew'))
                    <button type="button" class="btn btn-outline-primary btn-sm" id="openTopUpBtn">
                        <i class="fa fa-plus me-1"></i> Add Balance
                    </button>
                    @endif
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- PREVIOUS SUBSCRIPTIONS --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-history me-2"></i> {{ __('general.pages.subscriptions.previous_subscriptions') }}
                    </h5>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
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
                                @php
                                    $rowBadge = [
                                        'paid' => 'success',
                                        'expired' => 'danger',
                                        'cancelled' => 'warning',
                                    ][$subscription->status] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subscription->plan?->localizedName() }}</td>
                                    <td>{{ dateTimeFormat($subscription->start_date,true,false) }}</td>
                                    <td>{{ dateTimeFormat($subscription->end_date,true,false) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rowBadge }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ currencyFormat($subscription->price, true) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        {{ __('general.pages.subscriptions.no_previous_subscriptions_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>
    </div>

    @if(adminCan('subscriptions.renew'))
    <div class="modal d-none" id="changePlanModal" tabindex="-1" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-right-left me-2"></i>{{ __('general.pages.subscriptions.renew') }} / {{ __('general.pages.subscriptions.change_plan') }}</h5>
                    <button type="button" class="btn-close" id="closeChangePlanBtn"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('general.pages.subscriptions.plan') }}</label>
                        <select class="form-select" id="selectedPlanId">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ $selectedPlanId == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->localizedName() }} — {{ currencyFormat($plan->price, true) }} / {{ $plan->isYearly() ? 'year' : 'month' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-danger small mt-1 d-none" data-error-for="plan_id"></div>
                    </div>

                    <div class="alert alert-light border d-flex justify-content-between align-items-center {{ $pricingPreview ? '' : 'd-none' }}" id="pricingPreviewBox">
                        <span class="fw-semibold">{{ __('general.pages.subscriptions.total') }}</span>
                        <span class="fs-5 fw-bold text-primary" id="pricingPreviewAmount">{{ currencyFormat($pricingPreview['final_price'] ?? 0, true) }}</span>
                    </div>

                    <label class="form-label fw-semibold">Payment Method</label>
                    <div class="row g-3 mb-3" id="changePlanPaymentMethods">
                        <div class="col-md-4">
                            <label class="border rounded-3 p-3 d-flex flex-column align-items-center gap-2 h-100 cursor-pointer" style="cursor:pointer" data-method="balance">
                                <input type="radio" name="changePlanMethod" class="d-none" value="balance">
                                <i class="fa fa-wallet fs-3 text-primary"></i>
                                <span class="fw-semibold text-center">{{ __('general.pages.subscriptions.account_balance') }}</span>
                                <span class="small text-muted">{{ currencyFormat($accountBalance, true) }} available</span>
                            </label>
                        </div>
                        @foreach($paymentMethods as $pm)
                        <div class="col-md-4">
                            <label class="border rounded-3 p-3 d-flex flex-column align-items-center gap-2 h-100 cursor-pointer" style="cursor:pointer" data-method="{{ $pm->id }}" data-manual="{{ $pm->manual ? '1' : '0' }}">
                                <input type="radio" name="changePlanMethod" class="d-none" value="{{ $pm->id }}">
                                <i class="fa fa-money-bill-wave fs-3 text-secondary"></i>
                                <span class="fw-semibold text-center">{{ $pm->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-danger small mb-2 d-none" data-error-for="pay_from_balance"></div>
                    <div class="text-danger small mb-2 d-none" data-error-for="payment_method_id"></div>

                    <div class="bg-light border rounded-3 p-3 mb-3 d-none" id="changePlanManualBox">
                        <h6 class="fw-bold text-primary mb-1">Manual Payment</h6>
                        <p class="small text-muted mb-3">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                        <div class="bg-white p-3 rounded border mb-3 small" id="changePlanManualDetails"></div>

                        <div class="bg-white p-3 rounded border mb-3 d-flex justify-content-between align-items-center d-none" id="changePlanManualAmountBox">
                            <span class="fw-semibold">Amount to transfer</span>
                            <span class="fs-5 fw-bold text-primary" id="changePlanManualAmount"></span>
                        </div>

                        <label class="form-label fw-semibold">Upload Receipt <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="changePlanReceiptFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="text-danger small mt-1 d-none" data-error-for="receipt_file"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelChangePlanBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmChangePlanBtn">
                        <span id="confirmChangePlanIdle"><i class="fa fa-check me-1"></i> Confirm</span>
                        <span id="confirmChangePlanBusy" class="d-none"><i class="fa fa-spinner fa-spin me-1"></i> Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal d-none" id="topUpModal" tabindex="-1" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-wallet me-2"></i>Add Balance</h5>
                    <button type="button" class="btn-close" id="closeTopUpBtn"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Amount (USD)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="1" class="form-control" id="topUpAmount" placeholder="e.g. 50">
                        </div>
                        <div class="text-danger small mt-1 d-none" data-error-for="amount"></div>
                    </div>

                    <label class="form-label fw-semibold">Payment Method</label>
                    <div class="row g-3 mb-3" id="topUpPaymentMethods">
                        @foreach($paymentMethods as $pm)
                        <div class="col-md-4">
                            <label class="border rounded-3 p-3 d-flex flex-column align-items-center gap-2 h-100 cursor-pointer" style="cursor:pointer" data-method="{{ $pm->id }}" data-manual="{{ $pm->manual ? '1' : '0' }}">
                                <input type="radio" name="topUpMethod" class="d-none" value="{{ $pm->id }}">
                                <i class="fa fa-money-bill-wave fs-3 text-secondary"></i>
                                <span class="fw-semibold text-center">{{ $pm->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-danger small mb-2 d-none" data-error-for="payment_method_id"></div>

                    <div class="bg-light border rounded-3 p-3 mb-3 d-none" id="topUpManualBox">
                        <h6 class="fw-bold text-primary mb-1">Manual Payment</h6>
                        <p class="small text-muted mb-3">Please transfer the amount using the details below, then upload the receipt to continue.</p>

                        <div class="bg-white p-3 rounded border mb-3 small" id="topUpManualDetails"></div>

                        <div class="bg-white p-3 rounded border mb-3 d-flex justify-content-between align-items-center d-none" id="topUpManualAmountBox">
                            <span class="fw-semibold">Amount to transfer</span>
                            <span class="fs-5 fw-bold text-primary" id="topUpManualAmount"></span>
                        </div>

                        <label class="form-label fw-semibold">Upload Receipt <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="topUpReceiptFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="text-danger small mt-1 d-none" data-error-for="receipt_file"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelTopUpBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmTopUpBtn">
                        <span id="confirmTopUpIdle"><i class="fa fa-check me-1"></i> Confirm</span>
                        <span id="confirmTopUpBusy" class="d-none"><i class="fa fa-spinner fa-spin me-1"></i> Processing...</span>
                    </button>
                </div>
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

    function formatMoney(amount, code) {
        return Number(amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + code;
    }

    function renderManualDetails(container, pm) {
        container.innerHTML = '';
        const details = pm.details || [];
        if (!Array.isArray(details) || details.length === 0) return;
        details.forEach(function (row) {
            const label = (row.label && (row.label[locale] || row.label.en)) || row.key || '';
            const value = (row.value && (row.value[locale] || row.value.en)) || '';
            const line = document.createElement('div');
            line.className = 'd-flex justify-content-between border-bottom pb-1 mb-1';
            line.innerHTML = '<span class="fw-semibold"></span><span></span>';
            line.querySelector('span').textContent = label + ':';
            line.querySelectorAll('span')[1].textContent = value;
            container.appendChild(line);
        });
    }

    function clearErrors(modal) {
        modal.querySelectorAll('[data-error-for]').forEach(function (el) {
            el.textContent = '';
            el.classList.add('d-none');
        });
    }

    function showErrors(modal, errors) {
        clearErrors(modal);
        Object.keys(errors || {}).forEach(function (field) {
            const el = modal.querySelector('[data-error-for="' + field + '"]');
            if (el) {
                el.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                el.classList.remove('d-none');
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
                label.classList.toggle('border-primary', input.checked);
                label.classList.toggle('bg-primary-subtle', input.checked);
            });
        }

        function updateManualBox() {
            const method = selectedMethod();
            const pm = method && method !== 'balance' ? paymentMethods[method] : null;
            if (pm && pm.manual) {
                manualBox.classList.remove('d-none');
                renderManualDetails(manualDetails, pm);
                if (pm.currency && currentPricing) {
                    const converted = (parseFloat(currentPricing.final_price || 0) * parseFloat(pm.currency.conversion_rate)).toFixed(2);
                    manualAmount.textContent = converted + ' ' + pm.currency.code;
                    manualAmountBox.classList.remove('d-none');
                } else {
                    manualAmountBox.classList.add('d-none');
                }
            } else {
                manualBox.classList.add('d-none');
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
                    pricingBox.classList.remove('d-none');
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
            changePlanModal.classList.remove('d-none');
            loadPricing();
        });

        [closeBtn, cancelBtn].forEach(function (btn) {
            btn && btn.addEventListener('click', function () {
                changePlanModal.classList.add('d-none');
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
            document.getElementById('confirmChangePlanIdle').classList.add('d-none');
            document.getElementById('confirmChangePlanBusy').classList.remove('d-none');

            fetch(routes.changePlan, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData,
            })
                .then(function (r) { return r.json().then(function (data) { return { status: r.status, data: data }; }); })
                .then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        changePlanModal.classList.add('d-none');
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
                    document.getElementById('confirmChangePlanIdle').classList.remove('d-none');
                    document.getElementById('confirmChangePlanBusy').classList.add('d-none');
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
                label.classList.toggle('border-primary', input.checked);
                label.classList.toggle('bg-primary-subtle', input.checked);
            });
        }

        function updateManualBox() {
            const method = selectedMethod();
            const pm = method ? paymentMethods[method] : null;
            if (pm && pm.manual) {
                manualBox.classList.remove('d-none');
                renderManualDetails(manualDetails, pm);
                const amt = parseFloat(amountInput.value || 0);
                if (pm.currency && amt) {
                    const converted = (amt * parseFloat(pm.currency.conversion_rate)).toFixed(2);
                    manualAmount.textContent = converted + ' ' + pm.currency.code;
                    manualAmountBox.classList.remove('d-none');
                } else {
                    manualAmountBox.classList.add('d-none');
                }
            } else {
                manualBox.classList.add('d-none');
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
            manualBox.classList.add('d-none');
            topUpModal.classList.remove('d-none');
        });

        [closeBtn, cancelBtn].forEach(function (btn) {
            btn && btn.addEventListener('click', function () {
                topUpModal.classList.add('d-none');
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
            document.getElementById('confirmTopUpIdle').classList.add('d-none');
            document.getElementById('confirmTopUpBusy').classList.remove('d-none');

            fetch(routes.topUp, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData,
            })
                .then(function (r) { return r.json().then(function (data) { return { status: r.status, data: data }; }); })
                .then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        topUpModal.classList.add('d-none');
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
                    document.getElementById('confirmTopUpIdle').classList.remove('d-none');
                    document.getElementById('confirmTopUpBusy').classList.add('d-none');
                });
        });
    }
})();
</script>
@endpush
