<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.titles.hrm_attendance_sheets') }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">#{{ $sheet->id }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.hrm.date') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ optional($sheet->date)->format('Y-m-d') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.hrm.department') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $sheet->department?->name ?? '-' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.hrm.status') }}</p>
            <p class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $sheet->status?->label() ?? '-' }}</p>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_attendance_sheets') . ' #' . $sheet->id" icon="fa fa-calendar-check-o">
        <x-slot:actions>
            <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" href="{{ route('admin.hrm.attendance-sheets.list') }}">
                <i class="fa fa-arrow-left"></i> {{ __('general.pages.hrm.back') }}
            </a>
        </x-slot:actions>

        <div class="p-5">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('general.pages.hrm.id') }}</th>
                            <th>{{ __('general.pages.hrm.employee') }}</th>
                            <th>{{ __('general.pages.hrm.clock_in') }}</th>
                            <th>{{ __('general.pages.hrm.clock_out') }}</th>
                            <th>{{ __('general.pages.hrm.status') }}</th>
                            <th>{{ __('general.pages.hrm.source') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->employee?->name ?? $l->employee_id }}</td>
                                <td>{{ optional($l->clock_in_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ optional($l->clock_out_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                <td>{{ $l->status?->label() ?? '-' }}</td>
                                <td>{{ $l->source?->label() ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-center">
                {{ $logs->links('pagination::default5') }}
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
