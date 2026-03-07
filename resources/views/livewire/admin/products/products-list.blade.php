<div class="col-sm-12">
    <x-admin.table-card title="Products" icon="fa-cubes">
        <x-slot:actions>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.products.add-edit', ['create']) }}">
                <i class="fa fa-plus"></i> New Product
            </a>
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>#</th>
                <th>Sku</th>
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
        </x-slot:head>

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
                    <span class="badge badge-{{ $product->active ? 'success' : 'danger' }}">
                        {{ $product->active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="text-nowrap">
                    <a href="#" data-toggle="tooltip" data-original-title="Edit">
                        <i class="fa fa-pencil text-primary m-r-10"></i>
                    </a>
                    <a href="{{ route('admin.products.add-edit', $product->id) }}" data-toggle="tooltip" data-original-title="Close" wire:click="deleteAlert({{ $product->id }})">
                        <i class="fa fa-close text-danger"></i>
                    </a>
                    <a href="#" data-toggle="tooltip" data-original-title="View">
                        <i class="fa fa-eye text-info"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        <x-slot:footer>
            {{ $products->links() }}
        </x-slot:footer>
    </x-admin.table-card>
</div>

@push('styles')
@endpush
