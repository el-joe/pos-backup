<!-- Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="editBranchModalLabel">
                    {{ $current?->id ? __('general.pages.branches.edit_branch') : __('general.pages.branches.new_branch') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="branchName" class="form-label">{{ __('general.pages.branches.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="branchName" placeholder="{{ __('general.pages.branches.name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="branchPhone" class="form-label">{{ __('general.pages.branches.phone') }}</label>
                        <input type="text" class="form-control" wire:model="data.phone" id="branchPhone" placeholder="{{ __('general.pages.branches.phone') }}">
                    </div>

                    <div class="mb-3">
                        <label for="branchEmail" class="form-label">{{ __('general.pages.branches.email') }}</label>
                        <input type="email" class="form-control" wire:model="data.email" id="branchEmail" placeholder="{{ __('general.pages.branches.email') }}">
                    </div>

                    <div class="mb-3">
                        <label for="branchAddress" class="form-label">{{ __('general.pages.branches.address') }}</label>
                        <input type="text" class="form-control" wire:model="data.address" id="branchAddress" placeholder="{{ __('general.pages.branches.address') }}">
                    </div>

                    <div class="mb-3">
                        <label for="branchWebsite" class="form-label">{{ __('general.pages.branches.website') }}</label>
                        <input type="text" class="form-control" wire:model="data.website" id="branchWebsite" placeholder="{{ __('general.pages.branches.website') }}">
                    </div>

                    <div class="mb-3">
                        <label for="branchTax" class="form-label">{{ __('general.pages.branches.tax') }}</label>
                        <select class="form-select" wire:model="data.tax_id" id="branchTax">
                            <option value="">{{ __('general.pages.branches.select_tax') }}</option>
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="branchActive" wire:model="data.active">
                        <label class="form-check-label" for="branchActive">{{ __('general.pages.branches.is_active') }}</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.branches.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.branches.save') }}</button>
            </div>
        </div>
    </div>
</div>
