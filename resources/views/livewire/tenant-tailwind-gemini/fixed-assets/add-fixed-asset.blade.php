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
                <div class="md:col-span-2 xl:col-span-2">
                    <label class="form-label">{{ __('general.pages.fixed_assets.payment_distribution') }}</label>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                        <div class="grid gap-3 md:grid-cols-3 md:items-center">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.total_paid') }}</p>
                                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ currencyFormat($totalPaid ?? 0, true) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.due_amount') }}</p>
                                <p class="mt-2 text-base font-semibold text-rose-600 dark:text-rose-400">{{ currencyFormat($remainingDue ?? 0, true) }}</p>
                            </div>
                            <div class="md:text-end">
                                <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-toggle="modal" data-bs-target="#fixedAssetPaymentsModal">
                                    <i class="fa fa-credit-card"></i> {{ __('general.pages.fixed_assets.manage_payments') }}
                                </button>
                            </div>
                        </div>
                    </div>
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

    <div class="modal fade" id="fixedAssetPaymentsModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-xl">
                <div class="rounded-3xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between rounded-t-3xl bg-slate-900 px-6 py-4 text-white dark:bg-slate-800">
                        <h5 class="text-base font-semibold"><i class="fa fa-credit-card me-2"></i>{{ __('general.pages.fixed_assets.manage_payments') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 grid gap-3 rounded-2xl border border-sky-200 bg-sky-50 p-4 text-sm text-sky-900 md:grid-cols-3 dark:border-sky-900/60 dark:bg-sky-950/30 dark:text-sky-100">
                            <div><strong>{{ __('general.pages.fixed_assets.cost') }}:</strong> {{ currencyFormat($cost ?? 0, true) }}</div>
                            <div><strong>{{ __('general.pages.fixed_assets.total_paid') }}:</strong> {{ currencyFormat($totalPaid ?? 0, true) }}</div>
                            <div><strong>{{ __('general.pages.fixed_assets.due_amount') }}:</strong> {{ currencyFormat($remainingDue ?? 0, true) }}</div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table table-bordered align-middle mb-3">
                                <thead>
                                    <tr>
                                        <th>{{ __('general.pages.fixed_assets.payment_account') }}</th>
                                        <th>{{ __('general.pages.fixed_assets.amount') }}</th>
                                        <th class="text-center">{{ __('general.pages.fixed_assets.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payments as $index => $payment)
                                        <tr>
                                            <td>
                                                <select class="form-select" wire:model="payments.{{ $index }}.account_id">
                                                    <option value="">{{ __('general.pages.fixed_assets.select_payment_account') }}</option>
                                                    @foreach (collect($paymentAccounts ?? [])->filter() as $account)
                                                        <option value="{{ data_get($account, 'id') }}">{{ data_get($account, 'paymentMethod.name') }} - {{ data_get($account, 'name') }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" wire:model="payments.{{ $index }}.amount" step="0.01" min="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-600 text-white transition hover:bg-rose-700" wire:click="removePayment({{ $index }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.no_payment_rows') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" wire:click="addPayment">
                            <i class="fa fa-plus"></i> {{ __('general.pages.fixed_assets.add_payment') }}
                        </button>
                    </div>

                    <div class="flex justify-end px-6 pb-6">
                        <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" data-bs-dismiss="modal">{{ __('general.pages.fixed_assets.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
