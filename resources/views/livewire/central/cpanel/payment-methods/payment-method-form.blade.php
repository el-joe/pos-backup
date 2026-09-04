<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $paymentMethod?->id ? 'Edit Payment Method #' . $paymentMethod->id : 'Create Payment Method' }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.payment-methods.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model.defer="data.name">
                    @error('data.name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Icon</label>

                    @php
                        $iconValue = (string)($data['icon_path'] ?? '');
                        $looksLikeImage = $iconValue !== '' && (
                            str_contains($iconValue, '/') ||
                            str_contains($iconValue, '.png') ||
                            str_contains($iconValue, '.jpg') ||
                            str_contains($iconValue, '.jpeg') ||
                            str_contains($iconValue, '.gif') ||
                            str_contains($iconValue, '.svg')
                        );

                        $iconOptions = [
                            'fa-solid fa-credit-card' => 'Credit Card',
                            'fa-solid fa-money-bill-wave' => 'Cash / Bank Transfer',
                            'fa-solid fa-wallet' => 'Wallet',
                            'fa-solid fa-building-columns' => 'Bank',
                            'fa-solid fa-qrcode' => 'QR Code',
                            'fa-solid fa-mobile-screen-button' => 'Mobile Payment',
                            'fa-solid fa-receipt' => 'Receipt',
                            'fa-solid fa-hand-holding-dollar' => 'Manual Payment',
                            'fa-solid fa-coins' => 'Coins',
                            'fa-solid fa-store' => 'Store',
                            'fa-solid fa-globe' => 'Online',
                            'fa-brands fa-cc-visa' => 'Visa',
                            'fa-brands fa-cc-mastercard' => 'Mastercard',
                            'fa-brands fa-cc-amex' => 'American Express',
                            'fa-brands fa-paypal' => 'PayPal',
                            'fa-brands fa-stripe' => 'Stripe',
                            'fa-brands fa-bitcoin' => 'Bitcoin',
                            'fa-brands fa-apple-pay' => 'Apple Pay',
                            'fa-brands fa-google-pay' => 'Google Pay',
                        ];
                    @endphp

                    <select class="form-select select2" name="data.icon_path">
                        <option value="" {{ $iconValue === '' ? 'selected' : '' }}>—</option>
                        @foreach($iconOptions as $class => $label)
                            <option
                                value="{{ $class }}"
                                data-content="<i class='{{ $class }} fa-lg'></i>"
                                {{ $iconValue === $class ? 'selected' : '' }}
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('data.icon_path') <div class="text-danger small">{{ $message }}</div> @enderror

                    <div class="mt-2">
                        @if($looksLikeImage)
                            <img src="{{ asset('storage/' . $iconValue) }}" alt="icon" class="img-fluid rounded" style="max-height: 64px;">
                        @elseif($iconValue !== '')
                            <div class="d-flex align-items-center gap-2">
                                <i class="{{ $iconValue }} fa-2xl"></i>
                                <span class="text-muted small">{{ $iconValue }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Provider (unique)</label>
                    <input type="text" class="form-control" wire:model.defer="data.provider" placeholder="Paypal / Stripe / ...">
                    @error('data.provider') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gateway Type</label>
                    <select class="form-select" wire:model.defer="data.gateway_type">
                        <option value="stripe">Stripe</option>
                        <option value="paymob">Paymob</option>
                        <option value="manual">Manual</option>
                        <option value="other">Other</option>
                    </select>
                    @error('data.gateway_type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fee Percentage</label>
                    <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="data.fee_percentage">
                    @error('data.fee_percentage') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fixed Fee</label>
                    <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="data.fixed_fee">
                    @error('data.fixed_fee') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" wire:model.defer="data.active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="manual" wire:model.live="data.manual">
                        <label class="form-check-label" for="manual">Manual (no API integration)</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Required Fields (one key per line)</label>
                    <textarea class="form-control" rows="4" wire:model.live="requiredFieldsText" placeholder="client_id\nclient_secret\n..." ></textarea>
                    @error('requiredFieldsText') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="form-text">These keys will be stored in <code>required_fields</code> and used to render Credentials inputs.</div>
                </div>

                <div class="col-12">
                    <h6 class="mb-2">Credentials</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 35%">Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requiredFields as $idx => $key)
                                    <tr>
                                        <td><code>{{ $key }}</code></td>
                                        <td>
                                            @if($key === 'mode')
                                                <select class="form-select" wire:model.defer="credentialsInputs.{{ $idx }}.value">
                                                    <option value="sandbox">Sandbox (test)</option>
                                                    <option value="live">Live (production)</option>
                                                </select>
                                            @else
                                                <input type="text" class="form-control" wire:model.defer="credentialsInputs.{{ $idx }}.value" placeholder="Value">
                                            @endif
                                            @error('credentialsInputs.'.$idx.'.value') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No required fields defined.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <h6 class="mb-2">Supported Countries</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="isWorldwide" wire:model.live="isWorldwide">
                        <label class="form-check-label" for="isWorldwide">Worldwide (available in all countries)</label>
                    </div>

                    @unless($isWorldwide)
                        <div class="country-picker" x-data="{ q: '' }">
                            <div class="country-picker-search">
                                <i class="fa fa-search"></i>
                                <input type="text" x-model="q" placeholder="Search countries...">
                            </div>

                            @if(count($supportedCountries ?? []))
                                <div class="country-picker-chips">
                                    @foreach($countries as $country)
                                        @continue(!in_array($country->id, $supportedCountries ?? []))
                                        <span class="country-chip">
                                            {{ $country->name }}
                                            <button type="button" wire:click="toggleCountry({{ $country->id }})" aria-label="Remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="country-picker-list">
                                @forelse($countries as $country)
                                    <label
                                        class="country-picker-option"
                                        x-show="!q || {{ Illuminate\Support\Js::from(mb_strtolower($country->name)) }}.includes(q.toLowerCase())"
                                    >
                                        <input
                                            type="checkbox"
                                            wire:click="toggleCountry({{ $country->id }})"
                                            @checked(in_array($country->id, $supportedCountries ?? []))
                                        >
                                        <span>{{ $country->name }}</span>
                                    </label>
                                @empty
                                    <div class="country-picker-empty">No countries available.</div>
                                @endforelse
                            </div>
                        </div>
                        @error('supportedCountries') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <div class="form-text">This payment method will only be shown to visitors/tenants from the selected countries.</div>
                    @endunless
                </div>

                @if(($data['manual'] ?? false))
                    <div class="col-md-4">
                        <label class="form-label">Default Currency</label>
                        <select class="form-select" wire:model.defer="data.currency_id">
                            <option value="">Select currency...</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->code }} ({{ $currency->symbol }})</option>
                            @endforeach
                        </select>
                        @error('data.currency_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        <div class="form-text">Amounts for this manual payment method will be converted from USD and shown to customers in this currency at checkout.</div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Manual Details (JSON)</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addDetailRow">
                                <i class="fa fa-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 18%">Key</th>
                                        <th style="width: 20%">Label (EN)</th>
                                        <th style="width: 20%">Label (AR)</th>
                                        <th>Value (EN)</th>
                                        <th>Value (AR)</th>
                                        <th style="width: 1%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailsInputs as $i => $row)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.key" placeholder="bank_name / phone_number">
                                                @error('detailsInputs.'.$i.'.key') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.label_en" placeholder="Bank Name">
                                                @error('detailsInputs.'.$i.'.label_en') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.label_ar" placeholder="اسم البنك">
                                                @error('detailsInputs.'.$i.'.label_ar') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.value_en" placeholder="Global Corporate Bank">
                                                @error('detailsInputs.'.$i.'.value_en') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.value_ar" placeholder="Global Corporate Bank">
                                                @error('detailsInputs.'.$i.'.value_ar') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeDetailRow({{ $i }})" title="Remove">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-text">Saved as <code>details</code> JSON rows (translated labels + values).</div>
                    </div>
                @endif

                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .country-picker {
        border: 1px solid var(--bs-border-color);
        border-radius: .5rem;
        background-color: var(--bs-body-bg);
        overflow: hidden;
    }

    .country-picker-search {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem .75rem;
        border-bottom: 1px solid var(--bs-border-color);
        background-color: var(--bs-tertiary-bg);
    }

    .country-picker-search i {
        color: var(--bs-secondary-color);
        font-size: .8rem;
    }

    .country-picker-search input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        color: var(--bs-body-color);
        font-size: .875rem;
        padding: .25rem 0;
    }

    .country-picker-search input::placeholder {
        color: var(--bs-secondary-color);
    }

    .country-picker-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .375rem;
        padding: .625rem .75rem;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .country-chip {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        padding: .25rem .5rem .25rem .625rem;
        border-radius: 999px;
        background-color: rgba(111, 66, 193, .12);
        color: var(--bs-primary, #6f42c1);
        font-size: .8125rem;
        font-weight: 500;
        line-height: 1;
    }

    .country-chip button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border: none;
        background: transparent;
        color: inherit;
        opacity: .7;
        padding: 0;
        font-size: .7rem;
        cursor: pointer;
    }

    .country-chip button:hover {
        opacity: 1;
    }

    .country-picker-list {
        max-height: 240px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .country-picker-list::-webkit-scrollbar {
        width: 6px;
    }

    .country-picker-list::-webkit-scrollbar-thumb {
        background-color: var(--bs-border-color);
        border-radius: 4px;
    }

    .country-picker-option {
        display: flex;
        align-items: center;
        gap: .625rem;
        padding: .5rem .75rem;
        margin: 0;
        cursor: pointer;
        font-size: .875rem;
        color: var(--bs-body-color);
        border-bottom: 1px solid var(--bs-border-color-translucent, var(--bs-border-color));
        transition: background-color .1s ease-in-out;
    }

    .country-picker-option:last-child {
        border-bottom: none;
    }

    .country-picker-option:hover {
        background-color: var(--bs-tertiary-bg);
    }

    .country-picker-option input[type="checkbox"] {
        margin: 0;
        cursor: pointer;
    }

    .country-picker-empty {
        padding: 1rem;
        text-align: center;
        color: var(--bs-secondary-color);
        font-size: .875rem;
    }
</style>
    <link href="{{ asset('template/vendors/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ asset('hud/assets/js/select2.min.js') }}"></script>
    @include('layouts.hud.partials.select2-script')
@endpush
