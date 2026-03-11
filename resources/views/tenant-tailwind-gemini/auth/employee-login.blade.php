<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME', 'Mohaaseb') }} | Employee Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.tenant-tailwind-gemini.partials.styles')
</head>
<body class="min-h-screen bg-stone-950 text-stone-100">
    <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_right,_rgba(14,165,233,0.18),_transparent_32%),radial-gradient(circle_at_bottom_left,_rgba(37,99,235,0.18),_transparent_28%),linear-gradient(180deg,_#0c0a09_0%,_#111827_100%)]">
        <div class="absolute inset-0 opacity-50" style="background-image:linear-gradient(rgba(148,163,184,0.08) 1px, transparent 1px),linear-gradient(90deg, rgba(148,163,184,0.08) 1px, transparent 1px);background-size:42px 42px;"></div>
        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full gap-10 lg:grid-cols-[1fr_minmax(360px,440px)] lg:items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200">Employee Portal</div>
                    <h1 class="max-w-2xl text-4xl font-black tracking-tight text-white sm:text-5xl">A cleaner HR workspace for attendance, claims, leaves, and payslips.</h1>
                    <p class="max-w-xl text-base leading-7 text-slate-300 sm:text-lg">Employees get the same Tailwind-based experience, optimized for daily HR interactions and self-service workflows.</p>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/95 p-8 text-slate-900 shadow-2xl shadow-cyan-950/30 backdrop-blur dark:bg-slate-900/95 dark:text-slate-100">
                    <div class="mb-8">
                        <div class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-600 dark:text-cyan-400">Employee Sign In</div>
                        <h2 class="mt-3 text-3xl font-black tracking-tight">Welcome back</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Sign in to review your HR data, requests, and attendance records.</p>
                    </div>

                    <form action="{{ panelAwareUrl(route('employee.postLogin')) }}" method="POST" class="space-y-5">
                        @csrf

                        @if(session('error'))
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div>
                            <label class="mb-2 block text-sm font-semibold">Email Address</label>
                            <input type="text" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 dark:border-slate-700 dark:bg-slate-950" autocomplete="email">
                            @error('email')
                                <div class="mt-2 text-sm text-rose-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold">Password</label>
                            <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 dark:border-slate-700 dark:bg-slate-950" autocomplete="current-password">
                            @error('password')
                                <div class="mt-2 text-sm text-rose-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/30">Enter employee workspace</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
