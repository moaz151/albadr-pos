@extends('admin.layouts.app', [#
    'pageName' => __('trans.general_settings'),
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ __('trans.general_settings') }}</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.general.update') }}" id="main-form">
          @csrf
          @method('PUT')
        <div class="form-group">
          <label for="name">Company Name</label>
          <input
            class="form-control @error('company_name') is-invalid @enderror"
            id="name"
            name="company_name"
            value="{{ old('company_name', $generalSettings->company_name) }}"
            placeholder="Enter Company Name" >
          @error('company_name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">Company Email</label>
          <input
            class="form-control @error('company_email') is-invalid @enderror"
            id="email"
            name="company_email"
            value="{{ old('company_email', $generalSettings->company_email) }}"
            placeholder="Enter Company Email" >
          @error('company_email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="phone">Company Phone</label>
          <input
            class="form-control @error('company_phone') is-invalid @enderror"
            id="phone"
            name="company_phone"
            value="{{ old('company_phone', $generalSettings->company_phone) }}"
            placeholder="Enter Company Phone" >
          @error('company_phone')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="logo">Company Logo</label>
          <input
            class="form-control @error('company_logo') is-invalid @enderror"
            type="file"
            id="logo"
            name="company_logo"
            required>
          @error('company_logo')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div>
            <img src="{{ asset('storage/'.$generalSettings->company_logo) }}" style="hight:100px" alt="">
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