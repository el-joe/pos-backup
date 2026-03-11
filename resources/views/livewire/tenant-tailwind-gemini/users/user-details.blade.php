<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.24em] text-brand-600 dark:text-brand-300">{{ __('general.pages.users.profile') }}</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $user->name }}</h1>
            </div>
            <span class="inline-flex rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $user->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                {{ $user->active ? __('general.pages.users.active') : __('general.pages.users.not_active') }}
            </span>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="$user->name . ' - ' . __('general.pages.users.profile')" icon="fa fa-user">
        <x-slot:head>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('activeTab', 'details')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'details' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-user"></i> {{ __('general.pages.users.details_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'accounts')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'accounts' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-credit-card"></i> {{ __('general.pages.users.accounts_tab') }}
                </button>
                <button type="button" wire:click="$set('activeTab', 'transactions')" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeTab === 'transactions' ? 'bg-brand-600 text-white shadow-sm' : 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                    <i class="fa fa-exchange"></i> {{ __('general.pages.users.transactions_tab') }}
                </button>
            </div>
        </x-slot:head>

        @if($activeTab === 'details')
            <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60"><p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.users.full_name') }}</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p></div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60"><p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.users.mobile') }}</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $user->phone }}</p></div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60"><p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.users.email') }}</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $user->email }}</p></div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60"><p class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.users.address') }}</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $user->address }}</p></div>
            </div>
        @elseif($activeTab === 'accounts')
            <div class="p-5">
                @livewire('admin.accounts.accounts-list', ['subPage' => true, 'filters' => ['model_type' => \App\Models\Tenant\User::class, 'model_id' => $user->id]])
            </div>
        @else
            <div class="grid gap-4 p-5 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/60">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.users.details_intro_title') }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('general.pages.users.details_intro_text') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('general.pages.users.details_intro_side') }}</p>
                </div>
            </div>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    @livewire('admin.accounts.add-edit-modal')
</div>
