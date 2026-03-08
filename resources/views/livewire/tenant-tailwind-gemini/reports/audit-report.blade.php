<div class="container-fluid">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.reports.common.filter_options')" icon="fa-filter" class="mb-4">
                <form wire:submit.prevent="applyFilter" class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">{{ __('general.pages.reports.common.date_range') }}</label>
                        <input type="text" data-start_date_key="from_date" data-end_date_key="to_date" class="form-control date_range" id="date_range" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="admin_id" class="form-label">{{ __('general.pages.reports.common.admin') }}</label>
                        <select id="admin_id" name="admin_id" class="form-select select2 form-select-sm">
                            <option value="">{{ __('general.pages.reports.common.all') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $admin->id == ($admin_id??null) ? 'selected' : '' }}>{{ $admin->name }} #{{ $admin->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="action" class="form-label">{{ __('general.pages.reports.audit_report.action') }}</label>
                        <select id="action" name="action" class="form-select select2 form-select-sm">
                            <option value="">{{ __('general.pages.reports.common.all') }}</option>
                            @foreach(\App\Enums\AuditLogActionEnum::cases() as $actionEnum)
                                <option value="{{ $actionEnum->value }}" {{ $actionEnum->value == ($action??null) ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $actionEnum->value)) }}</option>
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
    </x-tenant-tailwind-gemini.filter-card>

    <div class="col-12">
        <x-tenant-tailwind-gemini.table-card :title="__('general.pages.reports.audit_report.title')" icon="fa-history" :render-table="false">
            <div class="d-flex align-items-center justify-content-between px-3 pt-3">
                <div class="d-flex align-items-center">
                </div>
                <small class="text-white-50">{{ __('general.pages.reports.audit_report.total') }}: {{ $audits->total() }}</small>
            </div>
            <div class="pt-2">
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
                                            {{ dateTimeFormat($audit->created_at, true, false) }}<br>
                                            <span class="text-muted">{{ dateTimeFormat($audit->created_at, false, true) }}</span>
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
                <div class="px-3 pb-3">
                    {{ $audits->links('pagination::default5') }}
                </div>
            @endif

        </x-tenant-tailwind-gemini.table-card>
    </div>
</div>
@push('scripts')
    @include('layouts.tenant-tailwind-gemini.partials.select2-script')
        @include('layouts.tenant-tailwind-gemini.partials.daterange-picker-script')
@endpush
