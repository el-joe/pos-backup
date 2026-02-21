<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tenants List</h5>
            <a class="btn btn-primary btn-sm" href="{{ route('cpanel.customers.create') }}">
                <i class="fa fa-plus"></i> Create Tenant
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>phone</th>
                            <th>Country</th>
                            <th>domain</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $tenant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tenant->id }}</td>
                            <td>{{ $tenant->email ?? '-' }}</td>
                            <td>{{ $tenant->phone ?? '-' }}</td>
                            <td>{{ $tenant->country()?->name ?? '-' }}</td>
                            <td>{{ $tenant->domains->first()->domain ?? '-'}}
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('cpanel.customers.details', ['id' => $tenant->id]) }}">
                                    Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $tenants->links("pagination::bootstrap-5") }}
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
