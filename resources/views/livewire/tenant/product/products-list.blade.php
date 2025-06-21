<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="mb-5 h4 col-sm-6">Products List <code>({{ $products->total() }})</code></div>
                <div class="col-sm-6">
                    <a class="btn btn-info float-right" href="{{ route('admin.products.addEditProduct','create') }}">
                        <i class="icon-circle-plus"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Unit</th>
                            <th>Brand</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td>
                                    <img src="{{$product->image_path}}" alt="{{$product->name}}" class="img-thumbnail" style="width: 50px;">
                                </td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->sku}}</td>
                                <td>{{$product->unit?->name ?? '---'}}</td>
                                <td>{{$product->brand?->name ?? '---'}}</td>
                                <td>
                                    <span class="badge badge-{{$product->active == 0 ? 'danger' : 'info'}}">
                                        {{$product->active ? 'YES' : 'NO'}}
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-dark btn-xs" href="{{ route('admin.products.addEditProduct', $product->id) }}">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs" wire:click="setCurrent({{$product->id}})" data-toggle="modal" data-target=".delete-modal">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal fade delete-modal" tabindex="-1" wire:ignore.self role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header justify-content-center">
                      <h5 class="modal-title">Are you Sure?!</h5>
                    </div>
                    <div class="modal-footer justify-content-around">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
