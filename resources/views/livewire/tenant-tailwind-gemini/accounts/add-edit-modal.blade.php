<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden rounded-[28px] border-0 shadow-2xl dark:bg-slate-900">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 dark:border-slate-800 dark:bg-slate-950/70">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h5 class="text-lg font-semibold text-slate-900 dark:text-white" id="editAccountModalLabel">{{ $current?->id ? __('general.pages.admins.edit') : __('general.pages.accounts.new_account') }} {{ __('general.pages.accounts.accounts') }}</h5>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Configure branch scope, payment method, and account state.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body bg-white px-6 py-6 dark:bg-slate-900">
                <form class="space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label for="branchName" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.accounts.name') }}</label>
                            <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.name" id="branchName" placeholder="{{ __('general.pages.accounts.enter_name') }}">
                        </div>

                        <div>
                            <label for="branchCode" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.accounts.code') }}</label>
                            <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.code" id="branchCode" placeholder="{{ __('general.pages.accounts.enter_code') }}">
                        </div>

                        @if(!(($filters['model_type'] ?? false) == \App\Models\Tenant\User::class && ($filters['model_id'] ?? false)))
                            <div>
                                <label for="branchSelect" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.accounts.branch') }}</label>
                                <select class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model.live="data.branch_id" id="branchSelect">
                                    <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="paymentMethodSelect" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.accounts.payment_method') }}</label>
                            <select class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.payment_method_id" id="paymentMethodSelect">
                                <option value="">{{ __('general.pages.accounts.select_payment_method') }}</option>
                                @foreach($paymenthMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(!(($filters['model_type'] ?? false) == \App\Models\Tenant\User::class && ($filters['model_id'] ?? false)))
                            <div>
                                <label for="branchType" class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.accounts.type') }}</label>
                                <select class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.type" id="branchType">
                                    <option value="">{{ __('general.pages.accounts.select_type') }}</option>
                                    @foreach($accountTypes as $type)
                                        <option value="{{ $type->value }}" {{ $type->isInvalided() ? 'disabled' : '' }}>
                                            {{ $type->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" id="branchActive" wire:model="data.active">
                        <span>{{ __('general.pages.accounts.is_active') }}</span>
                    </label>
                </form>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-950/70">
                <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" data-bs-dismiss="modal">{{ __('general.pages.accounts.close') }}</button>
                <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" wire:click="save">{{ __('general.pages.accounts.save') }}</button>
            </div>
        </div>
    </div>
</div>
