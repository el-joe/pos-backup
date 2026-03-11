<div class="mx-auto max-w-6xl space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.roles.role_details') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ $data['roleName'] ?? __('general.pages.roles.enter_role_name') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.roles.permissions_list') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ collect($permissionsList ?? [])->flatten()->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 md:col-span-2">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.roles.role_name') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $current?->name ?? __('general.pages.roles.enter_role_name') }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.roles.role_details')" icon="fa fa-lock">
        <div class="space-y-6 p-5">
            <div>
                <label for="roleName" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-white">{{ __('general.pages.roles.role_name') }}</label>
                <input
                    type="text"
                    class="form-control"
                    id="roleName"
                    name="roleName"
                    wire:model.lazy="data.roleName"
                    placeholder="{{ __('general.pages.roles.enter_role_name') }}"
                >
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ __('general.pages.roles.permissions_list') }}</h3>
                </div>

                @foreach ($permissionsList as $key => $list)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/60">
                        <button
                            type="button"
                            wire:click.prevent="toggleCollapse('{{ $key }}')"
                            class="flex w-full items-center justify-between gap-4 px-4 py-4 text-left"
                        >
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ __(ucwords(str_replace('_', ' ', $key))) }}</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-300 text-slate-600 dark:border-slate-600 dark:text-slate-300">
                                <i class="fa {{ ($collapses[$key] ?? false) ? 'fa-minus' : 'fa-plus' }}"></i>
                            </span>
                        </button>

                        @if($collapses[$key] ?? false)
                            <div class="grid gap-3 border-t border-slate-200 px-4 py-4 md:grid-cols-2 xl:grid-cols-3 dark:border-slate-700">
                                @foreach ($list as $per)
                                    <label class="inline-flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900">
                                        <input
                                            class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                                            type="checkbox"
                                            wire:click="setPermission('{{ $key }}','{{ $per }}', $event.target.checked)"
                                            id="{{ $per }}"
                                            {{ ($permissions["$key.$per"] ?? false) ? 'checked' : '' }}
                                        >
                                        <span class="text-slate-700 dark:text-slate-200">{{ __(ucwords(str_replace(['_', $key], [' ', ''], $per))) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <x-slot:footer>
            <div class="flex flex-wrap justify-center gap-3">
                <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" wire:click="save">
                    <i class="fa fa-save"></i> {{ __('general.pages.roles.save_role') }}
                </button>
                <button type="reset" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    <i class="fa fa-times"></i> {{ __('general.pages.roles.cancel') }}
                </button>
            </div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
