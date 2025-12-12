<!-- Edit Brand Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="editBrandModalLabel">{{ $current?->id ? __('general.pages.brands.edit_brand') : __('general.pages.brands.new_brand') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="brandName" class="form-label">{{ __('general.pages.brands.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="brandName" placeholder="{{ __('general.pages.brands.name') }}">
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="brandActive" wire:model="data.active">
                        <label class="form-check-label" for="brandActive">{{ __('general.pages.brands.is_active') }}</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.brands.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.brands.save') }}</button>
            </div>
        </div>
    </div>
</div>
