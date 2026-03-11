<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.brands.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.brands.search') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.brands.search') }} ..."
                       wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.brands.status') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.brands.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.brands.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.brands.inactive') }}</option>
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end mt-2">
                <button type="button"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 md:w-auto dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.brands.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.pages.brands.brands')"
        description="Maintain the catalog of supported brands and their publishing status."
        icon="fa fa-tags"
    >
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('brands.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.brands.export') }}
                    </button>
                @endadminCan

                @adminCan('brands.create')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                            data-bs-toggle="modal"
                            data-bs-target="#editBrandModal"
                            wire:click="$dispatch('brand-set-current', { id : null })">
                        <i class="fa fa-plus"></i> {{ __('general.pages.brands.new_brand') }}
                    </button>
                @endadminCan
            </div>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                    <tr>
                        <th class="px-5 py-3 font-semibold">#</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.brands.name') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.brands.status') }}</th>
                        <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.brands.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                    @forelse ($brands as $brand)
                        <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-5 py-4">{{ $brand->id }}</td>
                            <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $brand->name }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $brand->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                    {{ $brand->active ? __('general.pages.brands.active') : __('general.pages.brands.inactive') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @adminCan('brands.update')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus:ring-blue-400/50"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editBrandModal"
                                                wire:click="$dispatch('brand-set-current', { id : '{{ $brand->id }}'})"
                                                title="{{ __('general.pages.brands.edit') }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan

                                    @adminCan('brands.delete')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                                wire:click="deleteAlert({{ $brand->id }})"
                                                title="{{ __('general.pages.brands.delete') }}">
                                            <i class="fa fa-times text-lg"></i>
                                        </button>
                                    @endadminCan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <div class="mx-auto max-w-sm space-y-2">
                                    <div class="text-base font-medium text-slate-900 dark:text-white">No brands found.</div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Create a brand to improve catalog structure and filter products more cleanly.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($brands, 'hasPages') && $brands->hasPages())
            <x-slot:footer>
                {{ $brands->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

    @livewire('admin.brands.brand-modal')
</div>

@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
