@extends('admin.layouts.app', [#
    'pageName' => 'Orders',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Orders List</h3>
                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @include('admin.layouts.partials._flash')
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 1%">#</th>
                      <th>Order Number</th>
                      <th>Client</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Payment Method</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($orders as $order)
                      
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $order->order_number }}</td>
                      <td>{{ $order->client->name }}</td>
                      <td>{{ $order->total_price }}</td><td>
                        <i class="{{ $order->status->icon() }} mr-1"></i>
                        {{ $order->status->label() }}
                      </td>
                      <td>{{ $order->payment_method->label() }}</td>
                      <td>{{ $order->created_at->format('d-m-Y H:i:s') }}</td>
                      <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-warning btn-sm">
                          <i class="fas fa-eye">  </i>
                        </a>
                        {{-- <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-success btn-sm">
                          <i class="fas fa-edit">  </i>
                        </a> --}}
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                {{ $orders->links() }}
              </div>
            </div>
            <!-- /.card -->
    </div>
</div>

@endsection

@push('js')
  <script>
        $('.delete-button').on('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", response.message, "success");
                            location.reload();
                        },
                        error: function (xhr) {
                            Swal.fire("Error!", "An error occurred while deleting the user.", "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush