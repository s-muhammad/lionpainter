<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    public const STATUSES = [
        'new' => 'جدید',
        'contacted' => 'تماس گرفته شده',
        'quoted' => 'قیمت ارسال شده',
        'won' => 'تبدیل به پروژه',
        'closed' => 'بسته شده',
    ];

    protected $fillable = [
        'name', 'email', 'phone', 'suburb', 'service', 'property_type', 'bedrooms',
        'timeline', 'details', 'status', 'admin_notes',
    ];

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
