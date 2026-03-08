<div class="space-y-6">
    <section class="rounded-[28px] border border-slate-200 bg-white px-6 py-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:px-8">
        <div class="max-w-3xl space-y-2">
            <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                <i class="fa fa-plus-circle"></i>
                New Entry
            </span>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('general.pages.depreciation_expenses.new_asset_entry') }}</h1>
            <p class="text-sm text-slate-600 dark:text-slate-300">Create a depreciation, repair, or lifespan extension entry tied to a fixed asset.</p>
        </div>
    </section>

    <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.branch') }}</label>
                    @if(admin()->branch_id == null)
                        <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.branch_id">
                            <option value="">{{ __('general.pages.depreciation_expenses.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($data['branch_id']??'') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300" value="{{ admin()->branch?->name }}" disabled>
                    @endif
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.fixed_asset') }}</label>
                    <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.fixed_asset_id">
                        <option value="">{{ __('general.pages.depreciation_expenses.select_asset') }}</option>
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}" {{ ($data['fixed_asset_id']??0) == $asset->id ? 'selected' : '' }}>{{ $asset->code }} - {{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.category') }}</label>
                    <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.expense_category_id">
                        <option value="">{{ __('general.pages.depreciation_expenses.select_category') }}</option>
                        @foreach ($expenseCategories as $cat)
                            <option value="{{ $cat->id }}" {{ ($data['expense_category_id']??0) == $cat->id ? 'selected' : '' }}>{{ $cat->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.entry_type') }}</label>
                    <select class="select2 mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" name="data.fixed_asset_entry_type">
                        <option value="depreciation" {{ ($data['fixed_asset_entry_type']??'depreciation') == 'depreciation' ? 'selected' : '' }}>{{ __('general.pages.depreciation_expenses.type_depreciation') }}</option>
                        <option value="repair_expense" {{ ($data['fixed_asset_entry_type']??'') == 'repair_expense' ? 'selected' : '' }}>{{ __('general.pages.depreciation_expenses.type_repair_expense') }}</option>
                        <option value="lifespan_extension" {{ ($data['fixed_asset_entry_type']??'') == 'lifespan_extension' ? 'selected' : '' }}>{{ __('general.pages.depreciation_expenses.type_lifespan_extension') }}</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.amount') }}</label>
                    <input type="number" step="0.01" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.amount">
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.date') }}</label>
                    <input type="date" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.expense_date">
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.tax_percentage') }}</label>
                    <input type="number" step="0.01" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.tax_percentage">
                </div>

                @if(($data['fixed_asset_entry_type'] ?? 'depreciation') === 'lifespan_extension')
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.fixed_assets.added_useful_life_months') }}</label>
                        <input type="number" step="1" min="0" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" wire:model="data.added_useful_life_months">
                    </div>
                @endif

                <div class="md:col-span-2 xl:col-span-3">
                    <label class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ __('general.pages.depreciation_expenses.note') }}</label>
                    <textarea class="mt-2 min-h-28 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-400 dark:focus:bg-slate-900" rows="4" wire:model="data.note"></textarea>
                </div>
            </div>

            <aside class="rounded-[28px] border border-slate-200 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-950/70">
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Entry Guidance</h2>
                    <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Use this form for capitalized asset adjustments only. Choose the entry type carefully so reporting and depreciation schedules remain accurate.</p>
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-brand-500"></span><span>Depreciation records periodic cost allocation.</span></li>
                        <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-500"></span><span>Repair expense captures maintenance-related cost.</span></li>
                        <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span><span>Lifespan extension adjusts useful life for future calculations.</span></li>
                    </ul>
                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500" wire:click="saveExpense">
                        <i class="fa fa-save"></i>
                        {{ __('general.pages.depreciation_expenses.save') }}
                    </button>
                </div>
            </aside>
        </div>
    </section>
</div>

@push('scripts')
@include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
