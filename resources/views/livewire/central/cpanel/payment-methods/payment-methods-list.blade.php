<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payment Methods ({{ $paymentMethods->total() }})</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" href="{{ route('cpanel.payment-methods.create') }}">
                    <i class="fa fa-plus"></i> New Payment Method
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Provider</th>
                            <th>Manual</th>
                            <th>Fees</th>
                            <th>Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentMethods as $pm)
                            <tr>
                                <td>{{ $pm->id }}</td>
                                <td class="text-center" style="width: 72px;">
                                    @if($pm->icon_path)
                                        <img src="{{ asset('storage/' . $pm->icon_path) }}" alt="icon" style="height: 28px; width: 28px; object-fit: contain;">
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $pm->name }}</td>
                                <td><code>{{ $pm->provider }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $pm->manual ? 'info' : 'secondary' }}">
                                        {{ $pm->manual ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-muted">% {{ number_format((float) ($pm->fee_percentage ?? 0), 2) }}</div>
                                    <div class="small text-muted">+ {{ number_format((float) ($pm->fixed_fee ?? 0), 2) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pm->active ? 'success' : 'danger' }}">
                                        {{ $pm->active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                       href="{{ route('cpanel.payment-methods.edit', ['id' => $pm->id]) }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $pm->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $paymentMethods->links("pagination::bootstrap-5") }}
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
