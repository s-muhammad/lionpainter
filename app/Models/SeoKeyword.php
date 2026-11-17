<?php

namespace App\Models;

use App\Enums\KeywordContentType;
use App\Enums\KeywordDifficulty;
use App\Enums\KeywordIntent;
use App\Enums\KeywordStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeoKeyword extends Model
{
    use HasFactory;

    /**
     * Columns that may be used for table sorting.
     */
    public const SORTABLE = [
        'keyword', 'cluster', 'intent', 'content_type', 'target_url',
        'priority', 'status', 'current_position', 'target_date', 'search_volume',
    ];

    protected $fillable = [
        'keyword', 'cluster', 'intent', 'content_type', 'target_url', 'priority',
        'status', 'difficulty', 'search_volume', 'current_position', 'target_date', 'notes',
    ];

    protected $casts = [
        'intent' => KeywordIntent::class,
        'content_type' => KeywordContentType::class,
        'status' => KeywordStatus::class,
        'difficulty' => KeywordDifficulty::class,
        'priority' => 'integer',
        'search_volume' => 'integer',
        'current_position' => 'integer',
        'target_date' => 'date',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(SeoTask::class, 'keyword_id');
    }

    /**
     * Raw string data used by the Blade edit form.
     */
    public function toFormArray(): array
    {
        return [
            'id' => $this->id,
            'keyword' => $this->keyword,
            'cluster' => $this->cluster,
            'intent' => $this->intent->value,
            'content_type' => $this->content_type->value,
            'target_url' => $this->target_url,
            'priority' => $this->priority,
            'status' => $this->status->value,
            'difficulty' => $this->difficulty?->value,
            'search_volume' => $this->search_volume,
            'current_position' => $this->current_position,
            'target_date' => $this->target_date?->toDateString(),
            'notes' => $this->notes,
        ];
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->where('keyword', 'like', "%{$term}%")
                ->orWhere('cluster', 'like', "%{$term}%")
                ->orWhere('target_url', 'like', "%{$term}%");
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(filled($status), fn (Builder $query) => $query->where('status', $status));
    }

    public function scopePriority(Builder $query, int|string|null $priority): Builder
    {
        return $query->when(filled($priority), fn (Builder $query) => $query->where('priority', (int) $priority));
    }

    public function scopeCluster(Builder $query, ?string $cluster): Builder
    {
        return $query->when(filled($cluster), fn (Builder $query) => $query->where('cluster', $cluster));
    }

    public function scopeIntent(Builder $query, ?string $intent): Builder
    {
        return $query->when(filled($intent), fn (Builder $query) => $query->where('intent', $intent));
    }

    public function scopeContentType(Builder $query, ?string $contentType): Builder
    {
        return $query->when(filled($contentType), fn (Builder $query) => $query->where('content_type', $contentType));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', KeywordStatus::Published->value);
    }

    /**
     * Keywords that make up the content plan.
     */
    public function scopePlan(Builder $query): Builder
    {
        return $query->whereIn('status', [
            KeywordStatus::Planned->value,
            KeywordStatus::InProgress->value,
        ]);
    }

    public function scopeSorted(Builder $query, ?string $column, ?string $direction = 'asc'): Builder
    {
        $column = in_array($column, self::SORTABLE, true) ? $column : 'keyword';
        $direction = $direction === 'desc' ? 'desc' : 'asc';

        return $query->orderBy($column, $direction)->orderBy('id');
    }

    /**
     * Content-plan order: most important first, then the nearest target date.
     */
    public function scopePlanOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('priority')
            ->orderByRaw('case when target_date is null then 1 else 0 end')
            ->orderBy('target_date')
            ->orderBy('id');
    }
}
