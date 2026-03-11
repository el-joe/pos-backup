<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.hrm.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.status') }}
                </label>
                <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500"
                        wire:model.blur="filters.status">
                    <option value="all">{{ __('general.pages.hrm.all') }}</option>
                    @foreach ([App\Enums\LeaveRequestStatusEnum::PENDING, App\Enums\LeaveRequestStatusEnum::APPROVED, App\Enums\LeaveRequestStatusEnum::REJECTED] as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ __('general.pages.hrm.search') }}
                </label>
                <input type="text"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500"
                       placeholder="{{ __('general.pages.hrm.search_reason_placeholder') }}"
                       wire:model.blur="filters.search">
            </div>

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

            <div class="col-span-1 flex items-end justify-start md:col-span-3 md:justify-end mt-2">
                <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900"
                        wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card
        :title="__('general.titles.hrm_leave_requests')"
        icon="fa fa-calendar-minus-o"
    >
        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.employee') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.type') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.start') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.end') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.days') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.hrm.status') }}</th>
                    <th class="px-5 py-3 text-right font-semibold">{{ __('general.pages.hrm.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse($requests as $r)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $r->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">
                            {{ $r->employee?->name ?? $r->employee_id }}
                        </td>
                        <td class="px-5 py-4">{{ $r->leaveType?->name ?? $r->leave_type_id }}</td>
                        <td class="px-5 py-4">{{ optional($r->start_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4">{{ optional($r->end_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">
                            {{ numFormat($r->days) }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $statusVal = $r->status?->value ?? $r->status;
                                $statusColor = match($statusVal) {
                                    App\Enums\LeaveRequestStatusEnum::APPROVED->value => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    App\Enums\LeaveRequestStatusEnum::REJECTED->value => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                                    App\Enums\LeaveRequestStatusEnum::PENDING->value => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusColor }}">
                                {{ $r->status?->label() ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(($r->status?->value ?? $r->status) === App\Enums\LeaveRequestStatusEnum::PENDING->value)
                                    @adminCan('hrm_leaves.approve')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 dark:text-emerald-400 dark:hover:bg-emerald-500/10 dark:focus:ring-emerald-400/50"
                                                wire:click="approveAlert({{ $r->id }})"
                                                title="{{ __('general.pages.hrm.approve') }}">
                                            <i class="fa fa-check text-lg"></i>
                                        </button>
                                    @endadminCan

                                    @adminCan('hrm_leaves.reject')
                                        <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500/50 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:focus:ring-rose-400/50"
                                                wire:click="rejectAlert({{ $r->id }})"
                                                title="{{ __('general.pages.hrm.reject') }}">
                                            <i class="fa fa-times text-lg"></i>
                                        </button>
                                    @endadminCan
                                @else
                                    <span class="inline-flex h-8 items-center justify-center px-2 text-slate-400 dark:text-slate-500">
                                        -
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('general.pages.hrm.no_data_found') ?? 'No leave requests found.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($requests, 'hasPages') && $requests->hasPages())
            <x-slot:footer>
                {{ $requests->links() }}
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
