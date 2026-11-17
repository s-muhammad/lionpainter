<?php

namespace App\Enums;

enum KeywordIntent: string
{
    case Informational = 'informational';
    case Commercial = 'commercial';
    case Transactional = 'transactional';
    case Local = 'local';

    public function label(): string
    {
        return match ($this) {
            self::Informational => 'اطلاع‌رسان',
            self::Commercial => 'تجاری',
            self::Transactional => 'تراکنشی',
            self::Local => 'محلی',
        };
    }
}
