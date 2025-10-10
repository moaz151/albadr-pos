@extends('admin.layouts.app', [#
    'pageName' => __('trans.Sales'),
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">@lang('trans.sales_Create')</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" id="main-form">
          @csrf
        <div class="form-group">
          <label for="username">Username</label>
          <input class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Enter Username" name="username">
          @error('username')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        {{-- <button type="submit" id="submit" class="btn btn-primary">Create</button> --}}
      </form>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
      <x-form-submit text="Create" />
    </div>
      
    </div>
     <!-- /.card -->
  </div>
</div>

@endsection