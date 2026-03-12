<div class="flex flex-col gap-6">
    <x-tenant-tailwind-gemini.filter-card
        :title="__('general.pages.purchase_requests.filters')"
        icon="fa fa-filter"
        :expanded="$collapseFilters"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchase_requests.request_no') }}</label>
                <input type="text" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-500" placeholder="{{ __('general.pages.purchase_requests.search_placeholder') }}" wire:model.blur="filters.request_number">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchase_requests.supplier') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.supplier_id">
                    <option value="">{{ __('general.pages.purchase_requests.all_suppliers') }}</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ ($filters['supplier_id'] ?? '') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('general.pages.purchase_requests.branch') }}</label>
                <select class="select2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-brand-500" name="filters.branch_id">
                    <option value="">{{ __('general.pages.purchase_requests.all_branches_option') }}</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($filters['branch_id'] ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end justify-start md:col-span-2 lg:col-span-3 lg:justify-end">
                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" wire:click="resetFilters">
                    <i class="fa fa-undo"></i> {{ __('general.pages.purchase_requests.reset') }}
                </button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.pages.purchase_requests.purchase_requests')" icon="fa-file-text-o">
        <x-slot:actions>
            <div class="flex flex-wrap items-center gap-2">
                <button class="inline-flex items-center gap-2 rounded-xl border border-emerald-500 bg-white px-3 py-1.5 text-sm font-medium text-emerald-600 transition-colors hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:focus:ring-offset-slate-900" wire:click="$set('export', 'excel')">
                    <i class="fa fa-file-excel"></i> {{ __('general.pages.purchase_requests.export') }}
                </button>
                <a class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-1 dark:focus:ring-offset-slate-900" href="{{ route('admin.purchase-requests.create') }}">
                    <i class="fa fa-plus"></i> {{ __('general.pages.purchase_requests.new_purchase_request') }}
                </a>
            </div>
        </x-slot:actions>

        <table class="w-full whitespace-nowrap text-left text-sm text-slate-600 dark:text-slate-400">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                <tr>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchase_requests.id') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchase_requests.request_no') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchase_requests.supplier') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchase_requests.branch') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchase_requests.status') }}</th>
                    <th class="px-5 py-3 font-semibold">{{ __('general.pages.purchase_requests.total') }}</th>
                    <th class="px-5 py-3 text-right font-semibold text-nowrap">{{ __('general.pages.purchase_requests.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 border-t border-slate-100 dark:divide-slate-800 dark:border-slate-800">
                @forelse ($requests as $request)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="px-5 py-4">{{ $request->id }}</td>
                        <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $request->request_number }}</td>
                        <td class="px-5 py-4">{{ $request->supplier?->name ?? __('general.messages.n_a') }}</td>
                        <td class="px-5 py-4">{{ $request->branch?->name }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-{{ $request->status?->colorClass() ?? 'secondary' }}">
                                {{ $request->status?->label() ?? (string) $request->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4">{{ currencyFormat($request->total_amount ?? 0, true) }}</td>
                        <td class="px-5 py-4 text-right">
                            <a class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" href="{{ route('admin.purchase-requests.details', $request->id) }}" title="{{ __('general.pages.purchase_requests.details') }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.purchase_requests.no_records') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            {{ $requests->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
