<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.hrm.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="flex items-center justify-end">
            <button type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                    wire:click="resetFilters">
                <i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}
            </button>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.titles.hrm_contracts')"
        icon="fa fa-file-contract"
    >
        <x-slot:actions>
            @adminCan('hrm_master_data.create')
                <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900"
                        data-bs-toggle="modal"
                        data-bs-target="#editHrmContractModal"
                        wire:click="$dispatch('hrm-contract-set-employee', null)">
                    <i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.employee') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.basic_salary') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.start') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.end') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.active') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($contracts as $c)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $c->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">
                            {{ $c->employee?->name ?? $c->employee_id }}
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">
                            {{ numFormat($c->basic_salary) }}
                        </td>
                        <td class="px-5 py-4">{{ optional($c->start_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4">{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $c->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                {{ $c->is_active ? __('general.pages.hrm.yes') : __('general.pages.hrm.no') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.hrm.no_data_found') ?? 'No contracts found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($contracts, 'hasPages') && $contracts->hasPages())
            <x-slot:footer>
                {{ $contracts->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.contract-modal')
@endpush
