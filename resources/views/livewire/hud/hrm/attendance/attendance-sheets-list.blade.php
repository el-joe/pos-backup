<div class="col-12">
    <x-hud.filter-card :title="__('general.pages.hrm.filters')" icon="fa-filter" collapse-id="hrmAttendanceFilterCollapse" :collapsed="$collapseFilters">
        <x-slot:actions>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" aria-expanded="{{ $collapseFilters ? 'true' : 'false' }}" wire:click="$toggle('collapseFilters')" data-bs-target="#hrmAttendanceFilterCollapse">
                <i class="fa fa-filter me-1"></i> {{ __('general.pages.hrm.show_hide') }}
            </button>
        </x-slot:actions>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                <select class="form-select" wire:model.blur="filters.status">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach(App\Enums\AttendanceSheetStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('general.pages.hrm.department') }}</label>
                <select class="form-select" wire:model.blur="filters.department_id">
                    <option value="">{{ __('general.pages.hrm.all') }}</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-secondary w-100" wire:click="$set('filters', [])">{{ __('general.pages.hrm.reset') }}</button>
            </div>
        </div>
    </x-hud.filter-card>

    <x-hud.table-card :title="__('general.titles.hrm_attendance_sheets')" icon="fa-calendar-check-o">
        <x-slot:actions>
            @adminCan('hrm_attendance.create')
                <button class="btn btn-sm btn-theme" data-bs-toggle="modal" data-bs-target="#editHrmAttendanceSheetModal" wire:click="$dispatch('hrm-attendance-sheet-set-current', null)">
                    <i class="fa fa-plus me-1"></i> {{ __('general.pages.hrm.new') }}
                </button>
            @endadminCan
        </x-slot:actions>
        <x-slot:head>
            <tr>
                <th>{{ __('general.pages.hrm.id') }}</th>
                <th>{{ __('general.pages.hrm.date') }}</th>
                <th>{{ __('general.pages.hrm.department') }}</th>
                <th>{{ __('general.pages.hrm.status') }}</th>
                <th class="text-end">{{ __('general.pages.hrm.action') }}</th>
            </tr>
        </x-slot:head>
        @foreach($sheets as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ optional($s->date)->format('Y-m-d') }}</td>
                <td>{{ $s->department?->name ?? '-' }}</td>
                <td>{{ $s->status?->label() ?? '-' }}</td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-theme me-1" href="{{ route('admin.hrm.attendance-sheets.details', $s->id) }}"><i class="fa fa-eye"></i></a>
                    @adminCan('hrm_attendance.update')
                        @if(($s->status?->value ?? $s->status) === App\Enums\AttendanceSheetStatusEnum::DRAFT->value)
                            <button class="btn btn-sm btn-outline-success me-1" wire:click="submitAlert({{ $s->id }})" title="{{ __('general.pages.hrm.submit_action') }}"><i class="fa fa-paper-plane"></i></button>
                        @endif
                    @endadminCan
                    @adminCan('hrm_attendance.approve')
                        @if(($s->status?->value ?? $s->status) === App\Enums\AttendanceSheetStatusEnum::SUBMITTED->value)
                            <button class="btn btn-sm btn-outline-success me-1" wire:click="approveAlert({{ $s->id }})" title="{{ __('general.pages.hrm.approve_action') }}"><i class="fa fa-check"></i></button>
                        @endif
                    @endadminCan
                    @adminCan('hrm_attendance.update')
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editHrmAttendanceSheetModal" wire:click="$dispatch('hrm-attendance-sheet-set-current', { id: {{ $s->id }} })"><i class="fa fa-pencil"></i></button>
                    @endadminCan
                    @adminCan('hrm_attendance.delete')
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteAlert({{ $s->id }})"><i class="fa fa-times"></i></button>
                    @endadminCan
                </td>
            </tr>
        @endforeach
        <x-slot:footer>
            {{ $sheets->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>

@push('scripts')
    @livewire('admin.hrm.attendance.attendance-sheet-modal')
@endpush

