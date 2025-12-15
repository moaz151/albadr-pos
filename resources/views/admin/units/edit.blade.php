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
      <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>@lang('trans.current_image')</label>
                <div class="mb-3">
                    @if($unit->photo)
                        <img src="{{ asset('storage/' . $unit->photo->path) }}" 
                             alt="{{ $unit->name }}" 
                             class="img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <br>
                        <small class="text-muted">{{ $unit->photo->original_name }}</small>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-image text-muted fa-2x"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="photo">@lang('trans.new_image')</label>
                <div class="custom-file">
                    <input type="file"
                           class="custom-file-input @error('photo') is-invalid @enderror" 
                           id="photo"
                           name="photo"
                           accept="image/*">
                    <label class="custom-file-label" for="photo">@lang('trans.choose_new_file')</label>
                </div>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    @lang('trans.leave_empty_keep_current')
                </small>
            </div>
        </div>
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