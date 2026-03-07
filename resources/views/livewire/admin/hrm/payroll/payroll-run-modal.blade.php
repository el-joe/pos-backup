<div wire:ignore.self class="modal fade" id="editHrmPayrollRunModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('general.pages.hrm.modal.payroll_run') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.month') }}</label>
                        <input type="number" class="form-control" min="1" max="12" wire:model="data.month">
                        @error('data.month')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.year') }}</label>
                        <input type="number" class="form-control" min="2000" max="2100" wire:model="data.year">
                        @error('data.year')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.status') }}</label>
                        <select class="form-select" wire:model="data.status">
                            @foreach(App\Enums\PayrollRunStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('data.status')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.pages.hrm.total_payout') }}</label>
                        <input type="number" step="0.01" class="form-control" wire:model="data.total_payout">
                        @error('data.total_payout')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.hrm.cancel') }}</button>
                <button type="button" class="btn btn-theme" wire:click="save">{{ __('general.pages.hrm.save') }}</button>
            </div>
        </div>
    </div>
</div>
