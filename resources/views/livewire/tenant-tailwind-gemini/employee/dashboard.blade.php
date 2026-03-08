<div class="space-y-6">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.16),_transparent_42%),linear-gradient(135deg,_#0f172a,_#1e3a8a_65%,_#2563eb)] px-6 py-7 text-white">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="text-xs font-bold uppercase tracking-[0.28em] text-white/60">Employee Portal</div>
                    <h1 class="mt-3 text-3xl font-black tracking-tight">Welcome, {{ $employee->name }}</h1>
                    <p class="mt-2 max-w-2xl text-sm text-white/75">Overview of your role, activity, and most recent attendance updates.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-3xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <div class="text-xs uppercase tracking-[0.2em] text-white/55">Department</div>
                        <div class="mt-2 text-lg font-bold">{{ $employee->department?->name ?? '-' }}</div>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <div class="text-xs uppercase tracking-[0.2em] text-white/55">Designation</div>
                        <div class="mt-2 text-lg font-bold">{{ $employee->designation?->title ?? '-' }}</div>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <div class="text-xs uppercase tracking-[0.2em] text-white/55">Status</div>
                        <div class="mt-2 text-lg font-bold">{{ $employee->status?->label() ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 dark:text-slate-400">Leave Requests</div>
            <div class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $leaveRequestsCount }}</div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 dark:text-slate-400">Expense Claims</div>
            <div class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $expenseClaimsCount }}</div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 dark:text-slate-400">Payslips</div>
            <div class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $payslipsCount }}</div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card title="Latest Attendance" icon="fa-calendar-check">
        <div class="grid gap-4 p-5 md:grid-cols-3">
            @if($latestAttendanceLog)
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Date</div>
                    <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ $latestAttendanceLog->sheet?->date?->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Clock In</div>
                    <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ optional($latestAttendanceLog->clock_in_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/60">
                    <div class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Clock Out</div>
                    <div class="mt-3 text-lg font-bold text-slate-900 dark:text-white">{{ optional($latestAttendanceLog->clock_out_at)->format('Y-m-d H:i') ?? '-' }}</div>
                </div>
            @else
                <div class="md:col-span-3 rounded-3xl border border-dashed border-slate-300 px-5 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No attendance records yet.</div>
            @endif
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
