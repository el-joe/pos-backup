<div class="space-y-4">
    @php
        $total_cogs = collect($report ?? [])->sum('cogs_amount');
    @endphp

    <div class="grid gap-4 md:grid-cols-2">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-brand-500/15 to-brand-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.reports.inventory.cogs.title') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ count($report) }}</div>
            </div>
        </div>
        <div class="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm dark:border-emerald-500/20 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-emerald-500/15 to-emerald-500/5 p-5">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.reports.common.total') }}</div>
                <div class="mt-3 text-3xl font-black tracking-tight text-emerald-700 dark:text-emerald-300">{{ currencyFormat($total_cogs, true) }}</div>
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.inventory.cogs.title')" icon="fa-shopping-cart" :render-table="false" class="mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('general.pages.reports.inventory.cogs.branch') }}</th>
                        <th class="text-end">{{ __('general.pages.reports.inventory.cogs.cogs_amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                        <tr>
                            <td class="font-semibold">{{ $row->branch_name }}</td>
                            <td class="text-end">{{ currencyFormat($row->cogs_amount, true) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.reports.inventory.cogs.no_data') }}</td>
                        </tr>
                    @endforelse
                    @if(count($report))
                        <tr class="fw-semibold">
                            <td>{{ __('general.pages.reports.common.total') }}</td>
                            <td class="text-end">{{ currencyFormat($total_cogs, true) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-tenant-tailwind-gemini.table-card>
</div>
