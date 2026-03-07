<div class="container-fluid">
    <x-admin.filter-card title="Filter Options" icon="fa-filter">
        <form wire:submit.prevent="applyFilter" class="row">
            <div class="col-md-3 form-group">
                <label for="from_date">From Date</label>
                <input type="date" id="from_date" wire:model.defer="from_date" class="form-control input-sm">
            </div>
            <div class="col-md-3 form-group">
                <label for="to_date">To Date</label>
                <input type="date" id="to_date" wire:model.defer="to_date" class="form-control input-sm">
            </div>
            <div class="col-md-3 form-group">
                <label for="admin_id">Admin</label>
                <select id="admin_id" wire:model.defer="admin_id" class="form-control input-sm">
                    <option value="">All</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }} #{{ $admin->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="action">Action</label>
                <select id="action" wire:model.defer="action" class="form-control input-sm">
                    <option value="">All</option>
                    @foreach(

                        \App\Enums\AuditLogActionEnum::cases() as $actionEnum)
                        <option value="{{ $actionEnum->value }}">{{ ucwords(str_replace('_', ' ', $actionEnum->value)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group" style="margin-top:25px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-ok-circle"></i> Apply</button>
                <button type="button" wire:click="resetFilters" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
            </div>
        </form>
    </x-admin.filter-card>

    <x-admin.table-card title="Audit Report" icon="fa-history" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" style="margin-bottom:0;">
                <thead class="active">
                    <tr>
                        <th style="width: 80px">#</th>
                        <th style="width: 180px">Date / Time</th>
                        <th style="width: 150px">Admin</th>
                        <th style="width: 200px">Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                        <tr>
                            <td class="text-center">{{ $audit->id }}</td>
                            <td>
                                <small>
                                    {{ dateTimeFormat($audit->created_at, true, false) }}<br>
                                    <span class="text-muted">{{ dateTimeFormat($audit->created_at, false, true) }}</span>
                                </small>
                            </td>
                            <td>
                                @if($audit->admin)
                                    {{ $audit->admin->name }} #{{ $audit->admin->id }}
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="label label-info">{{ ucwords(str_replace('_', ' ', $audit->action->value)) }}</span>
                            </td>
                            <td>
                                @if($audit->description && isset($audit->description['key']))
                                    {{ __($audit->description['key'], $audit->description['params'] ?? []) }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($audits->hasPages())
            <div style="padding: 15px; border-top: 1px solid #eee;">
                {{ $audits->links() }}
            </div>
        @endif
    </x-admin.table-card>
</div>
