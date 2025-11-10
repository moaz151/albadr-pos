@extends('admin.layouts.app', [
    'pageName' => 'Client Details',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        @include('admin.layouts.partials._flash')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Client</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.clients.edit', $client->id) }}" 
                      class="btn btn-sm btn-success">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="200">Name</th><td>{{ $client->name }}</td></tr>
                                <tr><th>Email</th><td>{{ $client->email }}</td></tr>
                                <tr><th>Phone</th><td>{{ $client->phone }}</td></tr>
                                <tr><th>Address</th><td>{{ $client->address }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th width="200">Balance</th><td>{{ $client->balance }}</td></tr>
                                <tr><th>Status</th><td><span class="badge badge-{{ $client->status->style() }}">{{ $client->status->label() }}</span></td></tr>
                                <tr><th>Registered Via</th><td>{{ $client->registered_via->label() }}</td></tr>
                                <tr><th>Created At</th><td>{{ $client->created_at }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sales</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Invoice Number</th>
                            <th>Total</th>
                            <th>Discount</th>
                            <th>Net</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Payment Type</th>
                            <th>Balance</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sale->invoice_number }}</td>
                                <td>{{ $sale->total }}</td>
                                <td>{{ $sale->discount }}</td>
                                <td>{{ $sale->net_amount }}</td>
                                <td>{{ $sale->paid_amount }}</td>
                                <td>{{ $sale->remaining_amount }}</td>
                                <td>{{ $sale->payment_type }}</td> // credit or debit
                                <td>{{ $client->balance }}</td>
                                <td>{{ $sale->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No sales found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $sales->links() }}
            </div>
        </div>
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