@extends('admin.layouts.app', [
    'pageName' => 'Create Role',
])

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Create Role</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}" id="main-form">
          @csrf
          
          <div class="form-group">
            <label for="name">Role Name <span class="text-danger">*</span></label>
            <input class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   placeholder="Enter role name (e.g., pos-manager)" 
                   name="name" 
                   value="{{ old('name') }}">
            <small class="form-text text-muted">Use lowercase with hyphens (e.g., pos-manager, online-admin)</small>
            @error('name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="display_name">Display Name</label>
            <input class="form-control @error('display_name') is-invalid @enderror" 
                   id="display_name" 
                   placeholder="Enter display name (e.g., POS Manager)" 
                   name="display_name" 
                   value="{{ old('display_name') }}">
            @error('display_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="group_name">Group Name</label>
            <input class="form-control @error('group_name') is-invalid @enderror" 
                   id="group_name" 
                   placeholder="Enter group name (e.g., pos, online)" 
                   name="group_name" 
                   value="{{ old('group_name') }}">
            @error('group_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label>Permissions <span class="text-danger">*</span></label>
            <div class="row">
              @foreach($permissions as $groupName => $groupPermissions)
                <div class="col-md-6 mb-3">
                  <div class="card card-outline card-primary">
                    <div class="card-header">
                      <h3 class="card-title">
                        <input type="checkbox" 
                               class="group-checkbox" 
                               data-group="{{ $groupName }}"
                               id="group-{{ $loop->index }}">
                        <label for="group-{{ $loop->index }}" class="mb-0 ml-2">
                          <strong>{{ $groupName ?? 'Other' }}</strong>
                        </label>
                      </h3>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                      @foreach($groupPermissions as $permission)
                        <div class="form-check">
                          <input class="form-check-input permission-checkbox" 
                                 type="checkbox" 
                                 name="permissions[]" 
                                 value="{{ $permission->name }}" 
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

        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer clearfix">
        <x-form-submit text="Create" formId="main-form" />
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
    $('.group-checkbox').on('change', function() {
      var group = $(this).data('group');
      var isChecked = $(this).is(':checked');
      $('.permission-checkbox[data-group="' + group + '"]').prop('checked', isChecked);
    });

    // Individual permission checkbox - update group checkbox state
    $('.permission-checkbox').on('change', function() {
      var group = $(this).data('group');
      var groupCheckbox = $('.group-checkbox[data-group="' + group + '"]');
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
    $('.group-checkbox').each(function() {
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

