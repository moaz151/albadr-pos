@extends('admin.layouts.app', [#
    'pageName' => 'Users',
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Unit Edit</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.units.update', $unit->id) }}" id="main-form">
          @method('PUT')
          @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Category Name" name="name" value= "{{ old('name', $unit->name) }}">
          @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
      <div class="form-group">
       <label for="unit_status">Status</label>
        @foreach ( $UnitStatus as $value => $label )
          <div class="form-check">
            <input id="unit_status" class="form-check-input" type="radio" name="status" value="{{ $value }}"
            @if ($loop->first) checked @endif>
            <label class="form-check-label">{{ $label }}</label>
          </div>
        @endforeach
      </div>
      </form>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
      <x-form-submit text="Update" />
    </div>
      
    </div>
     <!-- /.card -->
  </div>
</div>

@endsection