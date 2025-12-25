@extends('admin.layouts.app', [#
    'pageName' => 'Dashboard',
])

@section('content')

@if(isset($lowStockItems) && $lowStockItems->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">
                <i class="fas fa-exclamation-triangle"></i> {{ __('trans.low_stock_warning') ?? 'Low Stock Warning' }}
            </h4>
            <p class="mb-2">{{ __('trans.low_stock_message') ?? 'The following items have reached or fallen below their minimum stock level:' }}</p>
            <hr>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('trans.item_code') }}</th>
                            <th>{{ __('trans.item_name') }}</th>
                            <th>{{ __('trans.current_stock') ?? 'Current Stock' }}</th>
                            <th>{{ __('trans.minimum_stock') ?? 'Minimum Stock' }}</th>
                            <th>{{ __('trans.status') }}</th>
                            <th>{{ __('trans.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockItems as $data)
                            @php
                                $item = $data['item'];
                                $totalStock = $data['total_stock'];
                                $minimumStock = $data['minimum_stock'];
                                $isCritical = $totalStock == 0;
                            @endphp
                            <tr class="{{ $isCritical ? 'table-danger' : 'table-warning' }}">
                                <td>{{ $item->item_code ?? '-' }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <strong class="{{ $isCritical ? 'text-danger' : 'text-warning' }}">
                                        {{ number_format($totalStock, 2) }}
                                    </strong>
                                </td>
                                <td>{{ number_format($minimumStock, 2) }}</td>
                                <td>
                                    @if($isCritical)
                                        <span class="badge badge-danger">{{ __('trans.out_of_stock') ?? 'Out of Stock' }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('trans.low_stock') ?? 'Low Stock' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> {{ __('trans.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- Quick navigation cards --}}
    @can('list-Order')
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
    @endcan
    @can('list-Sale')
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
    @endcan
    @can('list-Client')
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
    @endcan
    @can('list-Item')
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
    @endcan
    @can('list-Return')
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
    @endcan
    @can('list-User')
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-user-shield fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.users') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_users') }}</p>
                </div>
            </div>
        </a>
    </div>
    @endcan
    @can('list-Category')
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
    @endcan
    @can('list-Unit')
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
    @endcan
    @can('list-Warehouse')
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
    @endcan
    @can('list-Role')
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.roles.index') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-user-shield fa-2x mb-2 text-danger"></i>
                    <h5 class="card-title mb-1">{{ __('trans.roles') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_roles') }}</p>
                </div>
            </div>
        </a>
    </div>
    @endcan
    @can('list-Setting')
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.settings.general.view') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-cog fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.general_settings') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_general_settings') }}</p>
                </div>
            </div>
        </a>
    </div>
    @endcan
    @can('list-Setting')
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.settings.advanced.view') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-cog fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.advanced_settings') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_advanced_settings') }}</p>
                </div>
            </div>
        </a>
    </div>
    @endcan
    @can('list-Report')
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.reports.item-transactions') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x mb-2 text-secondary"></i>
                    <h5 class="card-title mb-1">{{ __('trans.item_transactions') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_item_transactions') }}</p>
                </div>
            </div>
        </a>
    </div>
    @endcan
    @can('list-Report')
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('admin.reports.sales-reports') }}" class="text-decoration-none">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-file-invoice-dollar fa-2x mb-2 text-success"></i>
                    <h5 class="card-title mb-1">{{ __('trans.sales_reports') }}</h5>
                    <p class="card-text text-muted small">{{ __('trans.manage_sales_reports') }}</p>
                </div>
            </div>
        </a>
    </div>
    @endcan
</div>

@endsection
