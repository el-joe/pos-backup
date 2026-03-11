<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.hrm.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.employee') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.blur="filters.employee_id">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_code }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.titles.hrm_payroll_runs') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.blur="filters.payroll_run_id">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach($runs as $run)
                        <option value="{{ $run->id }}">#{{ $run->id }} - {{ $run->month }}/{{ $run->year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-1 flex items-end justify-start md:col-span-2 lg:col-span-1 lg:justify-end mt-2">
                <button type="button"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 md:w-auto dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.titles.hrm_payslips')"
        icon="fa fa-file-text-o"
    >
        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.run') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.employee') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.gross') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.net') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($slips as $s)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $s->id }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                #{{ $s->run?->id ?? $s->payroll_run_id }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">
                            {{ $s->employee?->name ?? $s->employee_id }}
                        </td>
                        <td class="px-5 py-4 font-medium text-slate-700 dark:text-slate-300">
                            {{ numFormat($s->gross_pay) }}
                        </td>
                        <td class="px-5 py-4 font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ numFormat($s->net_pay) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.hrm.no_data_found') ?? 'No payslips found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($slips, 'hasPages') && $slips->hasPages())
            <x-slot:footer>
                {{ $slips->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
