<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">
                {{ $product?->id ? 'Edit Product - ' . $product->name : 'Add Product' }}
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <!-- Product Name -->
                <div class="col-md-4">
                    <label for="name" class="form-label">Product Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-product-hunt"></i></span>
                        <input type="text" id="name" class="form-control" placeholder="Product Name" wire:model="data.name">
                    </div>
                </div>

                <!-- SKU -->
                <div class="col-md-4">
                    <label for="sku" class="form-label">SKU</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                        <input type="text" id="sku" class="form-control" placeholder="SKU" wire:model="data.sku">
                    </div>
                </div>

                <!-- Code -->
                <div class="col-md-4">
                    <label for="code" class="form-label">Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                        <input type="text" id="code" class="form-control" placeholder="Product Code" wire:model="data.code">
                    </div>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" wire:model="data.description" class="form-control" rows="4" placeholder="Product Description"></textarea>
                </div>

                <!-- Branch -->
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select id="branch_id" wire:model.change="data.branch_id" class="form-select">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div class="col-md-4">
                    <label for="brand_id" class="form-label">Brand</label>
                    <select id="brand_id" wire:model.change="data.brand_id" class="form-select">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ ($this->data['brand_id']??false) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div class="col-md-4">
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" wire:model.change="data.category_id" class="form-select">
                        <option value="">Select Category</option>
                        @foreach ($categories as $parent)
                            {{ recursiveChildrenForOptions($parent,'children','id','name',0,true,$this->data['category_id'] ?? null) }}
                        @endforeach
                    </select>
                </div>

                <!-- Unit -->
                <div class="col-md-4">
                    <label for="unit_id" class="form-label">Unit</label>
                    <select id="unit_id" wire:model="data.unit_id" class="form-select">
                        <option value="">Select Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ ($this->data['unit_id']??false) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Weight -->
                <div class="col-md-4">
                    <label for="weight" class="form-label">Weight</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-balance-scale"></i></span>
                        <input type="number" step="any" id="weight" wire:model="data.weight" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <!-- Alert Quantity -->
                <div class="col-md-4">
                    <label for="alert_quantity" class="form-label">Alert Quantity</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-exclamation-triangle"></i></span>
                        <input type="number" step="any" id="alert_quantity" wire:model="data.alert_qty" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <!-- Active Checkbox -->
                <div class="col-md-4 d-flex align-items-center mt-3">
                    <div class="form-check">
                        <input type="checkbox" id="categoryActive" wire:model="data.active" class="form-check-input">
                        <label for="categoryActive" class="form-check-label">Is Active</label>
                    </div>
                </div>

                <!-- Taxable Checkbox -->
                <div class="col-md-4 d-flex align-items-center mt-3">
                    <div class="form-check">
                        <input type="checkbox" id="taxable" wire:model="data.taxable" class="form-check-input">
                        <label for="taxable" class="form-check-label">Is Taxable</label>
                    </div>
                </div>

                <!-- Main Image -->
                <div class="col-12">
                    <label for="image" class="form-label">Main Image</label>
                    <input type="file" id="image" wire:model="data.image" class="form-control">

                    @if (($data['image'] ?? false) && method_exists($data['image'],'temporaryUrl'))
                        <div class="mt-3 position-relative d-inline-block">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 start-0 translate-middle p-1 rounded-circle" wire:click="removeFile()">
                                <i class="fa fa-trash"></i>
                            </button>
                            <img src="{{ $data['image']->temporaryUrl() }}" alt="Image Preview" class="rounded border" style="width:100px;height:100px;">
                        </div>
                    @elseif($product?->image_path)
                        <div class="mt-3 position-relative d-inline-block">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 start-0 translate-middle p-1 rounded-circle" wire:click="removeImage()">
                                <i class="fa fa-trash"></i>
                            </button>
                            <img src="{{ $product->image_path }}" alt="Current Image" class="rounded border" style="width:100px;height:100px;">
                        </div>
                    @endif
                </div>

                <!-- Gallery Images -->
                <div class="col-12">
                    <label for="gallery" class="form-label">Gallery Images</label>
                    <input type="file" id="gallery" wire:model="data.gallery" class="form-control" multiple>

                    @if (($data['gallery'] ?? false) && is_array($data['gallery']))
                        <div class="mt-3">
                            @foreach ($data['gallery'] as $index => $file)
                                @if (method_exists($file, 'temporaryUrl'))
                                    <div class="position-relative d-inline-block me-2 mb-2">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 start-0 translate-middle p-1 rounded-circle" wire:click="removeGalleryImage({{ $index }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <img src="{{ $file->temporaryUrl() }}" alt="Gallery Image" class="rounded border" style="width:100px;height:100px;">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @elseif($data['gallery_path'] ?? false)
                        <div class="mt-3">
                            @foreach ($data['gallery_path'] as $index => $imagePath)
                                <div class="position-relative d-inline-block me-2 mb-2">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 start-0 translate-middle p-1 rounded-circle" wire:click="removeExistingGalleryImage({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <img src="{{ $imagePath }}" alt="Current Gallery Image" class="rounded border" style="width:100px;height:100px;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Buttons -->
                <div class="col-12 d-flex justify-content-start mt-3">
                    <button type="button" wire:click="save" class="btn btn-success me-2">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
