<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-cubes me-2"></i>Products</h5>
            <a href="{{ route('admin.products.add-edit','create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> New Product
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Branch</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Sell Price</th>
                            <th>Branch Stock</th>
                            <th>All Stock</th>
                            <th>Status</th>
                            <th class="text-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->branch?->name ?? 'All' }}</td>
                                <td>{{ $product->brand?->name }}</td>
                                <td>{{ $product->category?->name }}</td>
                                <td>{{ $product->stock_sell_price }}</td>
                                <td>{{ $product->branch_stock }}</td>
                                <td>{{ $product->all_stock }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->active ? 'success' : 'danger' }}">
                                        {{ $product->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.products.add-edit', $product->id) }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger me-1" data-bs-toggle="tooltip" title="Delete" wire:click="deleteAlert({{ $product->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
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

@push('styles')
@endpush
