<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="mb-5 h4 col-sm-6">Sales List <code>({{ $sales->total() }})</code></div>
                <div class="col-sm-6">
                    <a class="btn btn-info float-right" href="{{ route('admin.purchases.addEditPurchase','create') }}">
                        <i class="icon-circle-plus"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th>Ref No</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{($loop->index+1) * request('page',1)}}</td>
                                <td>{{$sale->customer?->name ?? '---'}}</td>
                                <td>{{$sale->branch?->name ?? '---'}}</td>
                                <td>{{$sale->ref_no}}</td>
                                <td>{{carbon($sale->order_date)->format('Y-m-d')}}</td>
                                <td>
                                    <a class="btn btn-dark btn-xs" href="{{ route('admin.sales.addEditSale', $sale->id) }}">
                                        <i class="icon-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
