<div class="space-y-6">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.categories.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <x-slot:actions>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300"
                wire:click="$toggle('collapseFilters')"
            >
                <i class="fa fa-filter"></i>
                {{ __('general.pages.categories.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.categories.search_by_name') }}</label>
                <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" placeholder="{{ __('general.pages.categories.search') }} ..." wire:model.blur="filters.search">
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.categories.parent_category') }}</label>
                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="filters.parent_id">
                    <option value="all">{{ __('general.pages.categories.all') }}</option>
                    @foreach ($allCategories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['parent_id']??0) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.categories.status') }}</label>
                <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.categories.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.categories.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.categories.inactive') }}</option>
                </select>
            </div>

            <div class="flex items-end justify-start md:justify-end">
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" wire:click="resetFilters">
                    <i class="fa fa-undo"></i>
                    {{ __('general.pages.categories.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.categories.categories')" description="Organize product hierarchies, parent relationships, and icon metadata." icon="fa fa-folder-open">
        <x-slot:actions>
            @adminCan('categories.export')
                <button class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i>
                    {{ __('general.pages.categories.export') }}
                </button>
            @endadminCan
            @adminCan('categories.create')
                <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="$dispatch('category-set-current', null)">
                    <i class="fa fa-plus"></i>
                    {{ __('general.pages.categories.new_category') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4 font-semibold">#</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.categories.name') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.categories.parent_category') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.categories.icon') }}</th>
                    <th class="px-5 py-4 font-semibold">{{ __('general.pages.categories.status') }}</th>
                    <th class="px-5 py-4 text-end font-semibold">{{ __('general.pages.categories.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($categories as $category)
                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">#{{ $category->id }}</td>
                        <td class="px-5 py-4 text-slate-700 dark:text-slate-200">{{ $category->name }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $category->parent ? $category->parent->name : __('general.pages.categories.n_a') }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                            @if($category->icon)
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                    <i class="{{ $category->icon }}"></i>
                                    {{ $category->icon }}
                                </span>
                            @else
                                {{ __('general.pages.categories.n_a') }}
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $category->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }}">
                                {{ $category->active ? __('general.pages.categories.active') : __('general.pages.categories.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-end">
                            <div class="flex justify-end gap-2">
                                @adminCan('categories.update')
                                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:text-slate-300 dark:hover:border-brand-400/50 dark:hover:text-brand-300" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="$dispatch('category-set-current', {id : '{{ $category->id }}' })" title="{{ __('general.pages.categories.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endadminCan
                                @adminCan('categories.delete')
                                    <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-rose-200 text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10" wire:click="deleteAlert({{ $category->id }})" title="{{ __('general.pages.categories.delete') }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="mx-auto max-w-sm space-y-2">
                                <div class="text-base font-semibold text-slate-900 dark:text-white">No categories found.</div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Build your category tree here to keep products grouped and easier to discover.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($categories->hasPages())
            <x-slot:footer>
                <div class="flex justify-center">
                    {{ $categories->links('pagination::default5') }}
                </div>
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    @livewire('admin.categories.category-modal')
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
