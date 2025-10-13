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
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
        <div class="form-group">
          <label for="name">Item Name</label>
          <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value= "{{ old('name', $item->name) }}">
          @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        {{-- Item Code input  --}}
        <div class="form-group">
          <label for="item_code">Item Code</label>
          <input class="form-control" id="item_code" value= "{{ old('item_code', $item->item_code) }}" name="item_code">
        </div>
        {{-- Quantity input  --}}
        <div class="form-group">
          <label for="quantity">Quantity</label>
          <input class="form-control" id="quantity" value= "{{ old('quantity', $item->quantity) }}" name="quantity">
        </div>
        {{-- Price input  --}}
        <div class="form-group">
          <label for="price">Price</label>
          <input class="form-control" id="price" value= "{{ old('price', $item->price) }}" name="price">
        </div>
        {{-- Category ID input  --}}
        <div class="form-group">
          <label for="category-id">Category ID</label>
          {{-- <input class="form-control" id="category-id" placeholder="Enter Category ID" name="category-id"> --}}
          <select class="form-control" id="category_id" name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" 
                @if(old('category_id', $item->category_id) == $category->id) selected @endif>
                {{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        {{-- Unit ID input  --}}
        <div class="form-group">
          <label for="unit-id">Unit ID</label>
          <select class="form-control" id="unit_id" name="unit_id" required>
            <option value="">Select Unit</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}"
                @if(old('unit_id', $item->unit_id) == $unit->id) selected @endif
                >{{ $unit->name }}</option>
            @endforeach
          </select>
        </div>
        {{-- Description input  --}}
        <div class="form-group">
          <label for="description">Description</label>
          <input class="form-control" id="description" value="{{ old('description', $item->description) }}" name="description">
        </div>
        {{-- minimum Stock ID input  --}}
        <div class="form-group">
          <label for="minimum_stock">Minimum Stock</label>
          <input class="form-control" id="minimum_stock" value="{{ old('minimum_stock', $item->minimum_stock) }}" name="minimum_stock">
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