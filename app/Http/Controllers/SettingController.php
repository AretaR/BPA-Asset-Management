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
        ]);

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

            $group = in_array($key, ['company_name', 'company_address', 'company_email', 'company_phone']) 
                ? 'company' 
                : (in_array($key, ['email_from_address', 'email_from_name']) ? 'email' : 'general');
            Setting::set($key, $value, 'string', $group);
        }

        ActivityLog::logAction('updated', new Setting(), null, $validated);

        return redirect()->back()
            ->with('success', 'Settings updated successfully.');
    }
}
