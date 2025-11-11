@extends('admin.layouts.app', [
    'pageName' => 'Warehouse Details',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        @include('admin.layouts.partials._flash')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Warehouse</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" 
                      class="btn btn-sm btn-success">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.warehouses.index') }}" 
                      class="btn btn-sm btn-secondary">
                        <i class="fas fa-list"></i> Back to list
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="200">Name</th><td>{{ $warehouse->name }}</td></tr>
                                <tr><th>Status</th><td><span class="badge badge-{{ $warehouse->status->style() }}">{{ $warehouse->status->label() }}</span></td></tr>
                                <tr><th>Created At</th><td>{{ $warehouse->created_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="200">Description</th><td>{{ $warehouse->description ?? 'N/A' }}</td></tr>
                                <tr><th>Items Count</th><td>{{ $warehouse->items->count() }}</td></tr>
                                <tr><th>Updated At</th><td>{{ $warehouse->updated_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Items in Warehouse</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouse->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->pivot->quantity }}</td>
                                <td>{{ $item->price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No items found in this warehouse.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


