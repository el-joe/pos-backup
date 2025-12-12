<!-- Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">
                    {{ $current?->id ? __('general.pages.categories.edit_category') : __('general.pages.categories.new_category') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">{{ __('general.pages.categories.name') }}</label>
                        <input type="text" class="form-control" wire:model="data.name" id="categoryName" placeholder="{{ __('general.pages.categories.name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="categoryParent" class="form-label">{{ __('general.pages.categories.parent_category') }}</label>
                        <select class="form-select" wire:model="data.parent_id" id="categoryParent">
                            <option value="">{{ __('general.pages.categories.n_a') }}</option>
                            @foreach ($allCategories as $cat)
                                @if ($current?->id !== $cat->id)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" wire:ignore>
                        <label for="categoryIcon" class="form-label">{{ __('general.pages.categories.icon') }}</label>
                        <select class="selectpicker form-control" name="data.icon" id="categoryIcon" data-live-search="true" title="{{ __('general.pages.categories.select_icon') }}">
                            @foreach ($bootstrapIcons as $icon)
                                <option value="{{ $icon }}" data-content="<i class='{{ $icon }}'></i> {{ $icon }}">
                                    {{ $icon }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="categoryActive" wire:model="data.active">
                        <label class="form-check-label" for="categoryActive">{{ __('general.pages.categories.is_active') }}</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('general.pages.categories.close') }}</button>
                <button type="button" class="btn btn-primary" wire:click="save">{{ __('general.pages.categories.save') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        $('.selectpicker').selectpicker({});

        $('.selectpicker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            @this.set($(this).attr('name'), $(this).val());
        });

        // add livewire event
        Livewire.on('changeSelect', (data) => {
            $('.selectpicker').selectpicker('val',data[0]);
        });
    </script>
@endpush
