<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $faq?->id ? 'Edit FAQ #' . $faq->id : 'Create FAQ' }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.faqs.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Question (EN)</label>
                    <input type="text" class="form-control" wire:model.defer="data.question_en">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Question (AR)</label>
                    <input type="text" class="form-control" wire:model.defer="data.question_ar">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Published</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_published" wire:model.defer="data.is_published">
                        <label class="form-check-label" for="is_published">Is Published</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" min="0" class="form-control" wire:model.defer="data.sort_order">
                </div>

                <div class="col-12">
                    <label class="form-label">Answer (EN)</label>
                    <textarea class="form-control" rows="6" wire:model.defer="data.answer_en"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Answer (AR)</label>
                    <textarea class="form-control" rows="6" wire:model.defer="data.answer_ar"></textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
