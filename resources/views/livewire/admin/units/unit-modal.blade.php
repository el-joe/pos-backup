<!-- Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUnitModalLabel">{{ $current?->id ? __('general.pages.units.edit_unit') : __('general.pages.units.new_unit') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="categoryName" class="form-label">{{ __('general.pages.units.name') }}</label>
                    <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="{{ __('general.pages.units.enter_unit_name') }}">
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label">{{ __('general.pages.units.parent_unit') }}</label>
                    <select id="parent_id" wire:model.change="data.parent_id" class="form-select">
                        <option value="0">{{ __('general.pages.units.is_parent') }}</option>
                        @foreach ($parents as $parent)
                            {{ recursiveChildrenForOptions($parent, 'children', 'id', 'name', 0) }}
                        @endforeach
                    </select>
                </div>

                @if(($data['parent_id'] ?? 0) != 0)
                    <div class="mb-3">
                        <label for="count" class="form-label">{{ __('general.pages.units.count') }}</label>
                        <input type="number" step="any" wire:model="data.count" id="count" class="form-control">
                    </div>
                @endif

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="categoryActive" wire:model="data.active">
                    <label class="form-check-label" for="categoryActive">{{ __('general.pages.units.is_active') }}</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.units.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.units.save') }}</button>
            </div>
        </div>
    </div>
</div>
