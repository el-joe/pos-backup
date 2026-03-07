<div class="modal fade" id="editHrmDesignationModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? __('general.pages.hrm.modal.edit_designation') : __('general.pages.hrm.modal.new_designation') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.title') }}</label>
                    <input type="text" class="form-control" wire:model="data.title" placeholder="{{ __('general.pages.hrm.designation_title_placeholder') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.department') }}</label>
                    <select class="form-select" wire:model="data.department_id">
                        <option value="">{{ __('general.pages.hrm.select') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.base_salary_range') }}</label>
                    <input type="text" class="form-control" wire:model="data.base_salary_range" placeholder="{{ __('general.pages.hrm.base_salary_range_placeholder') }}">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.hrm.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.hrm.save') }}</button>
            </div>
        </div>
    </div>
</div>
