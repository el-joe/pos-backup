@props(['titleKey' => '', 'entityKey' => '', 'icon' => 'fa-hard-hat'])

<div class="gemini-legacy-page">
    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div
            class="mx-auto max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700/60 dark:bg-slate-900">
            <div
                class="flex items-start gap-4 border-b border-slate-200 bg-gradient-to-br from-brand-500/10 to-transparent px-6 py-5 dark:border-slate-700/60">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-500/15 text-brand-600 dark:bg-brand-500/20 dark:text-brand-300">
                    <i class="fa {{ $icon }} fa-lg"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-slate-800 dark:text-slate-100">
                        {{ $titleKey ? __($titleKey) : '' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('general.pages.contracting.module_description') }}
                    </p>
                </div>
                <span
                    class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">
                    {{ __('general.pages.contracting.coming_soon') }}
                </span>
            </div>

            <div class="px-6 py-10 text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                    <i class="fa fa-tools fa-lg"></i>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-slate-700 dark:text-slate-200">
                    {{ __('general.pages.contracting.under_construction') }}
                </h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">
                    {{ $entityKey ? __($entityKey . '.title') : '' }}
                </p>
            </div>
        </div>
    </div>
</div>