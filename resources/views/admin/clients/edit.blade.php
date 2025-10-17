@extends('admin.layouts.app', [#
    'pageName' => 'Users',
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Users Edit</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.clients.update', $client->id) }}" id="main-form">
          @csrf
          @method('PUT')
        <div class="form-group">
          <label for="name">Name</label>
          <input class="form-control @error('name') is-invalid @enderror" id="name" 
          value="{{ old('name', $client->name) }}" name="name">
          @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="email">Email address</label>
          <input name="email" type="email" class="form-control" id="email" 
          value="{{ old('email', $client->email) }}">
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input name="phone" type="text" class="form-control" id="phone" 
          value="{{ old('phone', $client->phone) }}">
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input name="address" type="text" class="form-control" id="address" 
          value="{{ old('address', $client->address) }}">
        </div>
      <div class="form-group">
        <label for="balance">Balance</label>
        <input name="balance" type="text" class="form-control" id="balance" 
        value="{{ old('balance', $client->balance) }}">
      </div>
      <div class="form-group">
       <label>Status</label>
        @foreach ( $clientStatuses as $value => $label )
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
            @if ($loop->first) checked @endif>
            <label class="form-check-label">{{ $label }}</label>
          </div>
        @endforeach
        </div>
      <div class="form-group">
       <label>Client Registered Via</label>
        @foreach ( $clientRegistration as $value => $label )
          <div class="form-check">
            <input class="form-check-input" type="radio" name="registered_via" value="{{ $value }}" id="registered_via"
            @if ($loop->first) checked @endif>
            <label for="registered_via" class="form-check-label">{{ $label }}</label>
          </div>
        @endforeach
        </div>
        {{-- <button type="submit" id="submit" class="btn btn-primary">Create</button> --}}
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