<div class="col-12">
    <x-hud.filter-card title="New Expense Claim" icon="fa-plus-square">
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
    </x-hud.filter-card>

    <x-hud.table-card title="My Expense Claims" icon="fa-receipt">
        <x-slot:head>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </x-slot:head>
        @foreach($claims as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ optional($c->claim_date)->format('Y-m-d') }}</td>
                <td>{{ numFormat($c->total_amount) }}</td>
                <td>{{ $c->status?->label() ?? '-' }}</td>
            </tr>
        @endforeach
        <x-slot:footer>
            {{ $claims->links('pagination::default5') }}
        </x-slot:footer>
    </x-hud.table-card>
</div>
