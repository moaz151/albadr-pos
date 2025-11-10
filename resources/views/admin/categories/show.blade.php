@extends('admin.layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@lang('trans.category_details')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('trans.home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">@lang('trans.categories')</a></li>
                    <li class="breadcrumb-item active">@lang('trans.view')</li>
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
                        <h3 class="card-title">@lang('trans.category_information')</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> @lang('trans.edit')
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> @lang('trans.back')
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    {{-- @if($category->photo)
                                        <img src="{{ asset('storage/' . $category->photo->path) }}" 
                                             alt="{{ $category->name }}" 
                                             class="img-fluid rounded shadow" 
                                             style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" 
                                             style="width: 300px; height: 300px; margin: 0 auto;">
                                            <i class="fas fa-image text-muted fa-5x"></i>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>@lang('trans.name'):</strong></td>
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>@lang('trans.status'):</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $category->status->style() }}">
                                                    {{ $category->status->label() }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>@lang('trans.items_count'):</strong></td>
                                            <td>
                                                <span class="badge badge-info badge-lg">
                                                    {{ $items->count() }} @lang('trans.items')
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>@lang('trans.created_at'):</strong></td>
                                            <td>{{ $category->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>@lang('trans.updated_at'):</strong></td>
                                            <td>{{ $category->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        {{-- @if($category->photo)
                                            <tr>
                                                <td><strong>@lang('trans.image_info'):</strong></td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $category->photo->original_name }} 
                                                        ({{ number_format($category->photo->size / 1024, 2) }} KB)
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($items->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('trans.items_in_category')</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('trans.item_name')</th>
                                            <th>@lang('trans.price')</th>
                                            <th>@lang('trans.status')</th>
                                            <th>@lang('trans.created_at')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ number_format($item->price, 2) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $item->status->style() }}">
                                                        {{ $item->status->label() }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
