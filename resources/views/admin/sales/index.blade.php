@extends('admin.layouts.app', [
    'pageName' => 'Sales',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sales List</h3>
                <div class="card-tools">
                  <a href="{{ route('admin.sales.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Create
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @include('admin.layouts.partials._flash')
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Invoice #</th>
                      <th>Client</th>
                      <th>Discount</th>
                      <th>Net</th>
                      <th>Paid</th>
                      <th>Remaining</th>
                      <th>Payment Type</th>
                      <th>Date</th>
                      <th style="width: 120px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse ($sales as $sale)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $sale->invoice_number }}</td>
                      <td>{{ optional($sale->client)->name }}</td>
                      <td>{{ $sale->discount }}</td>
                      <td>{{ $sale->net_amount }}</td>
                      <td>{{ $sale->paid_amount }}</td>
                      <td>{{ $sale->remaining_amount }}</td>
                      <td><span class="badge badge-{{ $sale->payment_type->style() }}">{{ $sale->payment_type->label() }}</span></td>
                      <td>{{ $sale->created_at }}</td>
                      <td> 
                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="11" class="text-center">No sales found.</td>
                    </tr>
                  @endforelse
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


