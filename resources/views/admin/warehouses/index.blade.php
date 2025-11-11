@extends('admin.layouts.app', [
    'pageName' => 'Warehouses',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Warehouses List</h3>
                <div class="card-tools">
                  <a href="{{ route('admin.warehouses.create') }}" class="btn btn-sm btn-primary">
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
                      <th>Name</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Items Count</th>
                      <th style="width: 150px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($warehouses as $warehouse)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $warehouse->name }}</td>
                      <td>{{ Str::limit($warehouse->description, 50) ?? 'N/A' }}</td>
                      <td>
                      <span class="badge badge-{{ $warehouse->status->style() }}">{{ $warehouse->status->label() }}</span>
                      </td>
                      <td>{{ $warehouse->items_count }}</td>
                      <td> 
                        {{-- Edit Button --}}
                        <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        {{-- Show Button --}}
                        <a href="{{ route('admin.warehouses.show', $warehouse->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        {{-- Delete Button --}}
                        <a href="#"
                            data-url="{{ route('admin.warehouses.destroy', $warehouse->id) }}"
                            data-id="{{$warehouse->id}}"
                            class="btn btn-danger btn-sm delete-button">
                            <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                {{ $warehouses->links() }}
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
                            Swal.fire("Error!", "An error occurred while deleting the warehouse.", "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush


