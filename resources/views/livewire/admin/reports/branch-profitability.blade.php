<div style="margin-top: 20px;">

    <!-- 🔍 Filter Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><i class="glyphicon glyphicon-filter"></i> Filter Options</strong>
        </div>
        <div class="panel-body">
            <form wire:submit.prevent="applyFilter" class="row">
                <div class="col-md-3 form-group">
                    <label for="from_date">From Date</label>
                    <input type="date" id="from_date" wire:model.defer="from_date" class="form-control input-sm">
                </div>
                <div class="col-md-3 form-group">
                    <label for="to_date">To Date</label>
                    <input type="date" id="to_date" wire:model.defer="to_date" class="form-control input-sm">
                </div>
                <div class="col-md-3 form-group">
                    <label for="branch_id">Branch</label>
                    <select id="branch_id" wire:model.defer="branch_id" class="form-control input-sm">
                        <option value="">All Branches</option>
                        @if(function_exists('branches'))
                            @foreach(branches() as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 form-group" style="margin-top: 25px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="glyphicon glyphicon-ok-circle"></i> Apply
                    </button>
                    <button type="button" wire:click="resetFilters" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-refresh"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 📊 Report Panel -->
    <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left" style="padding-top: 7.5px;">
                <i class="glyphicon glyphicon-stats"></i> Branch Profitability
            </h4>
            <small class="pull-right text-warning" style="margin-top: 10px;">
                <i class="glyphicon glyphicon-info-sign"></i> Other Income includes (Purchase Discounts)
            </small>
        </div>

        <div class="panel-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="margin-bottom: 0;">
                    <thead style="background: #f5f5f5;">
                        <tr>
                            <th>Branch</th>
                            <th class="text-right">Sales Revenue</th>
                            <th class="text-right">COGS</th>
                            <th class="text-right">Expenses</th>
                            <th class="text-right">Other Income</th>
                            <th class="text-right">Net Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totals = [
                                'sales_revenue' => 0,
                                'cogs' => 0,
                                'expenses' => 0,
                                'other_income' => 0,
                                'net_profit' => 0,
                            ];
                        @endphp

                        @forelse($report as $row)
                            @php
                                $totals['sales_revenue'] += $row->sales_revenue ?? 0;
                                $totals['cogs'] += $row->cogs ?? 0;
                                $totals['expenses'] += $row->expenses ?? 0;
                                $totals['other_income'] += $row->other_income ?? 0;
                                $totals['net_profit'] += $row->net_profit ?? 0;
                                $isProfit = ($row->net_profit ?? 0) >= 0;
                            @endphp

                            <tr style="background: {{ $isProfit ? '#e7f8ef' : '#fdeaea' }};">
                                <td><strong>{{ $row->branch_name }}</strong> <small class="text-muted">(ID: {{ $row->branch_id ?? '-' }})</small></td>
                                <td class="text-right">{{ number_format($row->sales_revenue ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($row->cogs ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($row->expenses ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($row->other_income ?? 0, 2) }}</td>
                                <td class="text-right" style="font-weight: bold; color: {{ $isProfit ? '#3c763d' : '#a94442' }};">
                                    {{ number_format($row->net_profit ?? 0, 2) }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="glyphicon glyphicon-exclamation-sign"></i> No data found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot style="background: #f9f9f9;">
                        <tr>
                            <th class="text-right">Totals:</th>
                            <th class="text-right">{{ number_format($totals['sales_revenue'], 2) }}</th>
                            <th class="text-right">{{ number_format($totals['cogs'], 2) }}</th>
                            <th class="text-right">{{ number_format($totals['expenses'], 2) }}</th>
                            <th class="text-right">{{ number_format($totals['other_income'], 2) }}</th>
                            <th class="text-right text-primary">{{ number_format($totals['net_profit'], 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
