<div class="container-fluid">
    <div class="row g-4">

        <!-- Branches -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fa fa-building text-primary me-2"></i> {{ __('general.pages.imports.branches') }}
                        </h5>
                        <small class="text-muted">{{ __('general.pages.imports.import_branches_data') }}</small>
                    </div>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importBranches">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="branchesFile" accept=".xlsx,.xls,.csv">
                                @error('branchesFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • email, phone, address, website ({{ __('general.pages.imports.optional') }})<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('branches')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button type="submit" class="btn btn-primary ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    {{ __('general.pages.imports.importing') }}
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-box text-success me-2"></i> {{ __('general.pages.imports.products') }}
                    </h5>
                    <small class="text-muted">{{ __('general.pages.imports.import_products_data') }}</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importProducts">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="productsFile" accept=".xlsx,.xls,.csv">
                                @error('productsFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name, description, sku, code</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • <strong>unit, brand, category</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • <strong>alert_qty</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • branch, weight ({{ __('general.pages.imports.optional') }})<br>
                                    • <strong>active, taxable</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('products')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button class="btn btn-success ms-auto">
                                <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-tags text-info me-2"></i> {{ __('general.pages.imports.categories') }}
                    </h5>
                    <small class="text-muted">{{ __('general.pages.imports.import_categories_data') }}</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importCategories">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="categoriesFile" accept=".xlsx,.xls,.csv">
                                @error('categoriesFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • parent, icon ({{ __('general.pages.imports.optional') }})<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('categories')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button type="submit" class="btn btn-info ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    {{ __('general.pages.imports.importing') }}
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-certificate text-warning me-2"></i> {{ __('general.pages.imports.brands') }}
                    </h5>
                    <small class="text-muted">{{ __('general.pages.imports.import_brands_data') }}</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importBrands">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="brandsFile" accept=".xlsx,.xls,.csv">
                                @error('brandsFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('brands')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button type="submit" class="btn btn-warning ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    {{ __('general.pages.imports.importing') }}
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-ruler text-danger me-2"></i> {{ __('general.pages.imports.units') }}
                    </h5>
                    <small class="text-muted">{{ __('general.pages.imports.import_units_data') }}</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importUnits">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="unitsFile" accept=".xlsx,.xls,.csv">
                                @error('unitsFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • parent, count ({{ __('general.pages.imports.optional') }})<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('units')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button type="submit" class="btn btn-danger ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    {{ __('general.pages.imports.importing') }}
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-users text-info me-2"></i> {{ __('general.pages.imports.customers') }}
                    </h5>
                    <small class="text-muted">{{ __('general.pages.imports.import_customers_data') }}</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importCustomers">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="customersFile" accept=".xlsx,.xls,.csv">
                                @error('customersFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • email, phone, address ({{ __('general.pages.imports.optional') }})<br>
                                    • sales_threshold ({{ __('general.pages.imports.optional') }})<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('customers')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button type="submit" class="btn btn-info ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    {{ __('general.pages.imports.importing') }}
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

        <!-- Suppliers -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-truck text-secondary me-2"></i> {{ __('general.pages.imports.suppliers') }}
                    </h5>
                    <small class="text-muted">{{ __('general.pages.imports.import_suppliers_data') }}</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importSuppliers">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.select_excel_file') }}</label>
                                <input type="file" class="form-control" wire:model="suppliersFile" accept=".xlsx,.xls,.csv">
                                @error('suppliersFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('general.pages.imports.required_columns') }}</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> ({{ __('general.pages.imports.required') }})<br>
                                    • email, phone, address ({{ __('general.pages.imports.optional') }})<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('suppliers')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> {{ __('general.pages.imports.template') }}
                            </button>

                            <button type="submit" class="btn btn-secondary ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> {{ __('general.pages.imports.import') }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    {{ __('general.pages.imports.importing') }}
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
        </div>

    </div>
</div>
