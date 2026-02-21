<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">My Profile</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Employee Code</div>
                    <div class="fw-bold">{{ $employee->employee_code }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Name</div>
                    <div class="fw-bold">{{ $employee->name }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Email</div>
                    <div class="fw-bold">{{ $employee->email }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Phone</div>
                    <div class="fw-bold">{{ $employee->phone ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Department</div>
                    <div class="fw-bold">{{ $employee->department?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Designation</div>
                    <div class="fw-bold">{{ $employee->designation?->title ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Manager</div>
                    <div class="fw-bold">{{ $employee->manager?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Hire Date</div>
                    <div class="fw-bold">{{ optional($employee->hire_date)->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-inverse text-opacity-50">Status</div>
                    <div class="fw-bold">{{ $employee->status ?? '-' }}</div>
                </div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-2">Active Contract</h6>
                    @if($employee->activeContract)
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-inverse text-opacity-50">Basic Salary</div>
                                <div class="fw-bold">{{ numFormat($employee->activeContract->basic_salary) }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-inverse text-opacity-50">Start Date</div>
                                <div class="fw-bold">{{ optional($employee->activeContract->start_date)->format('Y-m-d') ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-inverse text-opacity-50">End Date</div>
                                <div class="fw-bold">{{ optional($employee->activeContract->end_date)->format('Y-m-d') ?? '-' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="text-inverse text-opacity-50">No active contract.</div>
                    @endif
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
