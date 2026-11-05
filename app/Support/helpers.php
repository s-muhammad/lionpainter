<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Retrieve a site setting, falling back to the values in config/site.php.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        $fallback = config("site.defaults.{$key}");

        return Setting::get($key, $fallback ?? $default);
    }
}

if (! function_exists('setting_bool')) {
    function setting_bool(string $key, bool $default = false): bool
    {
        return filter_var(setting($key, $default), FILTER_VALIDATE_BOOLEAN);
    }
}

if (! function_exists('asset_versioned')) {
    /**
     * Version a public asset path using its modification time.
     */
    function asset_versioned(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        $url = str_starts_with($path, 'http') ? $path : asset($path);
        $file = public_path(ltrim(parse_url($path, PHP_URL_PATH) ?? $path, '/'));

        return is_file($file) ? $url.'?v='.filemtime($file) : $url;
    }
}

if (! function_exists('money')) {
    function money(float $amount): string
    {
        return '$'.number_format($amount, 0, '.', ',');
    }
}
