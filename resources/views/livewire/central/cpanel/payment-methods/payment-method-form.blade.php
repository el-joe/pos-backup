<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $paymentMethod?->id ? 'Edit Payment Method #' . $paymentMethod->id : 'Create Payment Method' }}
            </h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-theme" href="{{ route('cpanel.payment-methods.list') }}">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" wire:model.defer="data.name">
                    @error('data.name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Icon / Image</label>
                    <input type="file" class="form-control" wire:model="iconFile" accept="image/*">
                    @error('iconFile') <div class="text-danger small">{{ $message }}</div> @enderror

                    <div class="mt-2">
                        @if ($iconFile)
                            <img src="{{ $iconFile->temporaryUrl() }}" alt="icon" class="img-fluid rounded" style="max-height: 64px;">
                        @elseif(!empty($data['icon_path']))
                            <img src="{{ asset('storage/' . $data['icon_path']) }}" alt="icon" class="img-fluid rounded" style="max-height: 64px;">
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Provider (unique)</label>
                    <input type="text" class="form-control" wire:model.defer="data.provider" placeholder="Paypal / Stripe / ...">
                    @error('data.provider') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fee Percentage</label>
                    <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="data.fee_percentage">
                    @error('data.fee_percentage') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fixed Fee</label>
                    <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="data.fixed_fee">
                    @error('data.fixed_fee') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" wire:model.defer="data.active">
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="manual" wire:model.live="data.manual">
                        <label class="form-check-label" for="manual">Manual (no API integration)</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Required Fields (one key per line)</label>
                    <textarea class="form-control" rows="4" wire:model.live="requiredFieldsText" placeholder="client_id\nclient_secret\n..." ></textarea>
                    @error('requiredFieldsText') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="form-text">These keys will be stored in <code>required_fields</code> and used to render Credentials inputs.</div>
                </div>

                <div class="col-12">
                    <h6 class="mb-2">Credentials</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 35%">Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requiredFields as $idx => $key)
                                    <tr>
                                        <td><code>{{ $key }}</code></td>
                                        <td>
                                            <input type="text" class="form-control" wire:model.defer="credentialsInputs.{{ $idx }}.value" placeholder="Value">
                                            @error('credentialsInputs.'.$idx.'.value') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No required fields defined.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(($data['manual'] ?? false))
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Manual Details (JSON)</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addDetailRow">
                                <i class="fa fa-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 18%">Key</th>
                                        <th style="width: 20%">Label (EN)</th>
                                        <th style="width: 20%">Label (AR)</th>
                                        <th>Value (EN)</th>
                                        <th>Value (AR)</th>
                                        <th style="width: 1%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailsInputs as $i => $row)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.key" placeholder="bank_name / phone_number">
                                                @error('detailsInputs.'.$i.'.key') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.label_en" placeholder="Bank Name">
                                                @error('detailsInputs.'.$i.'.label_en') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.label_ar" placeholder="اسم البنك">
                                                @error('detailsInputs.'.$i.'.label_ar') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.value_en" placeholder="Global Corporate Bank">
                                                @error('detailsInputs.'.$i.'.value_en') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" wire:model.defer="detailsInputs.{{ $i }}.value_ar" placeholder="Global Corporate Bank">
                                                @error('detailsInputs.'.$i.'.value_ar') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeDetailRow({{ $i }})" title="Remove">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-text">Saved as <code>details</code> JSON rows (translated labels + values).</div>
                    </div>
                @endif

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
