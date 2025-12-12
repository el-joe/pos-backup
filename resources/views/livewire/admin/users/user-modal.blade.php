
<!-- Edit/Add User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">{{ $current?->id ? __('general.pages.users.edit_user') : __('general.pages.users.create_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="userName" class="form-label">{{ __('general.pages.users.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="userName" placeholder="{{ __('general.pages.users.enter_user_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">{{ __('general.pages.users.email') }}</label>
                        <input type="email" class="form-control" wire:model="data.email" id="userEmail" placeholder="{{ __('general.pages.users.enter_user_email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="userPhone" class="form-label">{{ __('general.pages.users.phone') }}</label>
                        <input type="text" class="form-control" wire:model="data.phone" id="userPhone" placeholder="{{ __('general.pages.users.enter_user_phone') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="userAddress" class="form-label">{{ __('general.pages.users.address') }}</label>
                        <input type="text" class="form-control" wire:model="data.address" id="userAddress" placeholder="{{ __('general.pages.users.enter_user_address') }}" required>
                    </div>
                    @if($type == 'customer')
                    <div class="mb-3">
                        <label for="userSalesThreshold" class="form-label">{{ __('general.pages.users.sales_threshold') }}</label>
                        <input type="number" class="form-control" wire:model="data.sales_threshold" id="userSalesThreshold" placeholder="{{ __('general.pages.users.enter_sales_threshold') }}" min="0" step="0.01" required>
                    </div>
                    @endif
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="userActive" wire:model="data.active">
                        <label class="form-check-label" for="userActive">{{ __('general.pages.users.is_active') }}</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.users.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.users.save') }}</button>
            </div>
        </div>
    </div>
</div>
