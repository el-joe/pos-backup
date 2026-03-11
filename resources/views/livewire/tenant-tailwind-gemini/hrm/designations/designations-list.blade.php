<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.titles.hrm_designations')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div class="md:col-span-1">
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.search') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.hrm.search_designation_placeholder') }}"
                       wire:model.blur="filters.search">
            </div>

            <div class="flex items-end justify-start md:col-span-2 md:justify-end mt-2">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.titles.hrm_designations')"
        icon="fa fa-id-badge"
    >
        <x-slot:actions>
            @adminCan('hrm_master_data.create')
                <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmDesignationModal"
                        wire:click="$dispatch('hrm-designation-set-current', null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.title') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.department') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.hrm.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($designations as $d)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $d->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $d->title }}</td>
                        <td class="px-5 py-4">{{ $d->department?->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('hrm_master_data.update')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus:ring-blue-400/50"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editHrmDesignationModal"
                                            wire:click="$dispatch('hrm-designation-set-current', { id: {{ $d->id }} })">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endadminCan

                                @adminCan('hrm_master_data.delete')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                            wire:click="deleteAlert({{ $d->id }})">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.hrm.no_data_found') ?? 'No designations found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($designations, 'hasPages') && $designations->hasPages())
            <x-slot:footer>
                {{ $designations->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.designation-modal')
@endpush
