<div class="modal fade" id="editHrmContractModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('general.pages.hrm.modal.contract') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.employee') }}</label>
                    <select class="form-select" wire:model="data.employee_id">
                        <option value="">{{ __('general.pages.hrm.select') }}</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('general.pages.hrm.basic_salary') }}</label>
                    <input type="number" step="0.01" class="form-control" wire:model="data.basic_salary" placeholder="{{ __('general.pages.hrm.zero_amount_placeholder') }}">
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">{{ __('general.pages.hrm.start_date') }}</label>
                        <input type="date" class="form-control" wire:model="data.start_date">
                    </div>
                    <div class="col-6">
                        <label class="form-label">{{ __('general.pages.hrm.end_date') }}</label>
                        <input type="date" class="form-control" wire:model="data.end_date">
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
