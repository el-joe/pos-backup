<div class="col-12">
    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h5 class="mb-0">New Expense Claim</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Claim Date</label>
                    <input type="date" class="form-control" wire:model="form.claim_date">
                    @error('form.claim_date')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select class="form-select" wire:model="form.category_id">
                        <option value="">Select...</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('form.category_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" wire:model="form.amount">
                    @error('form.amount')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="2" wire:model="form.description"></textarea>
                    @error('form.description')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-theme" wire:click="submit">Submit</button>
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

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">My Expense Claims</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ optional($c->claim_date)->format('Y-m-d') }}</td>
                                <td>{{ numFormat($c->total_amount) }}</td>
                                <td>{{ $c->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $claims->links() }}
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
