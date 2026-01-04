<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('general.titles.partners') }} ({{ $partners->total() }})</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" href="{{ route('cpanel.partners.create') }}">
                    <i class="fa fa-plus"></i> New Partner
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Commission Rate (%)</th>
                            <th>Referral Code</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <td>{{ $partner->id }}</td>
                                <td>{{ $partner->name }}</td>
                                <td>{{ $partner->email }}</td>
                                <td>{{ $partner->phone ?? '-' }}</td>
                                <td>{{ $partner->country?->name ?? '-' }}</td>
                                <td>{{ $partner->commission_rate }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <code>{{ $partner->referral_code }}</code>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-theme"
                                            onclick="window.copyPartnerReferralLink('{{ config('app.url') }}?p_ref={{ $partner->referral_code }}')"
                                            title="Copy referral link"
                                        >
                                            <i class="fa fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary me-1"
                                        href="{{ route('cpanel.partners.edit', ['id' => $partner->id]) }}" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteAlert({{ $partner->id }})" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $partners->links("pagination::bootstrap-5") }}
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
        window.copyPartnerReferralLink = async function (text) {
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
                // fallback: last-resort prompt
                window.prompt('Copy referral link:', text);
            }
        };
    </script>
@endpush
