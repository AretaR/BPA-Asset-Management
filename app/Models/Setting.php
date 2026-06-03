<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    public const GROUP_GENERAL = 'general';
    public const GROUP_COMPANY = 'company';
    public const GROUP_EMAIL = 'email';
    public const GROUP_SYSTEM = 'system';

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    public static function allByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    public static function companyName(): string
    {
        return self::get('company_name', 'BPA Asset Management');
    }

    public static function companyLogo(): ?string
    {
        return self::get('company_logo');
    }

    public static function companyLogoSrc(): ?string
    {
        $logo = self::companyLogo();
        if (!$logo) return null;

        if (str_starts_with($logo, 'data:')) {
            return $logo;
        }

        $path = ltrim((string) $logo, '/');
        $path = preg_replace('#^storage/#', '', $path);

        return route('media.public', ['path' => $path]);
    }

    public static function companyAddress(): ?string
    {
        return self::get('company_address');
    }

    public static function companyEmail(): ?string
    {
        return self::get('company_email');
    }

    public static function companyPhone(): ?string
    {
        return self::get('company_phone');
    }

    public static function timezone(): string
    {
        return self::get('timezone', 'Pacific/Tarawa');
    }

    public static function emailFrom(): string
    {
        return self::get('email_from_address', 'no-reply@bpa-app.net');
    }

    public static function emailFromName(): string
    {
        return self::get('email_from_name', 'BPA Asset Management');
    }
}
