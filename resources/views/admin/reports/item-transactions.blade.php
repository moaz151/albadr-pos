@extends('admin.layouts.app', [#
    'pageName' => __('trans.item_transactions'),
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('trans.item_transactions_list') }}</h3>
                <div class="card-tools">

                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @include('admin.layouts.partials._flash')
                
                <!-- Filters Form -->
                <div class="card card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter"></i> @lang('trans.filters')
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.reports.item-transactions') }}" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">@lang('trans.date_from')</label>
                                    <input type="date"
                                           id="date_from"
                                           name="date_from"
                                           class="form-control"
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">@lang('trans.date_to')</label>
                                    <input type="date"
                                           id="date_to"
                                           name="date_to"
                                           class="form-control"
                                           value="{{ request('date_to') }}">
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
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> @lang('trans.search')
                                </button>
                                <a href="{{ route('admin.reports.item-transactions') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> @lang('trans.reset')
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>@lang('trans.by')</th>
                      <th>@lang('trans.current_quantity')</th>
                      <th>@lang('trans.description')</th>
                      <th>@lang('trans.price')</th>
                      <th>@lang('trans.quantity')</th>
                      <th>@lang('trans.created_at')</th>
                      <th>@lang('trans.item_name')</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($sales as $sale)
                    @foreach ($sale->items as $item)
                        <tr>
                            <td>{{ $loop->parent->iteration }}</td>
                            <td>{{ $sale->user->full_name }}</td>
                            @php
                                $transaction = optional($sale->warehouseTransactions)
                                    ->where('item_id', $item->id)
                                    ->first();
                            @endphp
                            <td>{{ $transaction?->quantity_after ?? '-' }}</td>
                            <td>
                                @if ($sale->isSale())
                                    @lang('trans.sale_invoice')
                                @else
                                    @lang('trans.return_invoice')
                                @endif
                                - @lang('trans.client_name'):
                                {{ $sale->client->name }}
                            </td>
                            <td>
                                {{ $item->pivot->total_price }}
                            </td>
                            <td>
                                {{ $item->pivot->quantity }}
                            </td>
                            <td>
                                {{ $sale->created_at->toDateTimeString() }}
                            </td>
                            <td>
                                {{ $item->name }}
                            </td>
                        </tr>
                    @endforeach
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                {{ $sales->links() }}
              </div>
          </div>
            <!-- /.card -->
    </div>
</div>

@endsection
