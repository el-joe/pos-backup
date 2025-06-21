<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="mb-5 h4 col-sm-6">Purchase List <code>({{ $purchases->total() }})</code></div>
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
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Ref No</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td>{{($loop->index+1) * request('page',1)}}</td>
                                <td>{{$purchase->supplier?->name ?? '---'}}</td>
                                <td>{{$purchase->branch?->name ?? '---'}}</td>
                                <td>{{$purchase->ref_no}}</td>
                                <td>{{$purchase->status}}</td>
                                <td>{{carbon($purchase->order_date)->format('Y-m-d')}}</td>
                                <td>
                                    <a class="btn btn-dark btn-xs" href="{{ route('admin.purchases.addEditPurchase', $purchase->id) }}">
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
