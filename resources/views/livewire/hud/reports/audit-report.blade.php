<div class="container-fluid">
    <!-- Filter Options -->
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa fa-filter me-2"></i>
                <strong>{{ __('general.pages.reports.common.filter_options') }}</strong>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="applyFilter" class="row g-3">
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">{{ __('general.pages.reports.common.from_date') }}</label>
                        <input type="date" id="from_date" wire:model.defer="from_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">{{ __('general.pages.reports.common.to_date') }}</label>
                        <input type="date" id="to_date" wire:model.defer="to_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="admin_id" class="form-label">{{ __('general.pages.reports.common.admin') }}</label>
                        <select id="admin_id" wire:model.defer="admin_id" class="form-select form-select-sm">
                            <option value="">{{ __('general.pages.reports.common.all') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }} #{{ $admin->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="action" class="form-label">{{ __('general.pages.reports.audit_report.action') }}</label>
                        <select id="action" wire:model.defer="action" class="form-select form-select-sm">
                            <option value="">{{ __('general.pages.reports.common.all') }}</option>
                            @foreach(\App\Enums\AuditLogActionEnum::cases() as $actionEnum)
                                <option value="{{ $actionEnum->value }}">{{ ucwords(str_replace('_', ' ', $actionEnum->value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="fa fa-check-circle"></i> {{ __('general.pages.reports.common.apply') }}
                        </button>
                        <button type="button" wire:click="resetFilters" class="btn btn-secondary btn-sm">
                            <i class="fa fa-refresh"></i> {{ __('general.pages.reports.common.reset') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fa fa-history me-2"></i>
                    <h5 class="mb-0">{{ __('general.pages.reports.audit_report.title') }}</h5>
                </div>
                <small class="text-white-50">{{ __('general.pages.reports.audit_report.total') }}: {{ $audits->total() }}</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px">#</th>
                                <th style="width: 180px">{{ __('general.pages.reports.audit_report.date_time') }}</th>
                                <th style="width: 150px">{{ __('general.pages.reports.audit_report.admin') }}</th>
                                <th style="width: 200px">{{ __('general.pages.reports.audit_report.action') }}</th>
                                <th>{{ __('general.pages.reports.audit_report.description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($audits as $audit)
                                <tr>
                                    <td class="text-center">{{ $audit->id }}</td>
                                    <td>
                                        <small>
                                            {{ carbon($audit->created_at)->translatedFormat('Y-m-d') }}<br>
                                            <span class="text-muted">{{ carbon($audit->created_at)->translatedFormat('h:i A') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        @if($audit->admin)
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-user-circle text-primary me-2"></i>
                                                <span>{{ $audit->admin->name }} #{{ $audit->admin->id }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('general.pages.reports.audit_report.system') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucwords(str_replace('_', ' ', $audit->action->value)) }}
                                        </span>
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
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('general.pages.reports.audit_report.no_records') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($audits->hasPages())
                <div class="card-footer">
                    {{ $audits->links() }}
                </div>
            @endif

            <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>
        </div>
    </div>
</div>
