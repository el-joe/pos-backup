<div class="col-12">
    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.hrm.filters')" icon="fa-filter" collapse-id="hrmPayrollRunsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmPayrollRunsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                <select class="form-select" wire:model.blur="filters.status">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach(App\Enums\PayrollRunStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-secondary w-100" wire:click="$set('filters', [])">{{ __('general.pages.hrm.reset') }}</button>
            </div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_payroll_runs')" icon="fa-money">
        <x-slot:actions>
            @adminCan('hrm_payroll.create')
                <button class="btn btn-sm btn-theme" data-bs-toggle="modal" data-bs-target="#editHrmPayrollRunModal" wire:click="$dispatch('hrm-payroll-run-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>
        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.month') }}</th>
                <th>{{ __('general.pages.hrm.year') }}</th>
                <th>{{ __('general.pages.hrm.status') }}</th>
                <th>{{ __('general.pages.hrm.total_payout') }}</th>
                <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
            </tr>
        </x-slot:head>
        @foreach($runs as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->month }}</td>
                <td>{{ $r->year }}</td>
                <td>{{ $r->status?->label() ?? '-' }}</td>
                <td>{{ numFormat($r->total_payout) }}</td>
                <td class="text-end text-nowrap">
                    @adminCan('hrm_payroll.approve')
                        @if(($r->status?->value ?? $r->status) === App\Enums\PayrollRunStatusEnum::DRAFT->value)
                            <button class="btn btn-sm btn-outline-success me-1" wire:click="approveAlert({{ $r->id }})" title="{{ __('general.pages.hrm.approve_action') }}"><i class="fa fa-check"></i></button>
                        @endif
                    @endadminCan
                    @adminCan('hrm_payroll.pay')
                        @if(($r->status?->value ?? $r->status) === App\Enums\PayrollRunStatusEnum::APPROVED->value)
                            <button class="btn btn-sm btn-outline-theme me-1" wire:click="payAlert({{ $r->id }})" title="{{ __('general.pages.hrm.mark_paid_action') }}"><i class="fa fa-money-bill"></i></button>
                        @endif
                    @endadminCan
                    @adminCan('hrm_payroll.update')
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editHrmPayrollRunModal" wire:click="$dispatch('hrm-payroll-run-set-current', { id: {{ $r->id }} })"><i class="fa fa-pencil"></i></button>
                    @endadminCan
                    @adminCan('hrm_payroll.delete')
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $r->id }})"><i class="fa fa-times"></i></button>
                    @endadminCan
                </td>
            </tr>
        @endforeach
        <x-slot:footer>
            {{ $runs->links('pagination::default5') }}
        </x-slot:footer>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.payroll.payroll-run-modal')
@endpush

