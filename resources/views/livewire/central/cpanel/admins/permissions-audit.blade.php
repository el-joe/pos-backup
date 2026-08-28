<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Permissions Audit</h5>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary" wire:click="seedAll" wire:loading.attr="disabled"
                    wire:confirm="Are you sure? This will resync permissions for ALL tenants.">
                    <i class="fa fa-sync"></i> Seed All Tenants
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tenant</th>
                            <th>Permissions Count</th>
                            <th>Missing</th>
                            <th>Last Seeded</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row['tenant_name'] }}</td>
                                <td>{{ $row['permissions_count'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $row['missing_count'] > 0 ? 'warning' : 'success' }}">
                                        {{ $row['missing_count'] }}
                                    </span>
                                </td>
                                <td>{{ $row['last_seeded'] ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $row['missing_count'] > 0 ? 'danger' : 'success' }} me-2">
                                        {{ $row['missing_count'] > 0 ? 'Out of sync' : 'Up to date' }}
                                    </span>
                                    <button class="btn btn-sm btn-primary"
                                        wire:click="seedSingle('{{ $row['tenant_id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:confirm="Are you sure? This will resync permissions for this tenant.">
                                        <i class="fa fa-sync"></i> Seed
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No tenants found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
