<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvancedSettingsRequest;
use App\Settings\AdvancedSettings;
use App\Enums\PaymentTypeEnum;

class AdvancedSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-settings')->only(['view', 'update']);
    }

    public function view(AdvancedSettings $advancedSettings)
    {
        return view('admin.settings.advanced')
            ->with('advancedSettings', $advancedSettings)
            ->with('paymentTypes', PaymentTypeEnum::cases());
    }

    public function update(AdvancedSettingsRequest $request, AdvancedSettings $advancedSettings)
    {
        $advancedSettings->allow_decimal_quantities = $request->boolean('allow_decimal_quantities');
        $advancedSettings->default_discount_method = $request->input('default_discount_method', 'percentage');
        $paymentMethods = $request->input('payment_methods');
        $advancedSettings->payment_methods = is_array($paymentMethods) ? $paymentMethods : [];

        $advancedSettings->save();

        return redirect()
            ->route('admin.settings.advanced.view')
            ->with('success', 'Advanced settings updated successfully');
    }
}
