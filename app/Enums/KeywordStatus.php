<?php

namespace App\Enums;

enum KeywordStatus: string
{
    case Idea = 'idea';
    case Planned = 'planned';
    case InProgress = 'in_progress';
    case Published = 'published';
    case NeedsUpdate = 'needs_update';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match ($this) {
            self::Idea => 'ایده',
            self::Planned => 'برنامه‌ریزی‌شده',
            self::InProgress => 'در حال انجام',
            self::Published => 'منتشرشده',
            self::NeedsUpdate => 'نیاز به به‌روزرسانی',
            self::Skipped => 'ردشده',
        };
    }
}
