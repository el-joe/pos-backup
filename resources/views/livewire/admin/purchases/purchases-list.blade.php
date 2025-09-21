<div class="col-sm-12">
    <div class="white-box">
        <div class="row mb-3" style="margin-bottom:15px;">
            <div class="col-xs-6">
                <h3 class="box-title m-b-0" style="margin:0;">Purchase Orders</h3>
            </div>
            <div class="col-xs-6 text-right">
                {{-- add toggle for edit branch --}}
                <a class="btn btn-primary" href="#">
                    <i class="fa fa-plus"></i> New Purchase Order
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped custom-table color-table primary-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Supplier</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th class="text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->ref_no }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td>{{ $purchase->branch->name }}</td>
                        <td>{{ $purchase->status->label() }}</td>
                        <td>{{ $purchase->total_amount ?? 0 }}</td>
                        <td class="text-nowrap">
                            <a href="#" data-toggle="tooltip" data-original-title="Edit">
                                <i class="fa fa-pencil text-primary m-r-10"></i>
                            </a>
                            <a href="#" wire:click="setCurrent({{ $purchase->id }})" data-toggle="modal" data-target="#paymentModal" data-id="{{ $purchase->id }}">
                                <i class="fa fa-credit-card text-success"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- center pagination --}}
            <div class="pagination-wrapper t-a-c">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
<div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"><!-- wider modal -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
                <h4 class="modal-title" id="paymentModalLabel">💰 Add Payment</h4>
            </div>

            <div class="modal-body">
                <div class="row">
<div class="form-group col-sm-6">
    <label for="paymentAmount" class="control-label">Amount</label>
    <div class="input-group">
        <span class="input-group-addon">$</span>
        <input type="number" class="form-control" id="paymentAmount" wire:model="paymentAmount" placeholder="Enter amount">
        <span class="input-group-addon">
            Due:
            <strong class="text-danger">
                {{ number_format($current->due_amount ?? 0, 2) }}
            </strong>
        </span>
    </div>
    @error('paymentAmount') <span class="text-danger small">{{ $message }}</span> @enderror
</div>

                    <div class="form-group col-sm-6">
                        <label for="paymentMethod" class="control-label">Payment Method</label>
                        <select class="form-control" id="paymentMethod" wire:model="paymentMethod">
                            <option value="">-- Select Method --</option>
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                        @error('paymentMethod') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="paymentNote" class="control-label">Note</label>
                    <textarea class="form-control" id="paymentNote" wire:model="paymentNote" rows="3" placeholder="Optional notes..."></textarea>
                    @error('paymentNote') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <button type="button" class="btn btn-success btn-block" wire:click="savePayment">
                    <i class="glyphicon glyphicon-ok"></i> Save Payment
                </button>

                <hr/>

                <h4 class="text-primary">Recent Payments</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($current->transactions ?? [] as $payment)
                                <tr>
                                    <td>{{ carbon($payment->created_at)->format('Y-m-d') }}</td>
                                    <td><span class="label label-success">{{ $payment->amount }}</span></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                                    <td>{{ $payment->note }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
@push('styles')
@endpush
