<div class="space-y-6">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.18),_transparent_38%),linear-gradient(135deg,_#0f172a,_#14532d_60%,_#059669)] px-6 py-7 text-white">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="text-xs font-bold uppercase tracking-[0.28em] text-white/60">Employee Profile</div>
                    <h1 class="mt-3 text-3xl font-black tracking-tight">{{ $employee->name }}</h1>
                    <p class="mt-2 text-sm text-white/75">Personal details, reporting line, and active contract snapshot.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-sm">
                    <div class="text-xs uppercase tracking-[0.2em] text-white/55">Employee Code</div>
                    <div class="mt-2 text-2xl font-black tracking-tight">{{ $employee->employee_code }}</div>
                </div>
            </div>
        </div>
    </section>

    <x-tenant-tailwind-gemini.table-card title="Profile Details" icon="fa-id-card">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Name</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->name }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Email</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->email }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Phone</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->phone ?? '-' }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Department</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->department?->name ?? '-' }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Designation</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->designation?->title ?? '-' }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Manager</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->manager?->name ?? '-' }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Hire Date</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ optional($employee->hire_date)->format('Y-m-d') ?? '-' }}</div></div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Status</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $employee->status?->label() ?? '-' }}</div></div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card title="Active Contract" icon="fa-file-signature">
        <div class="grid gap-4 p-5 md:grid-cols-3">
            @if($employee->activeContract)
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Basic Salary</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ numFormat($employee->activeContract->basic_salary) }}</div></div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Start Date</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ optional($employee->activeContract->start_date)->format('Y-m-d') ?? '-' }}</div></div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60"><div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">End Date</div><div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ optional($employee->activeContract->end_date)->format('Y-m-d') ?? '-' }}</div></div>
            @else
                <div class="md:col-span-3 rounded-3xl border border-dashed border-slate-300 px-5 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No active contract.</div>
            @endif
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
