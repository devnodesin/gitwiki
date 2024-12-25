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
    function set_setting(string $key, mixed $value, bool $edit = true): void
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

if (! function_exists('get_footer')) {
    function get_footer(): string
    {
        $defaultFooter = 'Copyright &copy; 2024 <a target=\'_blank\' class=\'link-dark link-offset-2\' href=\'https://github.com/devnodesin/gitwiki\'>Git Wiki an Open Source by Devnodes.in</a>';

        $footerText = get_setting('footer_copyright');

        return empty($footerText) ? $defaultFooter : $footerText;
    }
}
