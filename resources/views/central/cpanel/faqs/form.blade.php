@extends('layouts.cpanel')

@section('content')
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

        @if (session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif

        <form method="POST"
              action="{{ $faq?->id ? route('cpanel.faqs.update', ['id' => $faq->id]) : route('cpanel.faqs.store') }}">
            @csrf
            @if ($faq?->id)
                @method('PUT')
            @endif

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Question (EN)</label>
                        <input type="text" name="question_en"
                               class="form-control @error('question_en') is-invalid @enderror"
                               value="{{ old('question_en', $faq->question_en ?? '') }}">
                        @error('question_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Question (AR)</label>
                        <input type="text" name="question_ar" dir="rtl"
                               class="form-control @error('question_ar') is-invalid @enderror"
                               value="{{ old('question_ar', $faq->question_ar ?? '') }}">
                        @error('question_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Published</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                   value="1"
                                   @checked(old('is_published', $faq->is_published ?? true))>
                            <label class="form-check-label" for="is_published">Is Published</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" min="0" name="sort_order"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Answer (EN)</label>
                        <textarea name="answer_en" rows="6"
                                  class="form-control @error('answer_en') is-invalid @enderror">{{ old('answer_en', $faq->answer_en ?? '') }}</textarea>
                        @error('answer_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Answer (AR)</label>
                        <textarea name="answer_ar" dir="rtl" rows="6"
                                  class="form-control @error('answer_ar') is-invalid @enderror">{{ old('answer_ar', $faq->answer_ar ?? '') }}</textarea>
                        @error('answer_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
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
@endsection
