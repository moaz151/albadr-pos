@extends('admin.layouts.app', [
    'pageName' => 'Roles & Permissions',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Roles List</h3>
                <div class="card-tools">
                  <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Create Role
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
                      <th>Name</th>
                      <th>Display Name</th>
                      <th>Group</th>
                      <th>Permissions Count</th>
                      <th>Users Count</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($roles as $role)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td><strong>{{ $role->name }}</strong></td>
                      <td>{{ $role->display_name ?? '-' }}</td>
                      <td>{{ $role->group_name ?? '-' }}</td>
                      <td>
                        <span class="badge badge-info">{{ $role->permissions_count }}</span>
                      </td>
                      <td>
                        <span class="badge badge-secondary">{{ $role->users()->count() }}</span>
                      </td>
                      <td>
                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-success btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($role->users()->count() == 0)
                          <a href="#"
                            data-url="{{ route('admin.roles.destroy', $role->id) }}"
                            data-id="{{$role->id}}"
                            class="btn btn-danger btn-sm delete-button" title="Delete">
                            <i class="fas fa-trash"></i>
                          </a>
                        @else
                          <button type="button" class="btn btn-danger btn-sm" disabled title="Cannot delete - has assigned users">
                            <i class="fas fa-trash"></i>
                          </button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </div>
</div>

@endsection

@push('js')
  <script>
        $('.delete-button').on('click', function (e) {
            e.preventDefault();
            var deleteUrl = $(this).data('url');
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
                        url: deleteUrl,
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
                            var message = xhr.responseJSON && xhr.responseJSON.message
                                ? xhr.responseJSON.message
                                : "An error occurred while deleting the role.";
                            Swal.fire("Error!", message, "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush

