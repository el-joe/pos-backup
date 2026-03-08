@php
    $canCheckIn = !$todayOpenLog;
    $canCheckOut = (bool) $todayOpenLog;
    $todayClockIn = $todayOpenLog?->clock_in_at ?? $todayLog?->clock_in_at;
    $todayClockOut = $todayOpenLog?->clock_out_at ?? $todayLog?->clock_out_at;
    $todayStatus = $todayOpenLog ? 'Checked in' : ($todayClockOut ? 'Completed' : 'Not started');
@endphp

<div class="space-y-6">
    <section class="relative overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-r from-emerald-500/15 via-brand-500/10 to-cyan-500/15"></div>
        <div class="absolute right-0 top-0 h-40 w-40 -translate-y-12 translate-x-12 rounded-full bg-emerald-500/10 blur-3xl dark:bg-emerald-400/10"></div>

        <div class="relative px-6 py-6 lg:px-8 lg:py-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl space-y-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
                        <i class="fa fa-user-clock"></i>
                        Attendance
                    </span>

                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">My Attendance</h1>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Track your workday in real time, confirm today’s punches, and review the latest attendance records from one place.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 dark:disabled:bg-slate-700 dark:disabled:text-slate-400" wire:click="checkIn" @disabled(!$canCheckIn)>
                        <i class="fa fa-sign-in-alt"></i>
                        Check In
                    </button>

                    <button class="inline-flex items-center gap-2 rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 dark:border-rose-500/30 dark:bg-slate-950 dark:text-rose-300 dark:hover:bg-rose-500/10 dark:disabled:border-slate-700 dark:disabled:bg-slate-900 dark:disabled:text-slate-500" wire:click="checkOut" @disabled(!$canCheckOut)>
                        <i class="fa fa-sign-out-alt"></i>
                        Check Out
                    </button>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white/90 p-5 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Today</div>
                    <div class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ now()->format('l, d M Y') }}</div>
                    <div class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $todayStatus }}</div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white/90 p-5 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Clock In</div>
                    <div class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ optional($todayClockIn)->format('H:i') ?? '-' }}</div>
                    <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">Latest recorded start time for today.</div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white/90 p-5 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Clock Out</div>
                    <div class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">{{ optional($todayClockOut)->format('H:i') ?? '-' }}</div>
                    <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">End time appears after your checkout is confirmed.</div>
                </div>
            </div>
        </div>
    </section>

    <x-tenant-tailwind-gemini.table-card title="Attendance Timeline" description="Recent attendance entries, punch times, and recorded sources." icon="fa fa-calendar-check">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-800 rtl:text-right">
            <thead class="bg-slate-50/80 text-xs uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-4 font-semibold">ID</th>
                    <th class="px-5 py-4 font-semibold">Date</th>
                    <th class="px-5 py-4 font-semibold">Clock In</th>
                    <th class="px-5 py-4 font-semibold">Clock Out</th>
                    <th class="px-5 py-4 font-semibold">Status</th>
                    <th class="px-5 py-4 font-semibold">Source</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($logs as $log)
                    <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">#{{ $log->id }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $log->sheet?->date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ optional($log->clock_in_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ optional($log->clock_out_at)->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $log->status?->label() ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $log->source?->label() ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="mx-auto max-w-sm space-y-2">
                                <div class="text-base font-semibold text-slate-900 dark:text-white">No attendance records yet.</div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Your recent check-ins and check-outs will appear here once activity is recorded.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($logs->hasPages())
            <x-slot:footer>
                <div class="flex justify-center">
                    {{ $logs->links('pagination::default5') }}
                </div>
            </x-slot:footer>
        @endif
    </x-tenant-tailwind-gemini.table-card>
</div>
