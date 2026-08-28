@extends('layouts.cpanel')

@section('content')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $campaign?->id ? 'Edit Campaign #' . $campaign->id : 'Create Campaign' }}
                @if ($campaign?->id)
                    <span class="badge bg-secondary text-uppercase ms-2">{{ $campaign->status }}</span>
                @endif
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.newsletter.campaigns') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mx-3 mt-3">{{ session('error') }}</div>
        @endif

        <form method="POST"
              action="{{ $campaign?->id ? route('cpanel.newsletter.campaigns.update', ['id' => $campaign->id]) : route('cpanel.newsletter.campaigns.store') }}"
              id="campaign-form">
            @csrf
            @if ($campaign?->id)
                @method('PUT')
            @endif

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Subject (EN)</label>
                        <input type="text" name="subject_en" class="form-control @error('subject_en') is-invalid @enderror"
                               value="{{ old('subject_en', $campaign->subject_en ?? '') }}">
                        @error('subject_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Subject (AR)</label>
                        <input type="text" name="subject_ar" dir="rtl"
                               class="form-control @error('subject_ar') is-invalid @enderror"
                               value="{{ old('subject_ar', $campaign->subject_ar ?? '') }}">
                        @error('subject_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Recipients</label>
                        <select name="recipient_type" id="recipient_type"
                                class="form-select @error('recipient_type') is-invalid @enderror">
                            @php($currentRecipientType = old('recipient_type', $campaign->recipient_type ?? 'all'))
                            <option value="all" @selected($currentRecipientType === 'all')>All Subscribers</option>
                            <option value="active_only" @selected($currentRecipientType === 'active_only')>Active Subscribers Only</option>
                            <option value="manual" @selected($currentRecipientType === 'manual')>Manual List</option>
                        </select>
                        @error('recipient_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Scheduled At (optional)</label>
                        <input type="datetime-local" name="scheduled_at"
                               class="form-control @error('scheduled_at') is-invalid @enderror"
                               value="{{ old('scheduled_at', $campaign?->scheduled_at?->format('Y-m-d\TH:i')) }}">
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12" id="manual-emails-wrap"
                         style="{{ $currentRecipientType === 'manual' ? '' : 'display:none;' }}">
                        <label class="form-label">Manual Emails (one per line or comma-separated)</label>
                        <textarea name="manual_emails" rows="4"
                                  class="form-control @error('manual_emails') is-invalid @enderror">{{ old('manual_emails', $campaign->manual_emails ?? '') }}</textarea>
                        @error('manual_emails')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Body (EN)</label>
                        <textarea id="body_en" name="body_en" class="d-none">{{ old('body_en', $campaign->body_en ?? '') }}</textarea>
                        <div id="editor-body-en"></div>
                        @error('body_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Body (AR)</label>
                        <textarea id="body_ar" name="body_ar" class="d-none">{{ old('body_ar', $campaign->body_ar ?? '') }}</textarea>
                        <div id="editor-body-ar" dir="rtl"></div>
                        @error('body_ar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save as Draft
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if ($campaign?->id)
            <div class="card-body border-top">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('cpanel.newsletter.campaigns.preview', ['id' => $campaign->id]) }}"
                              class="d-flex gap-2">
                            @csrf
                            <input type="email" name="preview_email" required placeholder="preview@example.com"
                                   class="form-control" value="{{ old('preview_email') }}">
                            <button type="submit" class="btn btn-outline-theme text-nowrap">
                                <i class="fa fa-paper-plane"></i> Send Preview
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <form method="POST" action="{{ route('cpanel.newsletter.campaigns.send', ['id' => $campaign->id]) }}"
                              onsubmit="return confirm('Send this campaign to all matching recipients now?');">
                            @csrf
                            <button type="submit" class="btn btn-success" @disabled(in_array($campaign->status, ['sending', 'sent']))>
                                <i class="fa fa-rocket"></i> Send Campaign
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

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

            $('#editor-body-en').summernote({
                ...summernoteConfig,
                callbacks: {
                    onChange: function (contents) {
                        $('#body_en').val(contents);
                    },
                },
            });
            $('#editor-body-en').summernote('code', $('#body_en').val());

            $('#editor-body-ar').summernote({
                ...summernoteConfig,
                callbacks: {
                    onChange: function (contents) {
                        $('#body_ar').val(contents);
                    },
                },
            });
            $('#editor-body-ar').summernote('code', $('#body_ar').val());

            $('#recipient_type').on('change', function () {
                $('#manual-emails-wrap').toggle($(this).val() === 'manual');
            });

            $('#campaign-form').on('submit', function () {
                $('#body_en').val($('#editor-body-en').summernote('code'));
                $('#body_ar').val($('#editor-body-ar').summernote('code'));
                $('#editor-body-en').summernote('destroy');
                $('#editor-body-ar').summernote('destroy');
            });
        });
    </script>
@endpush
@endsection
