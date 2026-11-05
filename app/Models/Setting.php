<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const CACHE_KEY = 'lion.settings';

    public static function allValues(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return self::query()->pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $values = self::allValues();

        return array_key_exists($key, $values) && $values[$key] !== null && $values[$key] !== ''
            ? $values[$key]
            : $default;
    }

    public static function put(string $key, mixed $value): void
    {
        self::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
