<?php

namespace App\Enums;

enum KeywordContentType: string
{
    case Home = 'home';
    case ServicePage = 'service_page';
    case LocationPage = 'location_page';
    case BlogPost = 'blog_post';
    case Portfolio = 'portfolio';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Home => 'صفحه اصلی',
            self::ServicePage => 'صفحه خدمت',
            self::LocationPage => 'صفحه منطقه',
            self::BlogPost => 'مقاله وبلاگ',
            self::Portfolio => 'نمونه‌کار',
            self::Other => 'سایر',
        };
    }
}
