<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('hrm.clock_in') }} / {{ __('hrm.clock_out') }}</h5>
            <a class="btn btn-outline-secondary" href="{{ route('admin.hrm.attendance.list') }}">
                <i class="fa fa-arrow-left me-1"></i> {{ __('general.pages.hrm.back') }}
            </a>
        </div>

        <div class="card-body">
            @if(session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(!$employee_id)
                <div class="alert alert-warning mb-0">{{ __('hrm.no_employee_record_found') }}</div>
            @else
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <div class="text-muted small">{{ __('general.pages.hrm.date') }}</div>
                            <div class="fw-semibold">{{ now()->format('Y-m-d') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <div class="text-muted small">{{ __('hrm.clock_in_time') }}</div>
                            <div class="fw-semibold">{{ optional($today_attendance?->clock_in)->format('H:i') ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <div class="text-muted small">{{ __('hrm.clock_out_time') }}</div>
                            <div class="fw-semibold">{{ optional($today_attendance?->clock_out)->format('H:i') ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-success" wire:click="clockIn"
                            @if($today_attendance && $today_attendance->clock_in) disabled @endif>
                        <i class="fa fa-sign-in me-1"></i> {{ __('hrm.clock_in') }}
                    </button>

                    <button type="button" class="btn btn-danger" wire:click="clockOut"
                            @if(!$today_attendance || !$today_attendance->clock_in || ($today_attendance && $today_attendance->clock_out)) disabled @endif>
                        <i class="fa fa-sign-out me-1"></i> {{ __('hrm.clock_out') }}
                    </button>
                </div>
            @endif
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
