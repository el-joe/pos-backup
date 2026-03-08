<div class="space-y-6">
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium uppercase tracking-[0.24em] text-brand-600 dark:text-brand-300">{{ __('general.pages.products.code') }}</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ $product?->id ? __('general.titles.edit_product') . ' - ' . $product->name : __('general.titles.add_product') }}
                    </h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        {{ __('general.pages.products.sku') }}: {{ $data['sku'] ?? $product?->sku ?? '---' }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-brand-700 dark:bg-brand-500/10 dark:text-brand-300">
                        {{ __('general.pages.products.branch') }}: {{ admin()->branch?->name ?? __('general.pages.products.select_branch') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('general.pages.products.is_active') }}</p>
            <div class="mt-3 flex items-center gap-3">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <i class="fa fa-check"></i>
                </span>
                <div>
                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ ($data['active'] ?? false) ? 'ON' : 'OFF' }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('general.pages.products.is_taxable') }}: {{ ($data['taxable'] ?? false) ? 'ON' : 'OFF' }}</p>
                </div>
            </div>
        </div>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="$product?->id ? __('general.titles.edit_product') . ' - ' . $product->name : __('general.titles.add_product')" icon="fa fa-box">
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <label for="name" class="form-label">{{ __('general.pages.products.name') }} *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                    <input type="text" id="name" class="form-control" placeholder="{{ __('general.pages.products.enter_product_name') }}" wire:model="data.name">
                </div>
            </div>

            <div>
                <label for="sku" class="form-label">{{ __('general.pages.products.sku') }} *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                    <input type="text" id="sku" class="form-control" placeholder="{{ __('general.pages.products.enter_sku') }}" wire:model="data.sku">
                </div>
            </div>

            <div>
                <label for="code" class="form-label">{{ __('general.pages.products.code') }} *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input type="text" id="code" class="form-control" placeholder="{{ __('general.pages.products.enter_code') }}" wire:model="data.code">
                </div>
            </div>

            <div class="md:col-span-2 xl:col-span-3">
                <label for="description" class="form-label">{{ __('general.pages.products.description') }} *</label>
                <textarea id="description" wire:model="data.description" class="form-control" rows="4" placeholder="{{ __('general.pages.products.enter_description') }}"></textarea>
            </div>

            <div>
                <label for="branch_id" class="form-label">{{ __('general.pages.products.branch') }}</label>
                <div class="flex gap-2">
                    @if(admin()->branch_id == null)
                        <select id="branch_id" name="data.branch_id" class="form-select select2">
                            <option value="">{{ __('general.pages.products.select_branch') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ ($data['branch_id'] ?? false) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ admin()->branch->name }}" readonly disabled>
                    @endif
                    <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBranchModal" wire:click="$dispatch('branch-set-current', {id : null})">+</button>
                </div>
            </div>

            <div>
                <label for="brand_id" class="form-label">{{ __('general.pages.products.brand') }} *</label>
                <div class="flex gap-2">
                    <select id="brand_id" name="data.brand_id" class="form-select select2">
                        <option value="">{{ __('general.pages.products.select_brand') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ ($data['brand_id'] ?? false) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editBrandModal" wire:click="$dispatch('brand-set-current', {id : null})">+</button>
                </div>
            </div>

            <div>
                <label for="category_id" class="form-label">{{ __('general.pages.products.category') }} *</label>
                <div class="flex gap-2">
                    <select id="category_id" name="data.category_id" class="form-select select2">
                        <option value="">{{ __('general.pages.products.select_category') }}</option>
                        @foreach ($categories as $parent)
                            {{ recursiveChildrenForOptions($parent, 'children', 'id', 'name', 0, true, $data['category_id'] ?? ($this->data['category_id'] ?? null)) }}
                        @endforeach
                    </select>
                    <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editCategoryModal" wire:click="$dispatch('category-set-current', {id : null})">+</button>
                </div>
            </div>

            <div>
                <label for="unit_id" class="form-label">{{ __('general.pages.products.unit') }} *</label>
                <div class="flex gap-2">
                    <select id="unit_id" name="data.unit_id" class="form-select select2">
                        <option value="">{{ __('general.pages.products.select_unit') }}</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ ($data['unit_id'] ?? false) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editUnitModal" wire:click="$dispatch('unit-set-current', {id : null})">+</button>
                </div>
            </div>

            <div>
                <label for="weight" class="form-label">{{ __('general.pages.products.weight') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-balance-scale"></i></span>
                    <input type="number" step="any" id="weight" wire:model="data.weight" class="form-control" placeholder="0.00">
                </div>
            </div>

            <div>
                <label for="alert_quantity" class="form-label">{{ __('general.pages.products.alert_quantity') }} *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-exclamation-triangle"></i></span>
                    <input type="number" step="any" id="alert_quantity" wire:model="data.alert_qty" class="form-control" placeholder="0.00">
                </div>
            </div>

            <div class="flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/60">
                <div class="form-check mb-0">
                    <input type="checkbox" id="categoryActive" wire:model="data.active" class="form-check-input">
                    <label for="categoryActive" class="form-check-label">{{ __('general.pages.products.is_active') }}</label>
                </div>
            </div>

            <div class="flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/60">
                <div class="form-check mb-0">
                    <input type="checkbox" id="taxable" wire:model="data.taxable" class="form-check-input">
                    <label for="taxable" class="form-check-label">{{ __('general.pages.products.is_taxable') }}</label>
                </div>
            </div>
        </div>
    </x-tenant-tailwind-gemini.table-card>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(22rem,0.85fr)]">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.products.main_image')" icon="fa fa-image">
            <div class="space-y-5 p-5">
                <div>
                    <label for="image" class="form-label">{{ __('general.pages.products.main_image') }}</label>
                    <input type="file" id="image" wire:model="data.image" class="form-control">
                </div>

                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    @if (($data['image'] ?? false) && method_exists($data['image'], 'temporaryUrl'))
                        <div class="relative inline-block">
                            <button type="button" class="absolute -left-3 -top-3 inline-flex h-8 w-8 items-center justify-center rounded-full bg-rose-600 text-white shadow-sm" wire:click="removeImage()">
                                <i class="fa fa-trash text-xs"></i>
                            </button>
                            <img src="{{ $data['image']->temporaryUrl() }}" alt="Image Preview" class="h-32 w-32 rounded-2xl border border-slate-200 object-cover dark:border-slate-700">
                        </div>
                    @elseif($product?->image_path)
                        <div class="relative inline-block">
                            <button type="button" class="absolute -left-3 -top-3 inline-flex h-8 w-8 items-center justify-center rounded-full bg-rose-600 text-white shadow-sm" wire:click="removeImage()">
                                <i class="fa fa-trash text-xs"></i>
                            </button>
                            <img src="{{ $product->image_path }}" alt="Current Image" class="h-32 w-32 rounded-2xl border border-slate-200 object-cover dark:border-slate-700">
                        </div>
                    @else
                        <div class="flex h-32 items-center justify-center rounded-2xl border border-slate-200 bg-white text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                            {{ __('general.pages.products.main_image') }}
                        </div>
                    @endif
                </div>
            </div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.products.gallery_images')" icon="fa fa-images">
            <div class="space-y-5 p-5">
                <div>
                    <label for="gallery" class="form-label">{{ __('general.pages.products.gallery_images') }}</label>
                    <input type="file" id="gallery" wire:model="data.gallery" class="form-control" multiple>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @if (($data['gallery'] ?? false) && is_array($data['gallery']))
                        @foreach ($data['gallery'] as $index => $file)
                            @if (method_exists($file, 'temporaryUrl'))
                                <div class="relative">
                                    <button type="button" class="absolute -left-2 -top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-rose-600 text-white shadow-sm" wire:click="removeGalleryImage({{ $index }})">
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                    <img src="{{ $file->temporaryUrl() }}" alt="Gallery Image" class="h-24 w-full rounded-2xl border border-slate-200 object-cover dark:border-slate-700">
                                </div>
                            @endif
                        @endforeach
                    @elseif($data['gallery_path'] ?? false)
                        @foreach ($data['gallery_path'] as $index => $imagePath)
                            <div class="relative">
                                <button type="button" class="absolute -left-2 -top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-rose-600 text-white shadow-sm" wire:click="removeExistingGalleryImage({{ $index }})">
                                    <i class="fa fa-trash text-xs"></i>
                                </button>
                                <img src="{{ $imagePath }}" alt="Current Gallery Image" class="h-24 w-full rounded-2xl border border-slate-200 object-cover dark:border-slate-700">
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full flex h-24 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-400">
                            {{ __('general.pages.products.gallery_images') }}
                        </div>
                    @endif
                </div>
            </div>

            <x-slot:footer>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-start">
                    <button type="button" wire:click="save" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        <i class="fa fa-check me-2"></i> {{ __('general.pages.products.submit') }}
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                        <i class="fa fa-times me-2"></i> {{ __('general.pages.products.cancel') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>

@push('scripts')
    @livewire('admin.branches.branch-modal')
    @livewire('admin.brands.brand-modal')
    @livewire('admin.categories.category-modal')
    @livewire('admin.units.unit-modal')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
@endpush
