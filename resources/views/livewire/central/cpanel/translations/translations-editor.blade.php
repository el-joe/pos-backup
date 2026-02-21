<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0">Translations</h5>

                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 small text-muted">File</label>
                    <select class="form-select form-select-sm" style="min-width: 260px" wire:model.live="file">
                        @foreach ($files as $f)
                            <option value="{{ $f }}">{{ $f }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm" wire:click="save">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>

        <div class="card-body">
            @if (empty($file))
                <div class="alert alert-warning mb-0">No translation file selected.</div>
            @elseif (empty($rows))
                <div class="alert alert-info mb-0">No keys found in this file.</div>
            @else
                <div class="table-responsive" style="max-height: 70vh; overflow:auto;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th style="min-width: 380px;">Key</th>
                                @foreach ($locales as $locale)
                                    <th style="min-width: 360px;">{{ strtoupper($locale) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $i => $row)
                                <tr>
                                    <td class="text-break">
                                        <code>{{ $row['key'] }}</code>
                                    </td>

                                    @foreach ($locales as $locale)
                                        @php($value = (string)($row['values'][$locale] ?? ''))
                                        <td>
                                            @if (strlen($value) > 120 || \Illuminate\Support\Str::contains($value, "\n"))
                                                <textarea
                                                    class="form-control form-control-sm"
                                                    rows="3"
                                                    wire:model.defer="rows.{{ $i }}.values.{{ $locale }}"
                                                ></textarea>
                                            @else
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm"
                                                    wire:model.defer="rows.{{ $i }}.values.{{ $locale }}"
                                                />
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
</div>
