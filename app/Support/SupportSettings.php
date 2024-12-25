<?php

use App\Models\Settings;

if (! function_exists('get_setting')) {
    function get_setting(string $key, mixed $default = null): mixed
    {
        $setting = Settings::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        return $setting->value ?? $default;
    }
}

if (! function_exists('set_setting')) {
    function set_setting(string $key, mixed $value, bool $edit = false): void
    {
        Settings::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'edit' => $edit,
            ]
        );
    }
}
