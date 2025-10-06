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
        <form method="POST" action="{{ route('admin.items.update', $item->id) }}" id="main-form">
          @method('PUT')
          @csrf
        <div class="form-group">
          <label for="name">Item Name</label>
          <input class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Item Name" name="name">
          @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        {{-- Item Code input  --}}
        <div class="form-group">
          <label for="item_code">Item Code</label>
          <input class="form-control" id="item_code" placeholder="Enter Item Code" name="item_code">
        </div>
        {{-- Quantity input  --}}
        <div class="form-group">
          <label for="quantity">Quantity</label>
          <input class="form-control" id="quantity" placeholder="Enter Quantity" name="quantity">
        </div>
        {{-- Price input  --}}
        <div class="form-group">
          <label for="price">Price</label>
          <input class="form-control" id="price" placeholder="Enter price" name="price">
        </div>
        {{-- Category ID input  --}}
        <div class="form-group">
          <label for="category-id">Category ID</label>
          {{-- <input class="form-control" id="category-id" placeholder="Enter Category ID" name="category-id"> --}}
          <select class="form-control" id="category_id" name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        {{-- Unit ID input  --}}
        <div class="form-group">
          <label for="unit-id">Unit ID</label>
          <select class="form-control" id="unit_id" name="unit_id" required>
            <option value="">Select Unit</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
          </select>
        </div>
        {{-- Description input  --}}
        <div class="form-group">
          <label for="description">Description</label>
          <input class="form-control" id="description" placeholder="Enter Description" name="description">
        </div>
        {{-- minimum Stock ID input  --}}
        <div class="form-group">
          <label for="minimum_stock">Minimum Stock</label>
          <input class="form-control" id="minimum_stock" placeholder="Enter Minimum Stock" name="minimum_stock">
        </div>
       <label>Status</label>
        @foreach ( $ItemStatus as $value => $label )
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
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