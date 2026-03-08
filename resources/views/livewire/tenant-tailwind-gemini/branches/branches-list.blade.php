<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.branches.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.branches.search') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.branches.search') }} ..."
                       wire:model.live="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.branches.status') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.live="filters.active">
                    <option value="">{{ __('general.pages.branches.all') }}</option>
                    <option value="1">{{ __('general.pages.branches.active') }}</option>
                    <option value="0">{{ __('general.pages.branches.inactive') }}</option>
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end mt-2">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.branches.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.pages.branches.branches')"
        icon="fa fa-code-branch"
    >
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('branches.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.branches.export') }}
                    </button>
                @endadminCan

                @adminCan('branches.create')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                            data-bs-toggle="modal"
                            data-bs-target="#editBranchModal"
                            wire:click="$dispatch('branch-set-current', null)">
                        <i class="fa fa-plus"></i> {{ __('general.pages.branches.new_branch') }}
                    </button>
                @endadminCan
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.name') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.phone') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.email') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.address') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.tax') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.branches.active') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.branches.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($branches as $branch)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $branch->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $branch->name }}</td>
                        <td class="px-5 py-4">{{ $branch->phone ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $branch->email ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $branch->address ?? '-' }}</td>
                        <td class="px-5 py-4">
                            @if($branch->tax)
                                {{ $branch->tax?->name }} ({{ $branch->tax?->rate ?? 0 }}%)
                            @else
                                <span class="text-slate-400 dark:text-slate-500">{{ __('general.pages.branches.n_a') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $branch->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                {{ $branch->active ? __('general.pages.branches.active') : __('general.pages.branches.inactive') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @adminCan('branches.update')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editBranchModal"
                                            wire:click="$dispatch('branch-set-current', { id: {{ $branch->id }} })"
                                            title="{{ __('general.pages.branches.edit') }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endadminCan

                                @adminCan('branches.delete')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                                            wire:click="deleteAlert({{ $branch->id }})"
                                            title="{{ __('general.pages.branches.delete') }}">
                                        <i class="fa fa-times text-lg"></i>
                                    </button>
                                @endadminCan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            No data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($branches->hasPages())
            <x-slot:footer>
                {{ $branches->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>

</div>

@push('scripts')
    @livewire('admin.branches.branch-modal')
@endpush
