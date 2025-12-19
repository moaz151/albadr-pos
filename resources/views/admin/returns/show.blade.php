@extends('admin.layouts.app', [
    'pageName' => 'Sale Details',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        @include('admin.layouts.partials._flash')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sale Return</h3>
                <div class="card-tools">
                    {{-- <a href="{{ route('admin.returns.print', $return->id) }}" 
                      class="btn btn-sm btn-primary">
                        <i class="fas fa-print"></i> Print Invoice
                    </a> --}}
                    <a href="{{ route('admin.returns.index') }}" 
                      class="btn btn-sm btn-secondary">
                        <i class="fas fa-list"></i> Back to list
                    </a>
                        <a href="{{ route('admin.sales.print', $return->id) }}"
                      class="btn btn-sm btn-primary">
                        <i class="fas fa-print"></i> Print Return
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="220">Invoice #</th><td>{{ $return->invoice_number }}</td></tr>
                                <tr><th>Client</th><td>{{ optional($return->client)->name }}</td></tr>
                                <tr>
                                    <th>Payment Type</th>
                                    <td>
                                        <span class="badge badge-{{ $return->type->style() }}">{{ $return->type->label() }}</span>
                                    </td>
                                </tr>
                                <tr><th>Warehouse</th><td>{{ optional($return->warehouse)->name }}</td></tr>
                                <tr><th>Cash Safe</th><td>{{ optional($return->safe)->name }}</td></tr>
                                <tr><th>User</th><td>{{ optional(auth()->user())->full_name }}</td></tr>
                                <tr><th>Date</th><td>{{ $return->created_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="220">Total</th><td>{{ $return->total }}</td></tr>
                                <tr><th>Discount</th><td>{{ $return->discount }}</td></tr>
                                <tr><th>Discount Type</th><td>{{ $return->type->label() }}</td></tr>
                                <tr><th>Shipping</th><td>{{ $return->shipping_cost }}</td></tr>
                                <tr><th>Net</th><td>{{ $return->net_amount }}</td></tr>
                                <tr><th>Paid</th><td>{{ $return->paid_amount }}</td></tr>
                                <tr><th>Remaining</th><td>{{ $return->remaining_amount }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Items</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Item</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($return->items as $item)
                            <tr>
                                
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->pivot->unit_price }}</td>
                                <td>{{ $item->pivot->quantity }}</td>
                                <td>{{ $item->pivot->total_price }}</td>
                                <td>{{ $item->pivot->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No items found for this return.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


