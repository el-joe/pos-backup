<div class="card shadow-sm mb-3">
    <div class="card-body d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-sm btn-outline-theme" href="{{ route('employee.dashboard') }}">Dashboard</a>
            <a class="btn btn-sm btn-outline-theme" href="{{ route('employee.profile') }}">Profile</a>
            <a class="btn btn-sm btn-outline-theme" href="{{ route('employee.payslips.list') }}">Payslips</a>
            <a class="btn btn-sm btn-outline-theme" href="{{ route('employee.leaves.list') }}">Leaves</a>
            <a class="btn btn-sm btn-outline-theme" href="{{ route('employee.claims.list') }}">Claims</a>
            <a class="btn btn-sm btn-outline-theme" href="{{ route('employee.attendance.list') }}">Attendance</a>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <div class="small text-inverse text-opacity-50">
                {{ employee()?->name }}
            </div>
            <a class="btn btn-sm btn-outline-danger" href="{{ route('employee.logout') }}">Logout</a>
        </div>
    </div>

    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
</div>
