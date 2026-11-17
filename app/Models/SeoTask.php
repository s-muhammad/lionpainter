<?php

namespace App\Models;

use App\Enums\TaskCategory;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'priority', 'status', 'phase', 'due_date',
        'keyword_id', 'notes', 'sort_order', 'completed_at',
    ];

    protected $casts = [
        'category' => TaskCategory::class,
        'status' => TaskStatus::class,
        'priority' => 'integer',
        'due_date' => 'date',
        'sort_order' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(SeoKeyword::class, 'keyword_id');
    }

    /**
     * Raw string data used by the Blade edit form.
     */
    public function toFormArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category->value,
            'priority' => $this->priority,
            'status' => $this->status->value,
            'phase' => $this->phase,
            'due_date' => $this->due_date?->toDateString(),
            'keyword_id' => $this->keyword_id,
            'notes' => $this->notes,
            'sort_order' => $this->sort_order,
        ];
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when(filled($status), fn (Builder $query) => $query->where('status', $status));
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        return $query->when(filled($category), fn (Builder $query) => $query->where('category', $category));
    }

    public function scopePriority(Builder $query, int|string|null $priority): Builder
    {
        return $query->when(filled($priority), fn (Builder $query) => $query->where('priority', (int) $priority));
    }

    public function scopePhase(Builder $query, ?string $phase): Builder
    {
        return $query->when(filled($phase), fn (Builder $query) => $query->where('phase', $phase));
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today())
            ->where('status', '!=', TaskStatus::Done->value);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function isOverdue(): bool
    {
        return $this->status !== TaskStatus::Done
            && $this->due_date !== null
            && $this->due_date->lt(today());
    }

    public function isDone(): bool
    {
        return $this->status === TaskStatus::Done;
    }

    /**
     * Keep status and completed_at in sync wherever a task is changed.
     */
    public function changeStatus(TaskStatus $status): void
    {
        $this->status = $status;
        $this->completed_at = $status === TaskStatus::Done
            ? ($this->completed_at ?? now())
            : null;

        $this->save();
    }

    public function toggleDone(): void
    {
        $this->changeStatus($this->isDone() ? TaskStatus::Todo : TaskStatus::Done);
    }

    public function markDone(): void
    {
        $this->changeStatus(TaskStatus::Done);
    }

    public function markUndone(): void
    {
        $this->changeStatus(TaskStatus::Todo);
    }
}
