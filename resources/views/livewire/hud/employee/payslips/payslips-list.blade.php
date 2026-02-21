<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">My Payslips</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Run</th>
                            <th>Gross</th>
                            <th>Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payslips as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->run?->name ?? $p->payroll_run_id }}</td>
                                <td>{{ numFormat($p->gross_pay) }}</td>
                                <td>{{ numFormat($p->net_pay) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $payslips->links() }}
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
