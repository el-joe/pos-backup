<div class="space-y-6">
    <x-tenant-tailwind-gemini.table-card title="My Payslips" icon="fa-file-invoice-dollar" :render-table="false">
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
        <x-slot:footer>
            <div class="d-flex justify-content-center">
                {{ $payslips->links('pagination::default5') }}
            </div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
