<div class="container-fluid">
    <div class="row g-4">

        <!-- Branches -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fa fa-building text-primary me-2"></i> Branches
                        </h5>
                        <small class="text-muted">Import branches data</small>
                    </div>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importBranches">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="branchesFile" accept=".xlsx,.xls,.csv">
                                @error('branchesFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> (required)<br>
                                    • email, phone, address, website (optional)<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('branches')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button type="submit" class="btn btn-primary ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> Import
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Importing...
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
                        <i class="fa fa-box text-success me-2"></i> Products
                    </h5>
                    <small class="text-muted">Import products data</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importProducts">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="productsFile" accept=".xlsx,.xls,.csv">
                                @error('productsFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name, description, sku, code</strong> (required)<br>
                                    • <strong>unit, brand, category</strong> (required)<br>
                                    • <strong>alert_qty</strong> (required)<br>
                                    • branch, weight (optional)<br>
                                    • <strong>active, taxable</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('products')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button class="btn btn-success ms-auto">
                                <i class="fa fa-upload me-1"></i> Import
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
                        <i class="fa fa-tags text-info me-2"></i> Categories
                    </h5>
                    <small class="text-muted">Import categories data</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importCategories">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="categoriesFile" accept=".xlsx,.xls,.csv">
                                @error('categoriesFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> (required)<br>
                                    • parent, icon (optional)<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('categories')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button type="submit" class="btn btn-info ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> Import
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Importing...
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
                        <i class="fa fa-certificate text-warning me-2"></i> Brands
                    </h5>
                    <small class="text-muted">Import brands data</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importBrands">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="brandsFile" accept=".xlsx,.xls,.csv">
                                @error('brandsFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> (required)<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('brands')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button type="submit" class="btn btn-warning ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> Import
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Importing...
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
                        <i class="fa fa-ruler text-danger me-2"></i> Units
                    </h5>
                    <small class="text-muted">Import units data</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importUnits">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="unitsFile" accept=".xlsx,.xls,.csv">
                                @error('unitsFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> (required)<br>
                                    • parent, count (optional)<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('units')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button type="submit" class="btn btn-danger ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> Import
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Importing...
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
                        <i class="fa fa-users text-info me-2"></i> Customers
                    </h5>
                    <small class="text-muted">Import customers data</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importCustomers">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="customersFile" accept=".xlsx,.xls,.csv">
                                @error('customersFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> (required)<br>
                                    • email, phone, address (optional)<br>
                                    • sales_threshold (optional)<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('customers')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button type="submit" class="btn btn-info ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> Import
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Importing...
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
                        <i class="fa fa-truck text-secondary me-2"></i> Suppliers
                    </h5>
                    <small class="text-muted">Import suppliers data</small>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="importSuppliers">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Excel File</label>
                                <input type="file" class="form-control" wire:model="suppliersFile" accept=".xlsx,.xls,.csv">
                                @error('suppliersFile')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Required Columns</label>
                                <div class="border rounded p-2 small text-muted">
                                    • <strong>name</strong> (required)<br>
                                    • email, phone, address (optional)<br>
                                    • <strong>active</strong> (YES/NO)
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" wire:click="downloadTemplate('suppliers')" class="btn btn-outline-secondary">
                                <i class="fa fa-download me-1"></i> Template
                            </button>

                            <button type="submit" class="btn btn-secondary ms-auto" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-upload me-1"></i> Import
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Importing...
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
