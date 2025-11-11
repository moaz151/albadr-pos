@extends('admin.layouts.app', [#
    'pageName' => 'Users',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>
                <div class="card-tools">
                  <a href="{{ route('admin.clients.create') }}" class="btn btn-sm btn-primary">
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
                      <th style="width: 1%">#</th>
                      <th width="15%">Name</th>
                      <th width="10%">Email</th>
                      <th width="10%">Phone</th>
                      <th width="10%">Address</th>
                      <th width="5%">Balance</th>
                      <th width="10%">Status</th>
                      <th width="10%">Registed Via</th>
                      <th width="10%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($clients as $client)
                      
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $client->name }}</td>
                      <td>{{ $client->email }}</td>
                      <td>{{ $client->phone }}</td>
                      <td>{{ $client->address }}</td>
                      <td>{{ $client->balance }}</td>
                      <td>
                      <span class="badge badge-{{ $client->status->style() }}">{{ $client->status->label() }}</span>
                      </td>
                      <td>{{ $client->registered_via->label() }}</td>
                      <td>
                         {{-- Edit Button --}}
                        <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit">  </i>
                        </a>
                        {{-- Delete Button --}}
                        <a href="#"
                          data-url="{{ route('admin.clients.destroy', $client->id) }}"
                          data-id="{{$client->id}}"
                          class="btn btn-danger btn-sm delete-button">
                          <i class="fas fa-trash"></i>
                        </a>
                        {{-- Show Button --}}
                        <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-warning btn-sm">
                          <i class="fas fa-eye">  </i>
                        </a>

                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                {{ $clients->links() }}
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