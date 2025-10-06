@extends('admin.layouts.app', [#
    'pageName' => 'Users',
])

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Items List</h3>
                <div class="card-tools">
                  <a href="{{ route('admin.items.create') }}" class="btn btn-sm btn-primary">
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
                      <th>Item Code</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Category ID</th>
                      <th>Unit ID</th>
                      <th>Status</th>
                      <th>Description</th>
                      <th>Minimum stock</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($items as $item)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->item_code }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>{{ $item->price }}</td>
                      <td>{{ $item->category_id }}</td>
                      <td>{{ $item->unit_id }}</td>
                      <td>
                      <span class="badge badge-{{ $item->status->style() }}">{{ $item->status->label() }}</span>
                      </td>
                      <td>{{ $item->description }}</td>
                      <td>{{ $item->minimum_stock }}</td>
                      <td> 
                        {{-- Edit Button --}}
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit">  </i>
                        </a>
                        {{-- Delete Button --}}
                        <a href="#" data-url="{{ route('admin.items.destroy', $item->id) }}"
                          data-id="{{$item->id}}" class="btn btn-danger btn-sm delete-button">
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
                {{ $items->links() }}
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