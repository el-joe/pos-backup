@extends('layouts.cpanel')

@section('content')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $page?->id ? 'Edit Page #' . $page->id : 'Create Page' }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.pages.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        <form method="POST"
              action="{{ $page?->id ? route('cpanel.pages.update', ['id' => $page->id]) : route('cpanel.pages.store') }}"
              id="page-form">
            @csrf
            @if ($page?->id)
                @method('PUT')
            @endif

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title (EN)</label>
                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                               value="{{ old('title_en', $page->title_en ?? '') }}">
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Title (AR)</label>
                        <input type="text" name="title_ar" dir="rtl"
                               class="form-control @error('title_ar') is-invalid @enderror"
                               value="{{ old('title_ar', $page->title_ar ?? '') }}">
                        @error('title_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                               placeholder="about-us" value="{{ old('slug', $page->slug ?? '') }}">
                        <div class="form-text">Examples: <code>about-us</code>, <code>terms-conditions</code></div>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Published</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                   value="1"
                                   @checked(old('is_published', $page->is_published ?? true))>
                            <label class="form-check-label" for="is_published">Is Published</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Short Description (EN)</label>
                        <textarea name="short_description_en" rows="3"
                                  class="form-control @error('short_description_en') is-invalid @enderror">{{ old('short_description_en', $page->short_description_en ?? '') }}</textarea>
                        @error('short_description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Short Description (AR)</label>
                        <textarea name="short_description_ar" dir="rtl" rows="3"
                                  class="form-control @error('short_description_ar') is-invalid @enderror">{{ old('short_description_ar', $page->short_description_ar ?? '') }}</textarea>
                        @error('short_description_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Content (EN)</label>
                        <textarea id="content_en" name="content_en" class="d-none">{{ old('content_en', $page->content_en ?? '') }}</textarea>
                        <div id="editor-content-en"></div>
                        @error('content_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Content (AR)</label>
                        <textarea id="content_ar" name="content_ar" class="d-none">{{ old('content_ar', $page->content_ar ?? '') }}</textarea>
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

            $('#page-form').on('submit', function () {
                $('#content_en').val($('#editor-content-en').summernote('code'));
                $('#content_ar').val($('#editor-content-ar').summernote('code'));
                $('#editor-content-en').summernote('destroy');
                $('#editor-content-ar').summernote('destroy');
            });
        });
    </script>
@endpush
@endsection
