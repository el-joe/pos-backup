<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $partner?->id ? 'Edit Partner #' . $partner->id : 'Create Partner' }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.partners.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model.defer="data.name">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" wire:model.defer="data.email">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" wire:model.defer="data.phone">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <select class="form-select" wire:model.defer="data.country_id">
                        <option value="">-- Select --</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Commission Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control" wire:model.defer="data.commission_rate">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Referral Code</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $partner?->referral_code ?? '' }}" disabled>
                        <button
                            class="btn btn-outline-theme"
                            type="button"
                            @if($partner?->referral_code)
                                    onclick="window.copyPartnerReferralLink('{{ config('app.url') }}?p_ref={{ $partner->referral_code }}')"
                            @else
                                disabled
                            @endif
                            title="Copy referral link"
                        >
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" rows="3" wire:model.defer="data.address"></textarea>
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

@push('scripts')
    <script>
        window.copyPartnerReferralLink = window.copyPartnerReferralLink || async function (text) {
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                } else {
                    const el = document.createElement('textarea');
                    el.value = text;
                    el.setAttribute('readonly', '');
                    el.style.position = 'absolute';
                    el.style.left = '-9999px';
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);
                }

                swal.fire({
                    icon: 'success',
                    title: 'Referral link copied to clipboard',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });

            } catch (e) {
                window.prompt('Copy referral link:', text);
            }
        };
    </script>
@endpush
