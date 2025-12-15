@extends('admin.layouts.app', ['pageName' => __('trans.orders')])

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">@lang('trans.order') #{{ $order->order_number }}</h3>
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">
                        @lang('trans.back')
                    </a>
                </div>
            </div>
            <div class="card-body">
                @include('admin.layouts.partials._flash')

                <div class="row mb-3">
                    <div class="col-md-4">
                        <h5>@lang('trans.client')</h5>
                        <p class="mb-1"><strong>@lang('trans.name'):</strong> {{ $order->client->name ?? '-' }}</p>
                        <p class="mb-1"><strong>@lang('trans.phone'):</strong> {{ $order->shipping_phone }}</p>
                        <p class="mb-1"><strong>@lang('trans.address'):</strong> {{ $order->shipping_address }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5>@lang('trans.order_info')</h5>
                        <p class="mb-1"><strong>@lang('trans.status'):</strong>
                            {{ $order->status->label() }}
                        </p>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                              method="POST" class="form-inline mb-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control form-control-sm mr-2">
                                @foreach($orderStatuses as $value => $label)
                                    <option value="{{ $value }}" @selected($order->status->value == $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">
                                @lang('trans.update')
                            </button>
                        </form>
                        <p class="mb-1"><strong>@lang('trans.payment_method'):</strong>
                            {{ $order->payment_method->label() }}
                        </p>
                        <p class="mb-1"><strong>@lang('trans.created_at'):</strong> {{ $order->created_at?->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5>@lang('trans.totals')</h5>
                        <p class="mb-1"><strong>@lang('trans.subtotal'):</strong> {{ number_format($order->price, 2) }}</p>
                        <p class="mb-1"><strong>@lang('trans.shipping_cost'):</strong> {{ number_format($order->shipping_cost, 2) }}</p>
                        <p class="mb-1"><strong>@lang('trans.total'):</strong> {{ number_format($order->total_price, 2) }}</p>
                        <p class="mb-1"><strong>@lang('trans.sale_id'):</strong> {{ $order->sale_id ?? '-' }}</p>
                    </div>
                </div>

                <h5 class="mt-3">@lang('trans.items')</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('trans.item')</th>
                                <th>@lang('trans.quantity')</th>
                                <th>@lang('trans.unit_price')</th>
                                <th>@lang('trans.total')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->pivot->quantity }}</td>
                                    <td>{{ number_format($item->pivot->unit_price, 2) }}</td>
                                    <td>{{ number_format($item->pivot->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


