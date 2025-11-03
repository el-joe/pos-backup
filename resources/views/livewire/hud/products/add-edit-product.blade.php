<div>
    <div class="white-box">
        <h3 class="box-title m-b-0">{{ $product?->id ? 'Edit Product - ' . $product->name : 'Add Product' }}</h3>
        <p class="text-muted m-b-30 font-13"></p>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group col-sm-4">
                    <label for="name">Product Name</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-product-hunt"></i></div>
                        <input type="text" class="form-control" id="name" placeholder="Product Name" wire:model="data.name">
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label for="sku">SKU</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-barcode"></i></div>
                        <input type="text" class="form-control" id="sku" placeholder="SKU" wire:model="data.sku">
                    </div>
                </div>
                {{-- code --}}
                <div class="form-group col-sm-4">
                    <label for="code">Code</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-key"></i></div>
                        <input type="text" class="form-control" id="code" placeholder="Product Code" wire:model="data.code">
                    </div>
                </div>
                {{-- description --}}
                <div class="form-group col-sm-12">
                    <label for="description">Description</label>
                    <textarea id="description" wire:model="data.description" class="form-control" rows="4" placeholder="Product Description"></textarea>
                </div>
                <div class="form-group col-sm-4">
                    <label for="branch_id">Branch</label>
                    <select id="branch_id" wire:model.change="data.branch_id" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- brand then category --}}
                <div class="form-group col-sm-4">
                    <label for="brand_id">Brand</label>
                    <select id="brand_id" wire:model.change="data.brand_id" class="form-control">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ ($this->data['brand_id']??false) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="category_id">Category</label>
                    <select id="category_id" wire:model.change="data.category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach ($categories as $parent)
                            {{recursiveChildrenForOptions($parent,'children','id','name',0,true,$this->data['category_id'] ?? null)}}
                        @endforeach
                    </select>
                </div>
                {{-- units --}}
                <div class="form-group col-sm-4">
                    <label for="unit_id">Unit</label>
                    <select id="unit_id" wire:model="data.unit_id" class="form-control">
                        <option value="">Select Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ ($this->data['unit_id']??false) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- weight --}}
                <div class="form-group col-sm-4">
                    <label for="weight">Weight</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-balance-scale"></i></div>
                        <input type="number" step="any" id="weight" wire:model="data.weight" class="form-control" placeholder="0.00">
                    </div>
                </div>
                {{-- alert quantity --}}
                <div class="form-group col-sm-4">
                    <label for="alert_quantity">Alert Quantity</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-exclamation-triangle"></i></div>
                        <input type="number" step="any" id="alert_quantity" wire:model="data.alert_qty" class="form-control" placeholder="0.00">
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="categoryActive" wire:model="data.active">
                        <span class="checkmark"></span> Is Active
                    </label>
                </div>

                {{-- taxable --}}
                <div class="form-group col-sm-4">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="taxable" wire:model="data.taxable">
                        <span class="checkmark"></span> Is Taxable
                    </label>
                </div>
                {{-- sell_price --}}
                {{-- <div class="form-group col-sm-4">
                    <label for="sell_price">Sell Price</label>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-tag"></i></div>
                        <input type="number" step="any" id="sell_price" wire:model="data.sell_price" class="form-control" placeholder="0.00">
                    </div>
                </div> --}}
                <div class="form-group col-sm-12">
                    <label for="image">Main Image</label>
                    <div>
                        <input type="file" id="image" wire:model="data.image" class="form-control">
                    </div>
                    @if (($data['image'] ?? false) && method_exists($data['image'],'temporaryUrl'))
                        <div class="m-t-10" style="position:relative; display:inline-block;">
                            <button type="button" class="btn btn-danger btn-xs" style="position:absolute; top:2px; left:2px; z-index:2; padding:2px 6px; border-radius:50%;">
                                <i class="fa fa-trash" wire:click="removeFile()"></i>
                            </button>
                            <img src="{{ $data['image']->temporaryUrl() }}" alt="Image Preview" style="width: 100px; height: 100px; display:block;">
                        </div>
                    @elseif($product?->image_path)
                        <div class="m-t-10" style="position:relative; display:inline-block;">
                            <button type="button" class="btn btn-danger btn-xs" style="position:absolute; top:2px; left:2px; z-index:2; padding:2px 6px; border-radius:50%;" wire:click="removeImage()">
                                <i class="fa fa-trash"></i>
                            </button>
                            <img src="{{ $product->image_path }}" alt="Current Image" style="width: 100px; height: 100px; display:block;">
                        </div>
                    @endif
                </div>
                {{-- gallery images --}}
                <div class="form-group col-sm-12">
                    <label for="gallery">Gallery Images</label>
                    <div>
                        <input type="file" id="gallery" wire:model="data.gallery" class="form-control" multiple>
                    </div>
                    @if (($data['gallery'] ?? false) && is_array($data['gallery']))
                        <div class="m-t-10">
                            @foreach ($data['gallery'] as $index => $file)
                                @if (method_exists($file, 'temporaryUrl'))
                                    <div class="m-t-10" style="position:relative; display:inline-block; margin-right:10px;">
                                        <button type="button" class="btn btn-danger btn-xs" style="position:absolute; top:2px; left:2px; z-index:2; padding:2px 6px; border-radius:50%;" wire:click="removeGalleryImage({{ $index }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <img src="{{ $file->temporaryUrl() }}" alt="Gallery Image Preview" style="width: 100px; height: 100px; display:block;">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @elseif($data['gallery_path'] ?? false)
                        <div class="m-t-10">
                            @foreach ($data['gallery_path'] as $index => $imagePath)
                                <div class="m-t-10" style="position:relative; display:inline-block; margin-right:10px;">
                                    <button type="button" class="btn btn-danger btn-xs" style="position:absolute; top:2px; left:2px; z-index:2; padding:2px 6px; border-radius:50%;" wire:click="removeExistingGalleryImage({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <img src="{{ $imagePath }}" alt="Current Gallery Image" style="width: 100px; height: 100px; display:block;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-sm-12">
                    <button type="button" wire:click="save" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
                    <button type="button" class="btn btn-inverse waves-effect waves-light">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
