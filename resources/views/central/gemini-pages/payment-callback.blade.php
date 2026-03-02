@extends('layouts.central.gemini.layout')

@section('content')
<main class="relative flex items-center justify-center min-h-[75vh] py-16 bg-gray-50 dark:bg-gray-900 overflow-hidden">
    <!-- Subtle Gradient Background Elements -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 w-full max-w-3xl -translate-x-1/2 h-full opacity-40 dark:opacity-20 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] {{ $type == 'success' ? 'from-emerald-300 via-transparent' : 'from-rose-300 via-transparent' }} to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg px-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            @if($type == 'success')
                <!-- Success State -->
                <div class="p-8 sm:p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-28 w-28 rounded-full bg-emerald-50 dark:bg-emerald-900/30 mb-8 border-[6px] border-emerald-100 dark:border-emerald-800/40">
                        <svg class="h-14 w-14 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">
                        {{ __('website.payment.success_title') ?? 'Payment Successful!' }}
                    </h2>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                        {{ $message ?? __('website.payment.success_message') ?? 'Thank you for your payment. Your transaction has been completed successfully.' }}
                    </p>

                    <div class="w-full bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-5 mb-8 border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('website.payment.status') ?? 'Status' }}</span>
                            <span class="font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                {{ __('website.payment.confirmed') ?? 'Confirmed' }}
                            </span>
                        </div>
                    </div>

                    <a href="/" class="block w-full py-4 text-sm font-semibold text-white transition-all bg-emerald-500 rounded-xl hover:bg-emerald-600 focus:ring-4 focus:ring-emerald-200 dark:focus:ring-emerald-900 shadow-lg shadow-emerald-500/30 active:scale-[0.98]">
                        {{ __('website.payment.back_to_home') ?? 'Return to Dashboard' }}
                    </a>
                </div>
            @else
                <!-- Failed State -->
                <div class="p-8 sm:p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-28 w-28 rounded-full bg-rose-50 dark:bg-rose-900/30 mb-8 border-[6px] border-rose-100 dark:border-rose-800/40">
                        <svg class="h-14 w-14 text-rose-500 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">
                        {{ __('website.payment.failed_title') ?? 'Payment Failed' }}
                    </h2>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                        {{ $message ?? __('website.payment.failed_message') ?? 'We could not process your payment at this time. Please check your details and try again.' }}
                    </p>

                    <div class="w-full bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-5 mb-8 border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('website.payment.status') ?? 'Status' }}</span>
                            <span class="font-medium text-rose-600 dark:text-rose-400 flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                                </span>
                                {{ __('website.payment.declined') ?? 'Declined' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" class="flex items-center justify-center py-4 text-sm font-semibold text-gray-700 transition-all bg-white border border-gray-200 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:text-gray-200 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 active:scale-[0.98]">
                            {{ __('website.payment.cancel') ?? 'Cancel' }}
                        </a>
                        <a href="{{ route('central.checkout') ?? '/checkout' }}" class="flex items-center justify-center py-4 text-sm font-semibold text-white transition-all bg-rose-500 rounded-xl hover:bg-rose-600 focus:ring-4 focus:ring-rose-200 dark:focus:ring-rose-900 shadow-lg shadow-rose-500/30 active:scale-[0.98]">
                            {{ __('website.payment.try_again') ?? 'Try Again' }}
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Support Footer -->
        <div class="mt-8 text-center text-sm text-gray-400 dark:text-gray-500">
            {{ __('website.payment.having_trouble') ?? 'Having trouble?' }}
            <a href="/contact" class="font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors underline decoration-gray-300 dark:decoration-gray-600 underline-offset-4">
                {{ __('website.payment.contact_support') ?? 'Contact Support' }}
            </a>
        </div>
    </div>
</main>
@endsection

