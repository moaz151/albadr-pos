@extends('admin.layouts.app', [
    'pageName' => __('trans.sales_reports') ?? 'Sales Reports',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('trans.sales_reports') }}</h3>
            </div>

            <div class="card-body">
                <!-- Filters row (similar to the screenshot) -->
                <form method="GET" action="{{ route('admin.reports.sales-reports') }}" class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">@lang('trans.date_from')</label>
                            <div class="input-group">
                                <input type="date"
                                       id="date_from"
                                       name="date_from"
                                       class="form-control"
                                       value="{{ request('date_from') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">@lang('trans.date_to')</label>
                            <div class="input-group">
                                <input type="date"
                                       id="date_to"
                                       name="date_to"
                                       class="form-control"
                                       value="{{ request('date_to') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="client_id">@lang('trans.client_name')</label>
                            <select id="client_id"
                                    name="client_id"
                                    class="form-control">
                                <option value="">@lang('trans.all')</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="item_id">@lang('trans.item_name')</label>
                            <select id="item_id"
                                    name="item_id"
                                    class="form-control">
                                <option value="">@lang('trans.all')</option>
                                @foreach($items as $itemFilter)
                                    <option value="{{ $itemFilter->id }}" {{ request('item_id') == $itemFilter->id ? 'selected' : '' }}>
                                        {{ $itemFilter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-search"></i> @lang('trans.view')
                        </button>
                    </div>
                </form>

                <!-- Actions row (Print / Export / PDF) -->
                <div class="mb-3 d-flex flex-wrap">
                    <a href="{{ route('admin.reports.sales-reports.print', request()->query()) }}" 
                       target="_blank"
                       class="btn btn-secondary mr-2 mb-2">
                        <i class="fas fa-print"></i> @lang('trans.print')
                    </a>
                    <a href="{{ route('admin.reports.sales-reports.excel', request()->query()) }}" 
                       class="btn btn-secondary mr-2 mb-2">
                        <i class="fas fa-file-excel"></i> @lang('trans.export_excel')
                    </a>
                    <a href="{{ route('admin.reports.sales-reports.pdf', request()->query()) }}" 
                       class="btn btn-secondary mb-2">
                        <i class="fas fa-file-pdf"></i> @lang('trans.export_pdf')
                    </a>
                </div>

                <!-- Main table -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>@lang('trans.item_code')</th>
                                <th>@lang('trans.item_name')</th>
                                <th>@lang('trans.description')</th>
                                <th>@lang('trans.category')</th>
                                <th>@lang('trans.warehouse')</th>
                                <th>@lang('trans.sold_quantity')</th>
                                <th>@lang('trans.total_sales_amount')</th>
                                <th>@lang('trans.return_quantity')</th>
                                <th>@lang('trans.total_returns_amount')</th>
                                <th>@lang('trans.net_quantity')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->items->first()->item_code }}</td>
                                <td>{{ $sale->items->first()->name }}</td>
                                <td>
                                    @if ($sale->isSale())
                                    @lang('trans.sale_invoice')
                                @else
                                    @lang('trans.return_invoice')
                                @endif
                                - @lang('trans.client_name'):
                                {{ $sale->client->name }}
                                </td>
                                <td>{{ $sale->items->first()->category->name }}</td>
                                <td>{{ $sale->warehouse->name }}</td>

                                @php
                                    $firstItem = $sale->items->first();
                                    $soldQuantity = $firstItem ? $firstItem->pivot->quantity : 0;
                                    // Find returns for this sale (Sale records with type=return that have matching items)
                                    $returnQuantity = 0;
                                    $totalReturnsAmount = 0;
                                    if ($sale->isSale() && $firstItem) {
                                        $returns = \App\Models\Sale::where('type', \App\Enums\SaleTypeEnum::return->value)
                                            ->where('client_id', $sale->client_id)
                                            ->whereHas('items', function($q) use ($firstItem) {
                                                $q->where('items.id', $firstItem->id);
                                            })
                                            ->get();
                                        $returnQuantity = $returns->sum(function($return) use ($firstItem) {
                                            $returnItem = $return->items->where('id', $firstItem->id)->first();
                                            return $returnItem ? $returnItem->pivot->quantity : 0;
                                        });
                                        $totalReturnsAmount = $returns->sum('net_amount') ?? 0;
                                    }
                                    $netQuantity = $soldQuantity - $returnQuantity;
                                @endphp
                                <td>{{ $soldQuantity }}</td>
                                <td>{{ $sale->net_amount ?? $sale->total ?? 0 }}</td>

                                <td>{{ $returnQuantity }}</td>
                                <td>{{ $totalReturnsAmount }}</td>
                                
                                <td>{{ $netQuantity }}</td>
                            </tr>
                        @endforeach
                        @if($sales->isEmpty())
                            <tr>
                                <td colspan="10" class="text-center">@lang('trans.no_data_available')</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

