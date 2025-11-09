<div id="wizardLayout1" class="mb-5">
    {{-- <h4>Order Details</h4> --}}
    {{-- <p>Wizard layout include the number of step and text. Please do note that all the wizard is for uxui ONLY but do not include any javascript or backend logic.</p> --}}
    <div class="card">
        <div class="card-body">
            <div class="nav-wizards-container">
                <nav class="nav nav-wizards-1 mb-2">
                    <div class="nav-item col">
                        <a class="nav-link {{ $step > 0 ? 'completed' : '' }}" href="javascript:">
                            <div class="nav-no">1</div>
                            <div class="nav-text">Order Details</div>
                        </a>
                    </div>
                    <div class="nav-item col">
                        <a class="nav-link {{ $step > 1 ? 'completed' : '' }}" href="javascript:">
                            <div class="nav-no">2</div>
                            <div class="nav-text">Order Products</div>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="pos card" id="pos">
                @if($step == 1)
                <div class="card-body">
                    <div class="form-group col-sm-3" style="margin-bottom:0;">
                        <label for="branchSelect" style="font-weight:600;">Branch:</label>
                        <select class="form-control" id="branchSelect" wire:model.live="data.branch_id">
                            <option value="">-- Choose Branch --</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">
                                {{ $branch->name }} @if($branch->phone) - {{ $branch->phone }} @endif
                            </option>
                            @endforeach
                        </select>
                        @error('data.branch_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- customer_id --}}
                    <div class="form-group col-sm-3" style="margin-bottom:0;">
                        <label for="customerSelect" style="font-weight:600;">Customer:</label>
                        <select class="form-control customer-select" id="customerSelect" wire:model="selectedCustomerId">
                            <option value="">-- Choose Customer --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }} @if($customer->phone) - {{ $customer->phone }} @endif
                            </option>
                            @endforeach
                        </select>
                        @error('selectedCustomerId')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- order_date --}}
                    <div class="form-group col-sm-3" style="margin-bottom:0;">
                        <label for="orderDate" style="font-weight:600;">Order Date:</label>
                        <input type="date" class="form-control" id="orderDate" wire:model="data.order_date">
                        @error('data.order_date')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- invoice_number --}}
                    <div class="form-group col-sm-3" style="margin-bottom:0;">
                        <label for="invoiceNumber" style="font-weight:600;">Invoice Number:</label>
                        <input type="text" class="form-control" id="invoiceNumber" wire:model="data.invoice_number">
                        <small class="text-primary">Leave blank for auto-generated</small>
                    </div>
                    {{-- full col payment_note --}}
                    <div class="form-group col-sm-12" style="margin-bottom:0;">
                        <label for="paymentNote" style="font-weight:600;">Payment Note:</label>
                        <textarea class="form-control" id="paymentNote" wire:model="data.payment_note"></textarea>
                        @error('data.payment_note')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                @elseif($step == 2)
                    @include('hud.pos-page.products')
                @endif
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
            			<a href="#" class="pos-mobile-sidebar-toggler" data-toggle-class="pos-mobile-sidebar-toggled" data-toggle-target="#pos">
				<i class="bi bi-bag"></i>
				<span class="badge">5</span>
			</a>

                        <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
            </div>

        </div>
    </div>
    <div class="card-arrow">
        <div class="card-arrow-top-left"></div>
        <div class="card-arrow-top-right"></div>
        <div class="card-arrow-bottom-left"></div>
        <div class="card-arrow-bottom-right"></div>
    </div>
    <div class="hljs-container">
        <pre><code class="xml" data-url="{{ asset('hud/assets/data/form-wizards/code-1.json') }}"></code></pre>
    </div>
</div>
</div>


@push('scripts')
    <script src="{{ asset('hud/assets/plugins/@highlightjs/cdn-assets/highlight.min.js') }}"></script>
    <script src="{{ asset('hud/assets/js/demo/highlightjs.demo.js') }}"></script>
    <script src="{{ asset('hud/assets/js/demo/sidebar-scrollspy.demo.js') }}"></script>
	<script src="{{ asset('hud/assets/js/demo/pos-customer-order.demo.js') }}"></script>
@endpush
