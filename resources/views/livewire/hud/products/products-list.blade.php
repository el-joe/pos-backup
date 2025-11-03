<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Products</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit product --}}
                <a  class="btn btn-primary" href="{{ route('admin.products.add-edit','create') }}">
                    <i class="fa fa-plus"></i> New Product
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
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
                                <span class="badge badge-{{ $product->active ? 'success' : 'danger' }}">
                                    {{ $product->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="#"  data-toggle="tooltip" data-original-title="Edit">
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
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
@endpush
