<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.units.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.units.search') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.units.search') }} ..."
                       wire:model.blur="filters.search">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.units.parent_unit') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.parent_id">
                    <option value="all" {{ ($filters['parent_id']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.units.all') }}</option>
                    <option value="0" {{ ($filters['parent_id']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.units.is_parent') }}</option>
                    @foreach($filterUnits as $parentUnit)
                        <option value="{{ $parentUnit->id }}" {{ ($filters['parent_id']??'all') == $parentUnit->id ? 'selected' : '' }}>{{ $parentUnit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.units.status') }}
                </label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        name="filters.active">
                    <option value="all" {{ ($filters['active']??'all') == 'all' ? 'selected' : '' }}>{{ __('general.pages.units.all') }}</option>
                    <option value="1" {{ ($filters['active']??'all') == '1' ? 'selected' : '' }}>{{ __('general.pages.units.active') }}</option>
                    <option value="0" {{ ($filters['active']??'all') == '0' ? 'selected' : '' }}>{{ __('general.pages.units.inactive') }}</option>
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-3 lg:justify-end mt-2">
                <button type="button"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 md:w-auto dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.units.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.pages.units.units')"
        icon="fa fa-balance-scale"
    >
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                @adminCan('units.export')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900"
                            wire:click="$set('export', 'excel')">
                        <i class="fa fa-file-excel"></i> {{ __('general.pages.units.export') }}
                    </button>
                @endadminCan

                @adminCan('units.create')
                    <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                            data-bs-toggle="modal"
                            data-bs-target="#editUnitModal"
                            wire:click="$dispatch('unit-set-current', null)">
                        <i class="fa fa-plus"></i> {{ __('general.pages.units.new_unit') }}
                    </button>
                @endadminCan
            </div>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                    <tr>
                        <th class="px-5 py-3 font-semibold">#</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.units.name') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.units.parent') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.units.count') }}</th>
                        <th class="px-5 py-3 font-semibold">{{ __('general.pages.units.status') }}</th>
                        <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.units.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                    @forelse ($units as $unit)
                        <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-5 py-4">{{ $unit->id }}</td>
                            <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $unit->name }}</td>
                            <td class="px-5 py-4">{{ $unit->parent ? $unit->parent->name : __('general.pages.units.n_a') }}</td>
                            <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $unit->count }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $unit->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                                    {{ $unit->active ? __('general.pages.units.active') : __('general.pages.units.inactive') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @adminCan('units.update')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:text-blue-400 dark:hover:bg-blue-500/10 dark:focus:ring-blue-400/50"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUnitModal"
                                                wire:click="$dispatch('unit-set-current', {id : '{{ $unit->id }}'})"
                                                title="{{ __('general.pages.units.edit') }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    @endadminCan

                                    @adminCan('units.delete')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                                wire:click="deleteAlert({{ $unit->id }})"
                                                title="{{ __('general.pages.units.delete') }}">
                                            <i class="fa fa-trash text-lg"></i>
                                        </button>
                                    @endadminCan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __('general.pages.units.no_data_found') ?? 'No units found.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($units, 'hasPages') && $units->hasPages())
            <x-slot:footer>
                {{ $units->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.units.unit-modal')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
