@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $blog?->id ? 'Edit Blog #' . $blog->id : 'Create Blog' }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.blogs.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title (EN)</label>
                    <input type="text" class="form-control" wire:model.defer="data.title_en">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Title (AR)</label>
                    <input type="text" class="form-control" wire:model.defer="data.title_ar">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Excerpt (EN)</label>
                    <textarea class="form-control" rows="3" wire:model.defer="data.excerpt_en"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Excerpt (AR)</label>
                    <textarea class="form-control" rows="3" wire:model.defer="data.excerpt_ar"></textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Published</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_published" wire:model.defer="data.is_published">
                        <label class="form-check-label" for="is_published">Is Published</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Published At</label>
                    <input type="datetime-local" class="form-control" wire:model.defer="data.published_at">
                </div>

                <div class="col-12">
                    <label class="form-label">Content (EN)</label>
                    <div wire:ignore>
                        <textarea class="textarea" name="data.content_en">{{ $data['content_en'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Content (AR)</label>
                    <div wire:ignore>
                        <textarea class="textarea" name="data.content_ar">{{ $data['content_ar'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" wire:click="save">
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

    <script>
            $(document).ready(function () {
                $(".textarea").map(function () {
                    let el = $(this);
                    el.summernote({
                        callbacks: {
                            onChange: function(contents, $editable) {
                                @this.set(el.attr('name'), contents);
                            },
                        },
                        height: 400,
                        lang: "ar-AR",
                        blockquoteBreakingLevel: 2,
                        toolbar: [
                            ["style", ["style"]],
                            ["font", ["bold", "italic", "underline", "strikethrough", "clear"]],
                            ["fontname", ["fontname"]],
                            ["fontsize", ["fontsize"]],
                            ["color", ["color"]],
                            ["para", ["ul", "ol", "paragraph", "blockquote"]],
                            ["height", ["height"]],
                            ["insert", ["link", "picture", "video"]],
                            ["view", ["fullscreen", "codeview", "help"]],
                            ["mybutton", ["blockquoteButton"]],
                        ],
                        fontNames: ["din_R", "Arial", "Arial Black", "Comic Sans MS", "Courier New", "Helvetica", "Impact", "Lucida Grande", "Tahoma", "Times New Roman", "Verdana"],
                        fontsize: ["8px", "10px", "12px", "14px", "16px", "18px", "20px", "24px", "28px", "32px", "36px"],
                    });
                });
            });
        </script>
@endpush
