<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('trans.sales_reports') }} - {{ config('app.name') }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
            .table { font-size: 12px; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .filters p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .no-print {
            text-align: center;
            margin: 20px 0;
        }
        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('trans.sales_reports') }}</h1>
        <p>{{ config('app.name') }}</p>
        <p>{{ __('trans.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    @if(request('date_from') || request('date_to') || request('client_id') || request('item_id'))
    <div class="filters">
        <h3>{{ __('trans.filters') }}:</h3>
        @if(request('date_from'))
            <p><strong>{{ __('trans.date_from') }}:</strong> {{ request('date_from') }}</p>
        @endif
        @if(request('date_to'))
            <p><strong>{{ __('trans.date_to') }}:</strong> {{ request('date_to') }}</p>
        @endif
        @if(request('client_id'))
            <p><strong>{{ __('trans.client') }}:</strong> {{ \App\Models\Client::find(request('client_id'))->name ?? '' }}</p>
        @endif
        @if(request('item_id'))
            <p><strong>{{ __('trans.item') }}:</strong> {{ \App\Models\Item::find(request('item_id'))->name ?? '' }}</p>
        @endif
    </div>
    @endif

    <div class="no-print">
        <button onclick="window.print()" class="btn">{{ __('trans.print') }}</button>
        <a href="{{ route('admin.reports.sales-reports', request()->query()) }}" class="btn" style="background: #6c757d; margin-left: 10px;">{{ __('trans.back') }}</a>
    </div>

    <table class="table">
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
                @php
                    $firstItem = $sale->items->first();
                    if (!$firstItem) continue;
                    
                    $soldQuantity = $firstItem->pivot->quantity ?? 0;
                    
                    $returnQuantity = 0;
                    $totalReturnsAmount = 0;
                    if ($sale->isSale()) {
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
                <tr>
                    <td>{{ $firstItem->item_code ?? '' }}</td>
                    <td>{{ $firstItem->name ?? '' }}</td>
                    <td>
                        @if ($sale->isSale())
                            @lang('trans.sale_invoice')
                        @else
                            @lang('trans.return_invoice')
                        @endif
                        - @lang('trans.client_name'): {{ $sale->client->name ?? '' }}
                    </td>
                    <td>{{ $firstItem->category->name ?? '' }}</td>
                    <td>{{ $sale->warehouse->name ?? '' }}</td>
                    <td>{{ $soldQuantity }}</td>
                    <td>{{ number_format($sale->net_amount ?? $sale->total ?? 0, 2) }}</td>
                    <td>{{ $returnQuantity }}</td>
                    <td>{{ number_format($totalReturnsAmount, 2) }}</td>
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

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

