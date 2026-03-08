<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.code') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $data['code'] ?? '---' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.cost') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ currencyFormat($data['cost'] ?? 0, true) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.fixed_assets.status_' . ($data['status'] ?? 'active')) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.payment_status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.fixed_assets.payment_' . (($data['payment_status'] ?? 'full_paid') === 'pending' ? 'unpaid' : (($data['payment_status'] ?? 'full_paid') === 'partial_paid' ? 'partial' : 'paid'))) }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.fixed_assets.fixed_asset_details')" icon="fa fa-building">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.branch') }}</label>
                @if(admin()->branch_id == null)
                    <select class="form-select select2" name="data.branch_id">
                        <option value="">{{ __('general.pages.fixed_assets.select_branch') }}</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branch->id == ($data['branch_id'] ?? 0) ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text" class="form-control" value="{{ admin()->branch?->name }}" disabled>
                @endif
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.code') }}</label>
                <input type="text" class="form-control" wire:model="data.code" disabled>
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.name') }}</label>
                <input type="text" class="form-control" wire:model="data.name">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.purchase_date') }}</label>
                <input type="date" class="form-control" wire:model="data.purchase_date">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.cost') }}</label>
                <input type="number" step="0.01" min="0" class="form-control" wire:model="data.cost">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.payment_status') }}</label>
                <select class="form-select select2" name="data.payment_status">
                    <option value="pending" {{ ($data['payment_status'] ?? 'full_paid') == 'pending' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.payment_unpaid') }}</option>
                    <option value="partial_paid" {{ ($data['payment_status'] ?? '') == 'partial_paid' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.payment_partial') }}</option>
                    <option value="full_paid" {{ ($data['payment_status'] ?? 'full_paid') == 'full_paid' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.payment_paid') }}</option>
                </select>
                @error('data.payment_status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @if(in_array(($data['payment_status'] ?? 'full_paid'), ['partial_paid', 'full_paid']))
                <div>
                    <label class="form-label">{{ __('general.pages.fixed_assets.payment_account') }}</label>
                    @php($paymentAccountsList = collect($paymentAccounts ?? [])->filter())
                    <select class="form-select select2" name="data.payment_account">
                        <option value="">{{ __('general.pages.fixed_assets.select_payment_account') }}</option>
                        @foreach($paymentAccountsList as $account)
                            <option value="{{ $account->id }}" {{ ($data['payment_account'] ?? '') == $account->id ? 'selected' : '' }}>
                                {{ $account->paymentMethod?->name }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('data.payment_account')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            @endif

            @if(($data['payment_status'] ?? '') === 'partial_paid')
                <div>
                    <label class="form-label">{{ __('general.pages.fixed_assets.paid_amount') }}</label>
                    <input type="number" step="0.01" min="0" class="form-control" wire:model="data.payment_amount">
                    @error('data.payment_amount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            @endif

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.salvage_value') }}</label>
                <input type="number" step="0.01" min="0" class="form-control" wire:model="data.salvage_value">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.useful_life_months') }}</label>
                <input type="number" step="1" min="0" class="form-control" wire:model="data.useful_life_months">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.depreciation_rate') }}</label>
                <input type="number" step="0.0001" min="0" max="100" class="form-control" wire:model="data.depreciation_rate">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.depreciation_method') }}</label>
                <select class="form-select select2" name="data.depreciation_method">
                    <option value="straight_line" {{ ($data['depreciation_method'] ?? 'straight_line') == 'straight_line' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.method_straight_line') }}</option>
                    <option value="declining_balance" {{ ($data['depreciation_method'] ?? '') == 'declining_balance' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.method_declining_balance') }}</option>
                    <option value="double_declining_balance" {{ ($data['depreciation_method'] ?? '') == 'double_declining_balance' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.method_double_declining_balance') }}</option>
                </select>
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.depreciation_start_date') }}</label>
                <input type="date" class="form-control" wire:model="data.depreciation_start_date">
            </div>

            <div>
                <label class="form-label">{{ __('general.pages.fixed_assets.status') }}</label>
                <select class="form-select select2" name="data.status">
                    <option value="active" {{ ($data['status'] ?? 'active') == 'active' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_active') }}</option>
                    <option value="under_construction" {{ ($data['status'] ?? '') == 'under_construction' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_under_construction') }}</option>
                    <option value="disposed" {{ ($data['status'] ?? '') == 'disposed' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_disposed') }}</option>
                    <option value="sold" {{ ($data['status'] ?? '') == 'sold' ? 'selected' : '' }}>{{ __('general.pages.fixed_assets.status_sold') }}</option>
                </select>
            </div>

            <div class="md:col-span-2 xl:col-span-3">
                <label class="form-label">{{ __('general.pages.fixed_assets.note') }}</label>
                <textarea class="form-control" rows="2" wire:model="data.note"></textarea>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex justify-end">
                <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="saveAsset">
                    <i class="fa fa-save"></i> {{ __('general.pages.fixed_assets.save') }}
                </button>
            </div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
