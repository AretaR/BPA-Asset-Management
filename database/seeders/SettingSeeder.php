<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'BPA Asset Management', 'type' => 'string', 'group' => 'company'],
            ['key' => 'company_address', 'value' => 'Broadcasting & Publications Authority\n123 Business Center\nMakati City, Philippines', 'type' => 'string', 'group' => 'company'],
            ['key' => 'company_email', 'value' => 'info@bpa.com', 'type' => 'string', 'group' => 'company'],
            ['key' => 'company_phone', 'value' => '+63 2 123 4567', 'type' => 'string', 'group' => 'company'],
            ['key' => 'timezone', 'value' => 'Pacific/Tarawa', 'type' => 'string', 'group' => 'general'],
            ['key' => 'email_from_address', 'value' => 'noreply@bpa.com', 'type' => 'string', 'group' => 'email'],
            ['key' => 'email_from_name', 'value' => 'BPA Asset Management', 'type' => 'string', 'group' => 'email'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
