<div class="space-y-4">
    <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
        <form wire:submit.prevent="applyFilter" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500 dark:text-slate-400">Direction</label>
                <select wire:model.live="direction" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                    <option value="received">Received (Under Collection)</option>
                    <option value="issued">Issued</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500 dark:text-slate-400">Branch</label>
                <select wire:model.live="branch_id" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" wire:click="resetFilters" class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                Reset
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Aging total</p>
            <p class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">{{ currencyFormat($grandTotal, true) }}</p>
        </div>
        <div class="rounded-xl border p-4 {{ abs($grandTotal - $controlAccountBalance) < 0.01 ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-900/20' : 'border-rose-200 bg-rose-50 dark:border-rose-800 dark:bg-rose-900/20' }}">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $controlAccount->name }} balance</p>
            <p class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">
                {{ currencyFormat($controlAccountBalance, true) }}
                @if(abs($grandTotal - $controlAccountBalance) >= 0.01)
                    <span class="text-sm font-medium text-rose-600 dark:text-rose-400">— mismatch</span>
                @else
                    <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">— reconciled</span>
                @endif
            </p>
        </div>
    </div>

    @php
        $labels = [
            'overdue' => 'Overdue',
            'due_0_7' => 'Due in 0-7 days',
            'due_8_30' => 'Due in 8-30 days',
            'due_31_60' => 'Due in 31-60 days',
            'due_60_plus' => 'Due in 60+ days',
            'no_due_date' => 'No due date',
        ];
    @endphp

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/40">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Bucket</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Count</th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Total Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($buckets as $key => $items)
                    <tr class="{{ $key === 'overdue' && $items->count() ? 'bg-rose-50 dark:bg-rose-900/10' : '' }}">
                        <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $labels[$key] }}</td>
                        <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $items->count() }}</td>
                        <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ currencyFormat($bucketTotals[$key], true) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @foreach($buckets as $key => $items)
        @if($items->count())
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                <div class="border-b border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">
                    {{ $labels[$key] }}
                </div>
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/40">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Check #</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Bank</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Due Date</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Amount</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Customer / Supplier</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Branch</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($items as $check)
                            <tr>
                                <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $check->check_number }}</td>
                                <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $check->bank_name }}</td>
                                <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $check->due_date?->format('Y-m-d') }}</td>
                                <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ currencyFormat($check->amount, true) }}</td>
                                <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $check->direction === 'received' ? ($check->customer?->name ?? '-') : ($check->supplier?->name ?? '-') }}</td>
                                <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-200">{{ $check->branch?->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
</div>
