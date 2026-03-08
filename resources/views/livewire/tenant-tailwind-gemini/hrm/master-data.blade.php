<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.24em] text-brand-600 dark:text-brand-300">{{ __('general.titles.hrm_master_data') }}</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ __('general.titles.hrm_master_data') }}</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            @adminCan('hrm_master_data.create')
                <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" data-bs-toggle="modal" data-bs-target="#editHrmDepartmentModal" wire:click="$dispatch('hrm-department-set-current', null)"><i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }} {{ __('general.pages.hrm.department') }}</button>
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-toggle="modal" data-bs-target="#editHrmDesignationModal" wire:click="$dispatch('hrm-designation-set-current', null)"><i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }} {{ __('general.pages.hrm.designation') }}</button>
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-toggle="modal" data-bs-target="#editHrmEmployeeModal" wire:click="$dispatch('hrm-employee-set-current', null)"><i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }} {{ __('general.pages.hrm.employee') }}</button>
                <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" data-bs-toggle="modal" data-bs-target="#editHrmContractModal" wire:click="$dispatch('hrm-contract-set-employee', null)"><i class="fa fa-plus"></i> {{ __('general.pages.hrm.new') }} {{ __('general.titles.hrm_contracts') }}</button>
            @endadminCan
        </div>
    </div>

    <x-tenant-tailwind-gemini.filter-card :title="__('general.pages.hrm.filters')" icon="fa fa-filter" :expanded="$collapseFilters">
        <x-slot:actions>
            <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800" wire:click="resetFilters"><i class="fa fa-undo"></i> {{ __('general.pages.hrm.reset') }}</button>
        </x-slot:actions>
        <div class="grid gap-4 md:grid-cols-3">
            <div><label class="form-label">{{ __('general.pages.hrm.department') }} {{ __('general.pages.hrm.search') }}</label><input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search') }}" wire:model.blur="filters.departments_search"></div>
            <div><label class="form-label">{{ __('general.pages.hrm.designation') }} {{ __('general.pages.hrm.search') }}</label><input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search') }}" wire:model.blur="filters.designations_search"></div>
            <div><label class="form-label">{{ __('general.pages.hrm.employee') }} {{ __('general.pages.hrm.search') }}</label><input type="text" class="form-control" placeholder="{{ __('general.pages.hrm.search') }}" wire:model.blur="filters.employees_search"></div>
        </div>
    </x-tenant-tailwind-gemini.filter-card>

    <div class="grid gap-6 xl:grid-cols-2">
        <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_departments')" icon="fa fa-sitemap">
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.hrm.id') }}</th><th>{{ __('general.pages.hrm.name') }}</th><th>{{ __('general.pages.hrm.manager') }}</th><th class="text-end">{{ __('general.pages.hrm.action') }}</th></tr></thead><tbody>@foreach($departments as $d)<tr><td>{{ $d->id }}</td><td>{{ $d->name }}</td><td>{{ $d->manager_id ?? '-' }}</td><td class="text-end text-nowrap">@adminCan('hrm_master_data.update')<button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" data-bs-toggle="modal" data-bs-target="#editHrmDepartmentModal" wire:click="$dispatch('hrm-department-set-current', { id: {{ $d->id }} })"><i class="fa fa-pencil"></i></button>@endadminCan @adminCan('hrm_master_data.delete')<button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteDepartmentAlert({{ $d->id }})"><i class="fa fa-times"></i></button>@endadminCan</td></tr>@endforeach</tbody></table></div></div>
        </x-tenant-tailwind-gemini.table-card>

        <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_designations')" icon="fa fa-id-badge">
            <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.hrm.id') }}</th><th>{{ __('general.pages.hrm.title') }}</th><th>{{ __('general.pages.hrm.department') }}</th><th class="text-end">{{ __('general.pages.hrm.action') }}</th></tr></thead><tbody>@foreach($designations as $d)<tr><td>{{ $d->id }}</td><td>{{ $d->title }}</td><td>{{ $d->department?->name ?? '-' }}</td><td class="text-end text-nowrap">@adminCan('hrm_master_data.update')<button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" data-bs-toggle="modal" data-bs-target="#editHrmDesignationModal" wire:click="$dispatch('hrm-designation-set-current', { id: {{ $d->id }} })"><i class="fa fa-pencil"></i></button>@endadminCan @adminCan('hrm_master_data.delete')<button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteDesignationAlert({{ $d->id }})"><i class="fa fa-times"></i></button>@endadminCan</td></tr>@endforeach</tbody></table></div></div>
        </x-tenant-tailwind-gemini.table-card>
    </div>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_employees') . ' (' . __('general.pages.hrm.latest_100') . ')'" icon="fa fa-users">
        <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.hrm.id') }}</th><th>{{ __('general.pages.hrm.code') }}</th><th>{{ __('general.pages.hrm.name') }}</th><th>{{ __('general.pages.hrm.email') }}</th><th>{{ __('general.pages.hrm.status') }}</th><th class="text-end">{{ __('general.pages.hrm.action') }}</th></tr></thead><tbody>@foreach($employees as $e)<tr><td>{{ $e->id }}</td><td>{{ $e->employee_code }}</td><td>{{ $e->name }}</td><td>{{ $e->email }}</td><td>{{ $e->status?->label() ?? '-' }}</td><td class="text-end text-nowrap">@adminCan('hrm_master_data.update')<button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100 dark:bg-sky-500/10 dark:text-sky-300 dark:hover:bg-sky-500/20" data-bs-toggle="modal" data-bs-target="#editHrmEmployeeModal" wire:click="$dispatch('hrm-employee-set-current', { id: {{ $e->id }} })"><i class="fa fa-pencil"></i></button>@endadminCan @adminCan('hrm_master_data.delete')<button class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20" wire:click="deleteEmployeeAlert({{ $e->id }})"><i class="fa fa-times"></i></button>@endadminCan</td></tr>@endforeach</tbody></table></div></div>
    </x-tenant-tailwind-gemini.table-card>

    <x-tenant-tailwind-gemini.table-card :title="__('general.titles.hrm_contracts') . ' (' . __('general.pages.hrm.latest_100') . ')'" icon="fa fa-file-signature">
        <div class="p-5"><div class="table-responsive"><table class="table table-bordered table-hover align-middle"><thead><tr><th>{{ __('general.pages.hrm.id') }}</th><th>{{ __('general.pages.hrm.employee') }}</th><th>{{ __('general.pages.hrm.basic_salary') }}</th><th>{{ __('general.pages.hrm.start') }}</th><th>{{ __('general.pages.hrm.end') }}</th><th>{{ __('general.pages.hrm.active') }}</th></tr></thead><tbody>@foreach($contracts as $c)<tr><td>{{ $c->id }}</td><td>{{ $c->employee?->name ?? $c->employee_id }}</td><td>{{ numFormat($c->basic_salary) }}</td><td>{{ optional($c->start_date)->format('Y-m-d') }}</td><td>{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td><td><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $c->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">{{ $c->is_active ? __('general.pages.hrm.yes') : __('general.pages.hrm.no') }}</span></td></tr>@endforeach</tbody></table></div></div>
    </x-tenant-tailwind-gemini.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.master-data.department-modal')
    @livewire('admin.hrm.master-data.designation-modal')
    @livewire('admin.hrm.master-data.employee-modal')
    @livewire('admin.hrm.master-data.contract-modal')
@endpush
