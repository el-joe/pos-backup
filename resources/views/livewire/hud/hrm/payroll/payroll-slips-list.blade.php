<div class="col-12">
    <x-hud.filter-card :title="__('general.titles.hrm_payslips')" icon="fa-filter" collapse-id="hrmPayslipsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmPayslipsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.employee') }} {{ __('general.pages.hrm.id') }}</label>
                <input type="number" class="form-control" wire:model.blur="filters.employee_id">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.titles.hrm_payroll_runs') }} {{ __('general.pages.hrm.id') }}</label>
                <input type="number" class="form-control" wire:model.blur="filters.payroll_run_id">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters"><i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}</button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.hrm_payslips')" icon="fa-file-text-o">
        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                            <th>{{ __('general.pages.hrm.run') }}</th>
                <th>{{ __('general.pages.hrm.employee') }}</th>
                <th>{{ __('general.pages.hrm.gross') }}</th>
                <th>{{ __('general.pages.hrm.net') }}</th>
            </tr>
        </x-slot:head>
        @foreach($slips as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->run?->id ?? $s->payroll_run_id }}</td>
                <td>{{ $s->employee?->name ?? $s->employee_id }}</td>
                <td>{{ numFormat($s->gross_pay) }}</td>
                <td>{{ numFormat($s->net_pay) }}</td>
            </tr>
        @endforeach
        <x-slot:footer>
            {{ $slips->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>
