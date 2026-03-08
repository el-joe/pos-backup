<div class="space-y-6">
    <section class="rounded-[28px] border border-slate-200 bg-white px-6 py-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:px-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl space-y-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                    <i class="fa fa-wallet"></i>
                    Accounts
                </span>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('general.titles.accounts') }}</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300">Control account naming, codes, assignment scope, and linked payment methods from a single ledger-friendly table.</p>
            </div>

            <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" data-bs-toggle="modal" data-bs-target="#editAccountModal" wire:click="setCurrent(null)">
                <i class="fa fa-plus"></i>
                {{ __('general.pages.accounts.new_account') }}
            </button>
        </div>
    </section>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.accounts')" description="Active and inactive cash, bank, and operational accounts." icon="fa fa-book">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.id') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.name') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.code') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.type') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.branch') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.payment_method') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.accounts.status') }}</th>
                    <th class="px-5 py-4 text-center font-semibold">{{ __('general.pages.accounts.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($accounts as $account)
                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">#{{ $account->id }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $account->name }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $account->code }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold text-white bg-{{ $account->type->color() }}">{{ $account->type->label() }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $account->branch?->name ?? __('general.pages.accounts.all') }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $account->paymentMethod?->name ?? __('general.pages.accounts.placeholder_line') }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $account->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                                {{ $account->active ? __('general.pages.accounts.active') : __('general.pages.accounts.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-center gap-2">
                                <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300" data-bs-toggle="modal" data-bs-target="#editAccountModal" wire:click="setCurrent({{ $account->id }})" title="{{ __('general.pages.accounts.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $account->id }})" title="{{ __('general.pages.accounts.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <div class="mx-auto max-w-sm space-y-2">
                                <div class="text-base font-semibold text-slate-900 dark:text-white">No accounts found.</div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Create an account to connect transactions, payment methods, and branch finance activity.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($accounts->hasPages())
            <x-slot:footer>
                <div class="flex justify-center">
                    {{ $accounts->links('pagination::default5') }}
                </div>
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    @if(!$subPage)
        @livewire('admin.accounts.add-edit-modal',[
            'current' => $current,
            'filters' => $filters,
            'subPage' => $subPage
        ])
    @endif
</div>
