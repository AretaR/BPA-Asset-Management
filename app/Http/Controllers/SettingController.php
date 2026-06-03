<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function general()
    {
        $settings = Setting::where('group', 'general')->orWhere('group', 'company')->get();
        return view('settings.general', compact('settings'));
    }

    public function email()
    {
        $settings = Setting::where('group', 'email')->get();
        return view('settings.email', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
            'company_email' => ['nullable', 'email'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'email_from_address' => ['nullable', 'email'],
            'email_from_name' => ['nullable', 'string', 'max:255'],
            'email_notifications_enabled' => ['nullable', 'in:0,1,on,off,true,false'],
            'email_notification_recipients' => ['nullable', 'string', 'max:500'],
            'notify_asset_created' => ['nullable', 'in:0,1,on,off,true,false'],
            'notify_asset_updated' => ['nullable', 'in:0,1,on,off,true,false'],
            'notify_asset_checkout' => ['nullable', 'in:0,1,on,off,true,false'],
            'notify_user_created' => ['nullable', 'in:0,1,on,off,true,false'],
            'notify_maintenance' => ['nullable', 'in:0,1,on,off,true,false'],
        ]);

        $checkboxFields = [
            'email_notifications_enabled',
            'notify_asset_created',
            'notify_asset_updated',
            'notify_asset_checkout',
            'notify_user_created',
            'notify_maintenance',
        ];

        foreach ($validated as $key => $value) {
            if ($key === 'company_logo') {
                if ($request->hasFile('company_logo')) {
                    $logo = $request->file('company_logo');

                    $imageData = base64_encode(file_get_contents($logo->getRealPath()));
                    $mime = $logo->getMimeType();
                    $dataUrl = 'data:' . $mime . ';base64,' . $imageData;

                    Setting::set($key, $dataUrl, 'string', 'company');
                }
                continue;
            }

            if (in_array($key, $checkboxFields)) {
                $value = $value === '1' || $value === 'on' || $value === 'true' ? 'true' : 'false';
                Setting::set($key, $value, 'string', 'email');
                continue;
            }

            $group = in_array($key, ['company_name', 'company_address', 'company_email', 'company_phone']) 
                ? 'company' 
                : (in_array($key, ['email_from_address', 'email_from_name', 'email_notification_recipients']) ? 'email' : 'general');
            Setting::set($key, $value, 'string', $group);
        }

        // Save unchecked checkboxes as 'false'
        foreach ($checkboxFields as $field) {
            if (!$request->has($field)) {
                Setting::set($field, 'false', 'string', 'email');
            }
        }

        ActivityLog::logAction('updated', new Setting(), null, $validated);

        return redirect()->back()
            ->with('success', 'Settings updated successfully.');
    }
}
