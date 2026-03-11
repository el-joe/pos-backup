<div class="space-y-6">
    <x-tenant-tailwind-gemini.table-card title="My Payslips" icon="fa-file-invoice-dollar" :render-table="false">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Run</th>
                            <th>Gross</th>
                            <th>Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->run?->name ?? $p->payroll_run_id }}</td>
                                <td>{{ numFormat($p->gross_pay) }}</td>
                                <td>{{ numFormat($p->net_pay) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('general.messages.no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
        </div>
        <x-slot:footer>
            <div class="flex justify-center">
                {{ $payslips->links('pagination::default5') }}
            </div>
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>
