<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('adminlte') }}/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('adminlte') }}/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->full_name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item @if(request()->is('admin/sales/*')) menu-open  @endif ">
            <a href="" class="nav-link active">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                @lang('trans.sales')
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.sales.create') }}" class="nav-link @if(request()->routeIs('admin.sales.create')) active  @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Sale</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.sales.index') }}" class="nav-link @if(request()->routeIs('admin.sales.index')) active  @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Sales</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li> --}}
            </ul>
          </li>
          {{-- Returns Section --}}
          <li class="nav-item @if(request()->is('admin/return/*')) menu-open  @endif ">
            <a href="" class="nav-link active">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                @lang('trans.returns')
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.returns.create') }}" class="nav-link @if(request()->routeIs('admin.returns.create')) active  @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create Sale Return</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.returns.index') }}" class="nav-link @if(request()->routeIs('admin.returns.index')) active  @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Returns</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li> --}}
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.users.index')}}" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.categories.index')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Categories
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.items.index')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Items
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.units.index')}}" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Units
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.clients.index')}}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Clients
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.warehouses.index')}}" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                Warehouses
              </p>
            </a>
          </li>
          <li class="nav-item @if(request()->is('admin/settings/*')) menu-open @endif">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                    @lang('trans.settings')
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.settings.general.view') }}" class="nav-link @if(request()->routeIs('admin.settings.general.view')) active @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>General Settings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.advanced.view') }}" class="nav-link @if(request()->routeIs('admin.settings.advanced.view')) active @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Advanced Settings</p>
                    </a>
                </li>
            </ul>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>