<div class="modal fade" id="editHrmEmployeeModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? __('general.pages.hrm.modal.edit_employee') : __('general.pages.hrm.modal.new_employee') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.code') }}</label>
                        <input class="form-control" wire:model="data.employee_code" placeholder="{{ __('general.pages.hrm.employee_code_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.name') }}</label>
                        <input class="form-control" wire:model="data.name" placeholder="{{ __('general.pages.hrm.employee_name_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.email') }}</label>
                        <input type="email" class="form-control" wire:model="data.email" placeholder="{{ __('general.pages.hrm.employee_email_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.phone') }}</label>
                        <input class="form-control" wire:model="data.phone" placeholder="{{ __('general.pages.hrm.employee_phone_placeholder') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.department') }}</label>
                        <select class="form-select" wire:model="data.department_id">
                            <option value="">{{ __('general.pages.hrm.select') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.designation') }}</label>
                        <select class="form-select" wire:model="data.designation_id">
                            <option value="">{{ __('general.pages.hrm.select') }}</option>
                            @foreach($designations as $des)
                                <option value="{{ $des->id }}">{{ $des->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.pages.hrm.manager') }}</label>
                        <select class="form-select" wire:model="data.manager_id">
                            <option value="">{{ __('general.pages.hrm.none') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.hire_date') }}</label>
                        <input type="date" class="form-control" wire:model="data.hire_date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <select class="form-select" wire:model="data.status">
                            <option value="active">{{ __('general.pages.hrm.statuses.active') }}</option>
                            <option value="suspended">{{ __('general.pages.hrm.statuses.suspended') }}</option>
                            <option value="terminated">{{ __('general.pages.hrm.statuses.terminated') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('general.pages.hrm.password') }} {{ $current?->id ? __('general.pages.hrm.leave_blank_to_keep') : '' }}</label>
                        <input type="password" class="form-control" wire:model="data.password" placeholder="{{ __('general.pages.hrm.password_placeholder') }}">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.hrm.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.hrm.save') }}</button>
            </div>
        </div>
    </div>
</div>
