<div class="container-fluid">
    <h3 class="box-title" style="margin-bottom:32px;">Add Stock Take</h3>

    <div class="row">
        <div class="col-md-5">
            <div class="card section-card">
                <div class="card-body">
                    <h4 class="section-title"><i class="fa fa-info-circle" style="color:#2196f3;"></i> Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_id">Branch</label>
                                <select id="branch_id" class="form-control" wire:model.live="data.branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" id="date" class="form-control" wire:model="data.date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea id="note" class="form-control" wire:model="data.note" placeholder="Optional note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card section-card">
                <div class="card-body">
                    <h4 class="section-title"><i class="fa fa-cubes" style="color:#4caf50;"></i> Products</h4>
                    @if($data['branch_id'] ?? false)
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    {{-- <label for="product_search">Search Product</label> --}}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                            <input
                                                type="text" class="form-control" id="product_search"
                                                placeholder="Search Product by name/code/sku"
                                                onkeydown="productSearchEvent(event)"
                                            >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="products-container">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Current Stock</th>
                                            <th>Actual Stock</th>
                                            <th>Difference</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stocks as $stock)
                                            @foreach($stock as $unit)
                                                <tr>
                                                    <td>{{ $unit['product_name'] }}</td>
                                                    <td>{{ $unit['unit_name'] }}</td>
                                                    <td>{{ $unit['current_stock'] }}</td>
                                                    <td>
                                                        <input type="number" class="form-control" id="countedStock_{{ $unit['product_id'] }}_{{ $unit['unit_id'] }}" min="0" wire:model.lazy="data.countedStock.{{ $unit['product_id'] }}.{{ $unit['unit_id'] }}">
                                                    </td>
                                                    <td>
                                                        @php
                                                            $currentStock = $unit['current_stock'];
                                                            $actualStock = $data['countedStock'][$unit['product_id']][$unit['unit_id']] ?? 0;
                                                            $difference = $actualStock - $currentStock;

                                                            if($difference > 0) {
                                                                $badgeClass = 'badge-success';
                                                                $status = 'Surplus';
                                                                $sign = '+';
                                                            } elseif($difference < 0) {
                                                                $badgeClass = 'badge-danger';
                                                                $status = 'Shortage';
                                                                $sign = '';
                                                            } else {
                                                                $badgeClass = 'badge-secondary';
                                                                $status = 'No Change';
                                                                $sign = '';
                                                            }
                                                        @endphp
                                                        {{ $sign }}{{ $difference }}
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" wire:click="removeProductStock({{ $unit['product_id'] }}, {{ $unit['unit_id'] }})">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">Select a branch to view products.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="button" class="btn btn-primary btn-lg" wire:click="save">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>

</div>

@push('scripts')
        <script>
        window.addEventListener('reset-search-input', event => {
            const input = document.getElementById('product_search');
            if (input) {
                input.value = '';
            }
        });
    </script>

    <script>
        function productSearchEvent(event) {
            // i want set livewire key after 2 seconds of user stop typing
            if (event.key === "Enter") {
                // if user press enter key then set livewire key immediately
                @this.set('product_search', event.target.value);
                clearTimeout(window.productSearchTimeout);
            } else {
                // if user type other key then set livewire key after 2 seconds
                clearTimeout(window.productSearchTimeout);
                window.productSearchTimeout = setTimeout(() => {
                    @this.set('product_search', event.target.value);
                }, 1000);
            }
        }
    </script>

@endpush


    @push('styles')
        <style>
            .section-card {
                border-radius: 18px;
                margin-top: 18px;
                box-shadow: 0 4px 16px rgba(0,0,0,0.10);
                border: 1.5px solid #e3e6ed;
                background: #fff;
            }
            .section-card .card-body {
                padding: 32px 32px 24px 32px;
            }
            .section-title {
                font-size: 22px;
                font-weight: 600;
                margin-bottom: 24px;
                letter-spacing: 0.5px;
            }
            .form-group label {
                font-weight: 500;
                margin-bottom: 6px;
            }
            .table {
                background: #f9f9fb;
                border-radius: 10px;
                overflow: hidden;
            }
            .products-container {
                margin-top: 10px;
            }
            .btn-primary.btn-lg {
                padding: 12px 40px;
                font-size: 18px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(33,150,243,0.08);
            }
        </style>
    @endpush
