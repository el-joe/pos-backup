<div wire:ignore.self class="modal fade" id="editHrmClaimCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('general.pages.hrm.modal.claim_category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('general.pages.hrm.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name">
                        @error('data.name')<small class="text-danger">{{ $message }}</small>@enderror
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
