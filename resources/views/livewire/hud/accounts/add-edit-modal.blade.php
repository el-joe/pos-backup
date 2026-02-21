    <!-- Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $current?->id ? __('general.pages.admins.edit') : __('general.pages.accounts.new_account') }} {{ __('general.pages.accounts.accounts') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="branchName" class="form-label">{{ __('general.pages.accounts.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="branchName" placeholder="{{ __('general.pages.accounts.enter_name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="branchCode" class="form-label">{{ __('general.pages.accounts.code') }}</label>
                        <input type="text" class="form-control" wire:model="data.code" id="branchCode" placeholder="{{ __('general.pages.accounts.enter_code') }}">
                    </div>

                    @if(!(($filters['model_type'] ?? false) == \App\Models\Tenant\User::class && ($filters['model_id'] ?? false)))
                    <div class="mb-3">
                        <label for="branchSelect" class="form-label">{{ __('general.pages.accounts.branch') }}</label>
                        <select class="form-select" wire:model.live="data.branch_id" id="branchSelect">
                            <option value="">{{ __('general.pages.purchases.select_branch') }}</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="paymentMethodSelect" class="form-label">{{ __('general.pages.accounts.payment_method') }}</label>
                        <select class="form-select" wire:model="data.payment_method_id" id="paymentMethodSelect">
                            <option value="">{{ __('general.pages.accounts.select_payment_method') }}</option>
                            @foreach($paymenthMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(!(($filters['model_type'] ?? false) == \App\Models\Tenant\User::class && ($filters['model_id'] ?? false)))
                    <div class="mb-3">
                        <label for="branchType" class="form-label">{{ __('general.pages.accounts.type') }}</label>
                        <select class="form-select" wire:model="data.type" id="branchType">
                            <option value="">{{ __('general.pages.accounts.select_type') }}</option>
                            @foreach($accountTypes as $type)
                            <option value="{{ $type->value }}" {{ $type->isInvalided() ? 'disabled' : '' }}>
                                {{ $type->label() }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="branchActive" wire:model="data.active">
                        <label class="form-check-label" for="branchActive">{{ __('general.pages.accounts.is_active') }}</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.accounts.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.accounts.save') }}</button>
            </div>
        </div>
    </div>
</div>
