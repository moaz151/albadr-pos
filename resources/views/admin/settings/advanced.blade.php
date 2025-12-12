@extends('admin.layouts.app', [
    'pageName' => __('trans.settings'),
])

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Advanced Settings</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.advanced.update') }}" id="advanced-form">
          @csrf
          @method('PUT')

          <div class="form-group">
            <div class="custom-control custom-switch">
              <input
                type="checkbox"
                class="custom-control-input"
                id="allow_decimal_quantities"
                name="allow_decimal_quantities"
                value="1"
                {{ old('allow_decimal_quantities', $advancedSettings->allow_decimal_quantities) ? 'checked' : '' }}>
              <label class="custom-control-label" for="allow_decimal_quantities">
                Allow Decimal Quantities in Sales
              </label>
            </div>
            <small class="form-text text-muted">Enable fractional quantities (e.g., 0.5 kg).</small>
            @error('allow_decimal_quantities')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Default Discount Application Method</label>
            <div class="custom-control custom-radio">
              <input
                class="custom-control-input"
                type="radio"
                id="discount_percentage"
                name="default_discount_method"
                value="percentage"
                {{ old('default_discount_method', $advancedSettings->default_discount_method) === 'percentage' ? 'checked' : '' }}>
              <label class="custom-control-label" for="discount_percentage">Percentage</label>
            </div>
            <div class="custom-control custom-radio">
              <input
                class="custom-control-input"
                type="radio"
                id="discount_fixed"
                name="default_discount_method"
                value="fixed_amount"
                {{ old('default_discount_method', $advancedSettings->default_discount_method) === 'fixed_amount' ? 'checked' : '' }}>
              <label class="custom-control-label" for="discount_fixed">Fixed Amount</label>
            </div>
            @error('default_discount_method')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Activate Available Payment/Till Methods</label>
            @foreach($paymentTypes as $paymentType)
            <div class="custom-control custom-checkbox">
              <input
                class="custom-control-input"
                type="checkbox"
                id="payment_{{ $paymentType->name }}"
                name="payment_methods[]"
                value="{{ $paymentType->name }}"
                {{ in_array($paymentType->name, old('payment_methods', $advancedSettings->payment_methods ?? [])) ? 'checked' : '' }}>
              <label class="custom-control-label" for="payment_{{ $paymentType->name }}">{{ $paymentType->label() }}</label>
            </div>
            @endforeach
            <small class="form-text text-muted">Enabled methods show as buttons on invoice payments.</small>
            @error('payment_methods')
              <span class="text-danger d-block">{{ $message }}</span>
            @enderror
            @error('payment_methods.*')
              <span class="text-danger d-block">{{ $message }}</span>
            @enderror
          </div>

        </form>
      </div>
      <div class="card-footer clearfix">
        <x-form-submit text="Update" formId="advanced-form"/>
      </div>
    </div>
  </div>
</div>
@endsection


