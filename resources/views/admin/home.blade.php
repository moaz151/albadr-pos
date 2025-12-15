@extends('admin.layouts.app', [#
    'pageName' => 'Dashboard',
])

@section('content')

<div class="row">
    {{-- Quick navigation cards --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x mb-2 text-primary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.orders') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_orders') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.sales.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-file-invoice-dollar fa-2x mb-2 text-success"></i>
                    <h5 class="card-title mb-1">{{ __('trans.sales') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_sales') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.clients.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-users fa-2x mb-2 text-info"></i>
                    <h5 class="card-title mb-1">{{ __('trans.clients') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_clients') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.items.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-box fa-2x mb-2 text-warning"></i>
                    <h5 class="card-title mb-1">{{ __('trans.items') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_items') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.returns.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-undo-alt fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.returns') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_returns') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-user-shield fa-2x mb-2 text-danger"></i>
                    <h5 class="card-title mb-1">{{ __('trans.users') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_users') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-tags fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.categories') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_categories') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.units.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-balance-scale fa-2x mb-2 text-dark"></i>
                    <h5 class="card-title mb-1">{{ __('trans.units') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_units') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.warehouses.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-warehouse fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.warehouses') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_warehouses') }}</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection