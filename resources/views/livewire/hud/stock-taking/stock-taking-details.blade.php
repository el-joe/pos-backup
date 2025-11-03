<div class="white-box">
    <h3 class="box-title">Stock Take Details #{{ $stockTake->id }}</h3>
    <ul class="nav customtab nav-tabs" role="tablist">
        <li role="presentation" class="@if($activeTab === 'details') active @endif">
            <a wire:click="$set('activeTab', 'details')" href="#details" aria-controls="details" role="tab" data-toggle="tab" aria-expanded="true">
                <span class="visible-xs"><i class="fa fa-info-circle"></i></span>
                <span class="hidden-xs">Details</span>
            </a>
        </li>
        <li role="presentation" class="@if($activeTab === 'product_stocks') active @endif">
            <a wire:click="$set('activeTab', 'product_stocks')" href="#product_stocks" aria-controls="product_stocks" role="tab" data-toggle="tab" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-cubes"></i></span>
                <span class="hidden-xs">Product Stocks</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade @if($activeTab === 'details') in active @endif" id="details">
            <div class="stock-details-container">
                <h3 class="section-title"><i class="fa fa-info-circle"></i> Stock Take Details</h3>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="detail-box">
                            <h4><i class="fa fa-building"></i> Branch</h4>
                            <p>{{ $stockTake->branch?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="detail-box">
                            <h4><i class="fa fa-calendar"></i> Date</h4>
                            <p>{{ formattedDate($stockTake->date) }}</p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="detail-box">
                            <h4><i class="fa fa-calendar"></i> Created at</h4>
                            <p>{{ formattedDateTime($stockTake->created_at) }}</p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="detail-box">
                            <h4><i class="fa fa-sticky-note"></i> Note</h4>
                            <p>{{ $stockTake->note ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade @if($activeTab === 'product_stocks') in active @endif" id="product_stocks">
            <div class="products-container">
                <h3 class="section-title"><i class="fa fa-cubes"></i> Product Stocks</h3>
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped product-table">
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
                                @foreach($stockTake->products as $stProduct)
                                    @php $stock = $stProduct->stock; @endphp
                                    <tr>
                                        <td>{{ $stock->product?->name }}</td>
                                        <td>{{ $stock->unit?->name }}</td>
                                        <td>{{ $stProduct->current_qty }}</td>
                                        <td>{{ $stProduct->actual_qty }}</td>
                                        <td>
                                            @php
                                                $difference = $stProduct->difference;
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
                                        <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>
                                           <td>
                                                @if($stProduct->returned == 0)
                                                    <button class="btn btn-sm btn-warning" wire:click="returnStockAlert({{ $stProduct->id }})">
                                                        <i class="fa fa-undo"></i> Return
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fa fa-check"></i> Returned
                                                    </button>
                                                @endif
                                           </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
.white-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 30px 28px;
    margin-top: 20px;
}
.section-title {
    font-size: 22px;
    margin-bottom: 20px;
    font-weight: 600;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}
.detail-box {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    background: #fafafa;
    margin-bottom: 16px;
}
.table-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    padding: 18px;
    margin-bottom: 18px;
}
</style>
@endpush
