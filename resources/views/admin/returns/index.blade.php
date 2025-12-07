@extends('admin.layouts.app', [
    'pageName' => 'Returns',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Returns List</h3>
                <div class="card-tools">
                  <a href="{{ route('admin.returns.create') }}" class="btn btn-sm btn-primary">
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
                  @forelse ($returns as $return)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $return->invoice_number }}</td>
                      <td>{{ optional($return->client)->name }}</td>
                      <td>{{ $return->discount }}</td>
                      <td>{{ $return->net_amount }}</td>
                      <td>{{ $return->paid_amount }}</td>
                      <td>{{ $return->remaining_amount }}</td>
                      <td>
                        <span class="badge badge-{{ $return->type->style() }}">{{ $return->type->label() }}</span>
                      </td>
                      <td>{{ $return->created_at }}</td>
                      <td> 
                        <a href="{{ route('admin.returns.show', $return->id) }}" class="btn btn-info btn-sm">
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
                {{ $returns->links() }}
              </div>
          </div>
            <!-- /.card -->
    </div>
</div>

@endsection


