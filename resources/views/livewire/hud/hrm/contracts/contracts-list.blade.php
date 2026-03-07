<div class="col-12">
    <x-hud.filter-card :title="__('general.titles.hrm_contracts')" icon="fa-filter" collapse-id="hrmContractsFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmContractsFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>

        <div class="row g-3">
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-secondary btn-sm" wire:click="resetFilters">
                    <i class="fa fa-undo me-1"></i> {{ __('general.pages.hrm.reset') }}
                </button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.hrm_contracts')" icon="fa-file-contract">
        <x-slot:actions>
            @adminCan('hrm_master_data.create')
                <button class="btn btn-sm btn-theme" data-bs-toggle="modal" data-bs-target="#editHrmContractModal" wire:click="$dispatch('hrm-contract-set-employee', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>

        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.employee') }}</th>
                <th>{{ __('general.pages.hrm.basic_salary') }}</th>
                <th>{{ __('general.pages.hrm.start') }}</th>
                <th>{{ __('general.pages.hrm.end') }}</th>
                <th>{{ __('general.pages.hrm.active') }}</th>
            </tr>
        </x-slot:head>

        @foreach($contracts as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->employee?->name ?? $c->employee_id }}</td>
                <td>{{ numFormat($c->basic_salary) }}</td>
                <td>{{ optional($c->start_date)->format('Y-m-d') }}</td>
                <td>{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td>
                <td><span class="badge bg-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? __('general.pages.hrm.yes') : __('general.pages.hrm.no') }}</span></td>
            </tr>
        @endforeach

        <x-slot:footer>
            {{ $contracts->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.contract-modal')
@endpush
