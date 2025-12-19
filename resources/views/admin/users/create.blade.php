@extends('admin.layouts.app', [#
    'pageName' => 'Users',
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Users Create</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" id="main-form">
          @csrf
        <div class="form-group">
          <label for="username">Username</label>
          <input class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Enter Username" name="username">
          @error('username')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
      
        <div class="form-group">
          <label for="full_name">Full Name</label>
           <input class="form-control" id="full_name" placeholder="Enter Username" name="full_name">
        </div>
        <div class="form-group">
          <label for="email">Email address</label>
          <input name="email" type="email" class="form-control" id="email" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="Password">Password</label>
          <input name="password" type="password" class="form-control" id="Password" placeholder="Password">
        </div>
      
      <div class="form-group">
        <label for="password_confirmation">Password</label>
        <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Password">
      </div>

      <div class="form-group">
        <label for="roles">Roles</label>
        <select name="roles[]" class="form-control" multiple>
          @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Direct Permissions (optional)</label>
        <small class="form-text text-muted d-block mb-2">Use this to grant extra permissions in addition to the selected roles.</small>
        <div class="row" style="max-height: 400px; overflow-y: auto;">
          @foreach($permissions as $groupName => $groupPermissions)
            <div class="col-md-6 mb-3">
              <div class="card card-outline card-secondary">
                <div class="card-header">
                  <h3 class="card-title">
                    <input type="checkbox" 
                           class="permission-group-checkbox" 
                           data-group="{{ $groupName }}"
                           id="perm-group-{{ $loop->index }}">
                    <label for="perm-group-{{ $loop->index }}" class="mb-0 ml-2">
                      <strong>{{ $groupName ?? 'General' }}</strong>
                    </label>
                  </h3>
                </div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                  @foreach($groupPermissions as $permission)
                    <div class="form-check">
                      <input class="form-check-input permission-checkbox" 
                             type="checkbox" 
                             name="permissions[]" 
                             value="{{ $permission->id }}" 
                             id="perm-{{ $permission->id }}"
                             data-group="{{ $groupName }}">
                      <label class="form-check-label" for="perm-{{ $permission->id }}">
                        {{ $permission->display_name ?? $permission->name }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
        @error('permissions')
          <span class="text-danger" style="font-size: 0.875rem;">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
        @error('permissions.*')
          <span class="text-danger" style="font-size: 0.875rem;">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
      <div class="form-group">
       <label>Status</label>
        @foreach ( $userStatuses as $value => $label )
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
            @if ($loop->first) checked @endif>
            <label class="form-check-label">{{ $label }}</label>
          </div>
        @endforeach
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

@push('js')
<script>
  $(document).ready(function() {
    // Group checkbox functionality
    $('.permission-group-checkbox').on('change', function() {
      var group = $(this).data('group');
      var isChecked = $(this).is(':checked');
      $('.permission-checkbox[data-group="' + group + '"]').prop('checked', isChecked);
    });

    // Individual permission checkbox - update group checkbox state
    $('.permission-checkbox').on('change', function() {
      var group = $(this).data('group');
      var groupCheckbox = $('.permission-group-checkbox[data-group="' + group + '"]');
      var totalInGroup = $('.permission-checkbox[data-group="' + group + '"]').length;
      var checkedInGroup = $('.permission-checkbox[data-group="' + group + '"]:checked').length;
      
      if (checkedInGroup === 0) {
        groupCheckbox.prop('checked', false);
        groupCheckbox.prop('indeterminate', false);
      } else if (checkedInGroup === totalInGroup) {
        groupCheckbox.prop('checked', true);
        groupCheckbox.prop('indeterminate', false);
      } else {
        groupCheckbox.prop('checked', false);
        groupCheckbox.prop('indeterminate', true);
      }
    });

    // Initialize group checkbox states
    $('.permission-group-checkbox').each(function() {
      var group = $(this).data('group');
      var totalInGroup = $('.permission-checkbox[data-group="' + group + '"]').length;
      var checkedInGroup = $('.permission-checkbox[data-group="' + group + '"]:checked').length;
      
      if (checkedInGroup === 0) {
        $(this).prop('checked', false);
        $(this).prop('indeterminate', false);
      } else if (checkedInGroup === totalInGroup) {
        $(this).prop('checked', true);
        $(this).prop('indeterminate', false);
      } else {
        $(this).prop('checked', false);
        $(this).prop('indeterminate', true);
      }
    });
  });
</script>
@endpush