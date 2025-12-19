<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Settings\GeneralSettings;
use App\Http\Requests\Admin\GeneralSettingsRequest;

class GeneralSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-settings')->only(['view', 'update']);
    }

    public function view(GeneralSettings $generalSettings)
    {
        return view('admin.settings.general')->with('generalSettings', $generalSettings);
    }

    public function update(GeneralSettings $generalSettings,GeneralSettingsRequest $request)
    {
        $generalSettings->company_name = $request->company_name;
        $generalSettings->company_email = $request->company_email;
        $generalSettings->company_phone = $request->company_phone;
        $generalSettings->shipping_cost = $request->shipping_cost;
        
        if($request->hasFile('company_logo'))
        {
            $file = $request->file('company_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('settings', $fileName, 'public');
            $generalSettings->company_logo = $path;
        }
        $generalSettings->save();
        return redirect()->route('admin.settings.general.view')
        ->with('success', 'General settings updated successfully');
    }
}
