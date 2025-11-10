@extends('admin.layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@lang('trans.edit_category')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('trans.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">@lang('trans.categories')</a></li>
                    <li class="breadcrumb-item active">@lang('trans.edit')</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">@lang('trans.edit_category')</h3>
                    </div>
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">@lang('trans.name') <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $category->name) }}" 
                                               placeholder="@lang('trans.enter_category_name')"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">@lang('trans.status') <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="">@lang('trans.choose_status')</option>
                                            @foreach($categoryStatus as $value => $label)
                                                <option value="{{ $value }}" 
                                                        {{ old('status', $category->status->value) == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('trans.current_image')</label>
                                        <div class="mb-3">
                                            @if($category->photo)
                                                <img src="{{ asset('storage/' . $category->photo->path) }}" 
                                                     alt="{{ $category->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 150px; height: 150px; object-fit: cover;">
                                                <br>
                                                <small class="text-muted">{{ $category->photo->original_name }}</small>
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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="imagePreview" style="display: none;">
                                            <label>@lang('trans.new_image_preview')</label>
                                            <img id="previewImg" src="" alt="Preview" 
                                                 class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> @lang('trans.update')
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> @lang('trans.back')
                            </a>
                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> @lang('trans.view')
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Image preview
    $('#photo').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });

    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
@endpush
