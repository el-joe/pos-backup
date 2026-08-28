@extends('layouts.cpanel')

@section('content')
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

        @if (session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        <form method="POST"
              action="{{ $blog?->id ? route('cpanel.blogs.update', ['id' => $blog->id]) : route('cpanel.blogs.store') }}"
              enctype="multipart/form-data" id="blog-form">
            @csrf
            @if ($blog?->id)
                @method('PUT')
            @endif

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title (EN)</label>
                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                               value="{{ old('title_en', $blog->title_en ?? '') }}">
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Title (AR)</label>
                        <input type="text" name="title_ar" dir="rtl"
                               class="form-control @error('title_ar') is-invalid @enderror"
                               value="{{ old('title_ar', $blog->title_ar ?? '') }}">
                        @error('title_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Excerpt (EN)</label>
                        <textarea name="excerpt_en" rows="3"
                                  class="form-control @error('excerpt_en') is-invalid @enderror">{{ old('excerpt_en', $blog->excerpt_en ?? '') }}</textarea>
                        @error('excerpt_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Excerpt (AR)</label>
                        <textarea name="excerpt_ar" dir="rtl" rows="3"
                                  class="form-control @error('excerpt_ar') is-invalid @enderror">{{ old('excerpt_ar', $blog->excerpt_ar ?? '') }}</textarea>
                        @error('excerpt_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Published</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                   value="1"
                                   @checked(old('is_published', $blog->is_published ?? true))>
                            <label class="form-check-label" for="is_published">Is Published</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Published At</label>
                        <input type="datetime-local" name="published_at"
                               class="form-control @error('published_at') is-invalid @enderror"
                               value="{{ old('published_at', $blog?->published_at?->format('Y-m-d\TH:i')) }}">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Image</label>
                        <input type="file" name="imageFile" id="imageFile"
                               class="form-control @error('imageFile') is-invalid @enderror" accept="image/*">
                        @error('imageFile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mt-2">
                            <img id="imagePreview" src="{{ $blog?->image_path ?? '' }}" alt="Blog image"
                                 class="img-fluid rounded {{ $blog?->image_path ? '' : 'd-none' }}"
                                 style="max-height: 180px;">
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Content (EN)</label>
                        <textarea id="content_en" name="content_en" class="d-none">{{ old('content_en', $blog->content_en ?? '') }}</textarea>
                        <div id="editor-content-en"></div>
                        @error('content_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Content (AR)</label>
                        <textarea id="content_ar" name="content_ar" class="d-none">{{ old('content_ar', $blog->content_ar ?? '') }}</textarea>
                        <div id="editor-content-ar" dir="rtl"></div>
                        @error('content_ar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </form>

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
            const summernoteConfig = {
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
                ],
                fontNames: ["din_R", "Arial", "Arial Black", "Comic Sans MS", "Courier New", "Helvetica", "Impact", "Lucida Grande", "Tahoma", "Times New Roman", "Verdana"],
                fontsize: ["8px", "10px", "12px", "14px", "16px", "18px", "20px", "24px", "28px", "32px", "36px"],
            };

            $('#editor-content-en').summernote({
                ...summernoteConfig,
                callbacks: {
                    onChange: function (contents) {
                        $('#content_en').val(contents);
                    },
                },
            });
            $('#editor-content-en').summernote('code', $('#content_en').val());

            $('#editor-content-ar').summernote({
                ...summernoteConfig,
                callbacks: {
                    onChange: function (contents) {
                        $('#content_ar').val(contents);
                    },
                },
            });
            $('#editor-content-ar').summernote('code', $('#content_ar').val());

            $('#imageFile').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const url = URL.createObjectURL(file);
                    $('#imagePreview').attr('src', url).removeClass('d-none');
                }
            });

            $('#blog-form').on('submit', function () {
                $('#content_en').val($('#editor-content-en').summernote('code'));
                $('#content_ar').val($('#editor-content-ar').summernote('code'));
                $('#editor-content-en').summernote('destroy');
                $('#editor-content-ar').summernote('destroy');
            });
        });
    </script>
@endpush
@endsection
