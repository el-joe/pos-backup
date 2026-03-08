<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME', 'Mohaaseb') }} | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.tenant-tailwind-gemini.partials.styles')
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="relative isolate min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(37,99,235,0.22),_transparent_35%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)]">
        <div class="absolute inset-0 opacity-40" style="background-image:linear-gradient(rgba(148,163,184,0.12) 1px, transparent 1px),linear-gradient(90deg, rgba(148,163,184,0.12) 1px, transparent 1px);background-size:48px 48px;"></div>
        <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full gap-10 lg:grid-cols-[1.2fr_minmax(360px,460px)] lg:items-center">
                <div class="max-w-2xl space-y-6">
                    <div class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-blue-200">Tenant Tailwind Gemini</div>
                    <div class="space-y-4">
                        <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl">Professional operations workspace for modern retail teams.</h1>
                        <p class="max-w-xl text-base leading-7 text-slate-300 sm:text-lg">Access sales, purchasing, inventory, finance, and HR workflows from a cleaner Tailwind-based tenant experience.</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Focus</div>
                            <div class="mt-2 text-sm font-semibold text-white">Fast daily operations</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Design</div>
                            <div class="mt-2 text-sm font-semibold text-white">Tailwind-first interface</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">Mode</div>
                            <div class="mt-2 text-sm font-semibold text-white">Tenant admin access</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/10 bg-white/95 p-8 text-slate-900 shadow-2xl shadow-blue-950/40 backdrop-blur dark:bg-slate-900/95 dark:text-slate-100">
                    <div class="mb-8">
                        <div class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400">Sign In</div>
                        <h2 class="mt-3 text-3xl font-black tracking-tight">Admin login</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">For your protection, please verify your identity before entering the workspace.</p>
                    </div>

                    <form action="{{ panelAwareUrl(route('admin.postLogin')) }}" method="POST" class="space-y-5">
                        @csrf

                        @if(session('error'))
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div>
                            <label class="mb-2 block text-sm font-semibold">Email Address</label>
                            <input type="text" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950" autocomplete="email">
                            @error('email')
                                <div class="mt-2 text-sm text-rose-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold">Password</label>
                            <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950" autocomplete="current-password">
                            @error('password')
                                <div class="mt-2 text-sm text-rose-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30">Enter dashboard</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
