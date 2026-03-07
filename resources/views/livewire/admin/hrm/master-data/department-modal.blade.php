<div class="modal fade" id="editHrmDepartmentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? __('general.pages.hrm.modal.edit_department') : __('general.pages.hrm.modal.new_department') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.name') }}</label>
                    <input type="text" class="form-control" wire:model="data.name" placeholder="{{ __('general.pages.hrm.department_name_placeholder') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.parent_department_id') }}</label>
                    <select class="form-select" wire:model="data.parent_id">
                        <option value="">{{ __('general.pages.hrm.none') }}</option>
                        @foreach($departments as $department)
                            @if($department->id !== $current?->id)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.manager_employee_id') }}</label>
                    <select class="form-select" wire:model="data.manager_id">
                        <option value="">{{ __('general.pages.hrm.none') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.hrm.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.hrm.save') }}</button>
            </div>
        </div>
    </div>
</div>
