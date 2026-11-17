<?php

use App\Enums\TaskCategory;
use App\Enums\TaskStatus;
use App\Models\SeoTask;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
});

test('task scopes filter by status category priority phase and overdue state', function () {
    $overdue = SeoTask::factory()->overdue()->create([
        'phase' => 'هفته ۱',
        'category' => TaskCategory::Content,
        'priority' => 1,
    ]);

    $done = SeoTask::factory()->done()->create([
        'phase' => 'هفته ۱',
        'category' => TaskCategory::Content,
        'priority' => 1,
        'due_date' => now()->subWeek()->toDateString(),
    ]);

    $later = SeoTask::factory()->create([
        'phase' => 'ماه ۱',
        'category' => TaskCategory::Technical,
        'priority' => 3,
        'due_date' => now()->addWeek()->toDateString(),
    ]);

    expect(SeoTask::query()->status('todo')->pluck('id')->all())->toContain($overdue->id, $later->id)
        ->and(SeoTask::query()->category('content')->pluck('id')->all())->toBe([$overdue->id, $done->id])
        ->and(SeoTask::query()->priority(3)->pluck('id')->all())->toBe([$later->id])
        ->and(SeoTask::query()->phase('ماه ۱')->pluck('id')->all())->toBe([$later->id])
        ->and(SeoTask::query()->overdue()->pluck('id')->all())->toBe([$overdue->id])
        ->and($overdue->isOverdue())->toBeTrue()
        ->and($done->isOverdue())->toBeFalse()
        ->and($later->isOverdue())->toBeFalse();
});

test('ordered scope sorts by sort order and id', function () {
    $second = SeoTask::factory()->create(['sort_order' => 20]);
    $first = SeoTask::factory()->create(['sort_order' => 0]);

    expect(SeoTask::query()->ordered()->pluck('id')->all())->toBe([$first->id, $second->id]);
});

test('toggling a task sets and clears completed_at', function () {
    $task = SeoTask::factory()->create(['status' => TaskStatus::Todo, 'completed_at' => null]);

    $this->actingAs($this->admin)
        ->patch(route('admin.seo.tasks.toggle', $task))
        ->assertRedirect();

    $task->refresh();

    expect($task->status)->toBe(TaskStatus::Done)
        ->and($task->completed_at)->not->toBeNull();

    $this->actingAs($this->admin)
        ->patch(route('admin.seo.tasks.toggle', $task))
        ->assertRedirect();

    $task->refresh();

    expect($task->status)->toBe(TaskStatus::Todo)
        ->and($task->completed_at)->toBeNull();
});

test('updating task status keeps completed_at in sync', function () {
    $task = SeoTask::factory()->create([
        'title' => 'Review meta titles',
        'category' => TaskCategory::OnPage,
        'priority' => 1,
        'status' => TaskStatus::Todo,
    ]);

    $payload = [
        'title' => $task->title,
        'category' => TaskCategory::OnPage->value,
        'priority' => 1,
        'phase' => 'هفته ۱',
        'sort_order' => 2,
        'status' => 'done',
    ];

    $this->actingAs($this->admin)
        ->put(route('admin.seo.tasks.update', $task), $payload)
        ->assertRedirect();

    $task->refresh();

    expect($task->status)->toBe(TaskStatus::Done)
        ->and($task->completed_at)->not->toBeNull();

    $payload['status'] = 'doing';

    $this->actingAs($this->admin)
        ->put(route('admin.seo.tasks.update', $task), $payload)
        ->assertRedirect();

    $task->refresh();

    expect($task->status)->toBe(TaskStatus::Doing)
        ->and($task->completed_at)->toBeNull();
});

test('creating a done task records its completion time', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.seo.tasks.store'), [
            'title' => 'Already completed task',
            'category' => 'technical',
            'priority' => 2,
            'status' => 'done',
        ])
        ->assertRedirect();

    $task = SeoTask::query()->where('title', 'Already completed task')->firstOrFail();

    expect($task->status)->toBe(TaskStatus::Done)
        ->and($task->completed_at)->not->toBeNull();
});
