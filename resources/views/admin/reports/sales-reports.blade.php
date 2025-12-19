{{-- @extends('admin.layouts.app', [
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
                            <i class="fas fa-search"></i> @lang('trans.view') ?? @lang('trans.search')
                        </button>
                    </div>
                </form>

                <!-- Actions row (Print / Export / PDF) -->
                <div class="mb-3 d-flex flex-wrap">
                    <button type="button" class="btn btn-secondary mr-2 mb-2">
                        <i class="fas fa-print"></i> @lang('trans.print') ?? 'Print'
                    </button>
                    <button type="button" class="btn btn-secondary mr-2 mb-2">
                        <i class="fas fa-file-excel"></i> @lang('trans.export_excel') ?? 'Export Excel'
                    </button>
                    <button type="button" class="btn btn-secondary mb-2">
                        <i class="fas fa-file-pdf"></i> @lang('trans.export_pdf') ?? 'Export PDF'
                    </button>
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
                        @foreach($itemsData as $item)
                            <tr>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>
                                    @if ($item->isSale())
                                    @lang('trans.sale_invoice')
                                @else
                                    @lang('trans.return_invoice')
                                @endif
                                - @lang('trans.client_name'):
                                {{ $item->client->name }}
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->warehouse->name }}</td>

                                <td>{{ $item->sold_quantity }}</td>
                                <td>{{ $item->total_sales_amount }}</td>

                                <td>{{ $item->returns->sum('quantity') }}</td>
                                <td>{{ $item->total_returns_amount }}</td>
                                
                                <td>{{ $item->sold_quantity - $item->returns->sum('quantity') }}</td>
                            </tr>
                        @endforeach
                        @if($itemsData->isEmpty())
                            <tr>
                                <td colspan="10" class="text-center">@lang('trans.no_data_available')</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <!-- Totals summary row (similar to screenshot bottom section) -->
                <div class="row text-center mt-4">
                    <div class="col-md-3 mb-3">
                        <strong>@lang('trans.total_return_quantity')</strong>
                        <div class="h4">{{ number_format($totalReturnQty, 2) }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>@lang('trans.total_returns_amount')</strong>
                        <div class="h4">{{ number_format($totalReturnsAmount, 2) }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>@lang('trans.total_net_amount_after_returns')</strong>
                        <div class="h4">{{ number_format($totalSalesAmount - $totalReturnsAmount, 2) }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>@lang('trans.total_sales_amount')</strong>
                        <div class="h4">{{ number_format($totalSalesAmount, 2) }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>@lang('trans.total_net_quantity')</strong>
                        <div class="h4">{{ number_format($totalNetQty, 2) }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>@lang('trans.total_sold_quantity')</strong>
                        <div class="h4">{{ number_format($totalSoldQty, 2) }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
 --}}
