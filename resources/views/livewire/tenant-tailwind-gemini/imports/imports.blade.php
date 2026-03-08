<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                <i class="fa fa-building text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.branches') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_branches_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importBranches" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="branchesFile" accept=".xlsx,.xls,.csv">
                        @error('branchesFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• email, phone, address, website ({{ __('general.pages.imports.optional') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('branches')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-500 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importBranches">
                        <span wire:loading.remove wire:target="importBranches"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importBranches"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                <i class="fa fa-box text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.products') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_products_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importProducts" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="productsFile" accept=".xlsx,.xls,.csv">
                        @error('productsFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name, description, sku, code</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">unit, brand, category</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">alert_qty</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• branch, weight ({{ __('general.pages.imports.optional') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active, taxable</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('products')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importProducts">
                        <span wire:loading.remove wire:target="importProducts"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importProducts"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                <i class="fa fa-tags text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.categories') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_categories_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importCategories" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="categoriesFile" accept=".xlsx,.xls,.csv">
                        @error('categoriesFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• parent, icon ({{ __('general.pages.imports.optional') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('categories')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-500 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importCategories">
                        <span wire:loading.remove wire:target="importCategories"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importCategories"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                <i class="fa fa-certificate text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.brands') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_brands_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importBrands" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="brandsFile" accept=".xlsx,.xls,.csv">
                        @error('brandsFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('brands')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importBrands">
                        <span wire:loading.remove wire:target="importBrands"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importBrands"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                <i class="fa fa-ruler text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.units') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_units_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importUnits" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="unitsFile" accept=".xlsx,.xls,.csv">
                        @error('unitsFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• parent, count ({{ __('general.pages.imports.optional') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('units')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-500 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importUnits">
                        <span wire:loading.remove wire:target="importUnits"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importUnits"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-teal-50 text-teal-600 dark:bg-teal-500/10 dark:text-teal-400">
                <i class="fa fa-users text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.customers') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_customers_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importCustomers" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="customersFile" accept=".xlsx,.xls,.csv">
                        @error('customersFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• email, phone, address ({{ __('general.pages.imports.optional') }})</li>
                                <li>• sales_threshold ({{ __('general.pages.imports.optional') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('customers')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-teal-500 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importCustomers">
                        <span wire:loading.remove wire:target="importCustomers"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importCustomers"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
            <span class="mt-0.5 flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-400">
                <i class="fa fa-truck text-lg"></i>
            </span>
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('general.pages.imports.suppliers') }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('general.pages.imports.import_suppliers_data') }}</p>
            </div>
        </div>

        <div class="flex-1 px-6 py-5">
            <form wire:submit.prevent="importSuppliers" class="flex h-full flex-col">
                <div class="grid flex-1 grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.select_excel_file') }}
                        </label>
                        <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:outline-none dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-300 dark:hover:file:bg-slate-700" wire:model="suppliersFile" accept=".xlsx,.xls,.csv">
                        @error('suppliersFile')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ __('general.pages.imports.required_columns') }}
                        </label>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-600 dark:border-slate-700/50 dark:bg-slate-800/50 dark:text-slate-400">
                            <ul class="space-y-1">
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">name</strong> ({{ __('general.pages.imports.required') }})</li>
                                <li>• email, phone, address ({{ __('general.pages.imports.optional') }})</li>
                                <li>• <strong class="font-semibold text-slate-900 dark:text-white">active</strong> (YES/NO)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="downloadTemplate('suppliers')" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:ring-slate-700 dark:focus:ring-offset-slate-900">
                        <i class="fa fa-download"></i> {{ __('general.pages.imports.template') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:ring-offset-1 disabled:opacity-70 dark:focus:ring-offset-slate-900" wire:loading.attr="disabled" wire:target="importSuppliers">
                        <span wire:loading.remove wire:target="importSuppliers"><i class="fa fa-upload"></i> {{ __('general.pages.imports.import') }}</span>
                        <span wire:loading wire:target="importSuppliers"><i class="fa fa-circle-notch fa-spin"></i> {{ __('general.pages.imports.importing') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
