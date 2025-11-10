@extends('admin.layouts.app', [#
    'pageName' => 'Users',
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Item Creation</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.items.store') }}" id="main-form">
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
          <input class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Item Name" name="name" value="{{ old('name') }}">
          @error('name')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        {{-- Item Code input  --}}
        <div class="form-group">
          <label for="item_code">Item Code</label>
          <input class="form-control" id="item_code" placeholder="Enter Item Code" name="item_code"
          value="{{ old('item_code') }}">
        </div>
        {{-- Quantity input  --}}
        
        {{-- Price input  --}}
        <div class="form-group">
          <label for="price">Price</label>
          <input class="form-control" id="price" placeholder="Enter price" name="price" value="{{ old('price') }}">
        </div>
        {{-- Category ID input  --}}
        <div class="form-group">
          <label for="category-id">Category ID</label>
          {{-- <input class="form-control" id="category-id" placeholder="Enter Category ID" name="category-id"> --}}
          <select class="form-control" id="category_id" name="category_id" required value="{{ old('category_id') }}">
            <option value="">Select Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        {{-- Unit ID input  --}}
        <div class="form-group">
          <label for="unit-id">Unit ID</label>
          <select class="form-control" id="unit_id" name="unit_id" required value="{{ old('unit_id') }}">
            <option value="">Select Unit</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
          </select>
        </div>
        {{-- Description input  --}}
        <div class="form-group">
          <label for="description">Description</label>
          <input class="form-control" id="description" placeholder="Enter Description" name="description" value="{{ old('description') }}">
        </div>
        {{-- minimum Stock ID input  --}}
        <div class="form-group">
          <label for="minimum_stock">Minimum Stock</label>
          <input class="form-control" id="minimum_stock" placeholder="Enter Minimum Stock" name="minimum_stock" value="{{ old('minimum_stock') }}">
        </div>
        <div class="form-group">
          <label for="photo">@lang('trans.photo')</label>
          <input type="file" name="photo" id="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror" >
          @error('photo')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      <div class="form-group">
        <label for="gallery">@lang('trans.gallery')</label>
        <input type="file" name="gallery[]" id="gallery"  multiple accept="image/*" class="form-control @error('gallery') is-invalid @enderror" >
        <small>@lang('trans.you_can_select_multiple_images')</small>
        @error('gallery')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
      </div>
      <label>Status</label>
        @foreach ( $ItemStatus as $value => $label )
          <div class="form-check">
            <input id="lable" class="form-check-input" type="radio" name="status" value="{{ $value }}"
            @if ($loop->first) checked @endif>
            <label for="lable" class="form-check-label">{{ $label }}</label>
          </div>
        @endforeach
      <hr>
      <h3>@lang('trans.stock')</h3>
      <div class="form-group">
        <label for="quantity">@lang('trans.quantity')</label>
        <input type="text" class="form-control @error('quantity') is-invalid @enderror"
        id="quantity" name="quantity" value="{{ old('quantity') }}"
        placeholder="@lang('trans.enter_quantity')">
        @error('quantity')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>
      <div class="form-group">
        <label for="warehouse_id">@lang('trans.warehouse')</label>
        <select id="warehouse_id" name="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror">
          <option value="">@lang('trans.choose_warehouse')</option>
          @foreach($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}"
              {{ (string) old('warehouse_id') === (string) $warehouse->id ? 'selected' : '' }}>
              {{ $warehouse->name }}
            </option>
          @endforeach
        </select>
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