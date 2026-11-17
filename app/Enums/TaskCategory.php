<?php

namespace App\Enums;

enum TaskCategory: string
{
    case Tracking = 'tracking';
    case Technical = 'technical';
    case OnPage = 'on_page';
    case Content = 'content';
    case Local = 'local';
    case Links = 'links';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Tracking => 'ردیابی',
            self::Technical => 'فنی',
            self::OnPage => 'سئوی داخلی',
            self::Content => 'محتوا',
            self::Local => 'محلی',
            self::Links => 'لینک‌سازی',
            self::Other => 'سایر',
        };
    }
}
