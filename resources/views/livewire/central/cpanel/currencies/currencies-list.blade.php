<div class="col-12">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Currencies List</h5>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCurrencyModal" wire:click="setCurrent(null)">
                    <i class="fa fa-plus"></i> Add Currency
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Sympol</th>
                            <th>Conversion Rate</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currencies as $currency)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->code }}</td>
                                <td>{{ $currency->symbol }}</td>
                                <td>{{ number_format($currency->conversion_rate, 2) }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary me-1"
                                            data-bs-toggle="modal" data-bs-target="#editCurrencyModal"
                                            wire:click="setCurrent({{ $currency->id }})"
                                            title="currency.edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            wire:click="deleteAlert({{ $currency->id }})"
                                            title="currency.delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination center aligned (optional) --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $currencies->links() }}
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

    <!-- Edit / Create Currency Modal -->
    <div class="modal fade" id="editCurrencyModal" tabindex="-1" aria-labelledby="editCurrencyModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCurrencyModalLabel">{{ $current?->id ? 'Edit Currency' : 'Add New Currency' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="currencyName" class="form-label">Currency Name</label>
                            <input type="text" class="form-control" wire:model="data.name" id="currencyName" placeholder="Enter currency name">
                        </div>

                        <div class="mb-3">
                            <label for="currencyCode" class="form-label">Currency Code</label>
                            <input type="text" class="form-control" wire:model="data.code" id="currencyCode" placeholder="Enter currency code">
                        </div>

                        <div class="mb-3">
                            <label for="currencySymbol" class="form-label">Currency Symbol</label>
                            <input type="text" class="form-control" wire:model="data.symbol" id="currencySymbol" placeholder="Enter currency symbol">
                        </div>

                        <div class="mb-3">
                            <label for="currencyConversionRate" class="form-label">Conversion Rate</label>
                            <input type="number" class="form-control" wire:model="data.conversion_rate" id="currencyConversionRate" placeholder="Enter conversion rate">
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
