<?php

use App\Enums\KeywordStatus;
use App\Enums\TaskStatus;
use App\Models\SeoKeyword;
use App\Models\SeoTask;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
});

test('guests are redirected and non-admins cannot access the SEO notebook', function () {
    $this->get(route('admin.seo.index'))->assertRedirect(route('admin.login'));

    $this->actingAs(User::factory()->create(['is_admin' => false]))
        ->get(route('admin.seo.index'))
        ->assertForbidden();
});

test('the SEO notebook renders both tabs plan view and the summary strip', function () {
    SeoKeyword::factory()->published()->create();
    SeoTask::factory()->overdue()->create(['phase' => null]);
    SeoTask::factory()->done()->create(['phase' => 'هفته ۱']);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index'))
        ->assertOk()
        ->assertSee('دفترچه سئو')
        ->assertSee('فهرست کلیدواژه‌ها')
        ->assertSee('کلیدواژه‌های منتشرشده')
        ->assertSee('کارهای عقب‌افتاده')
        ->assertSee('1 / 2');

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'tasks']))
        ->assertOk()
        ->assertSee('چک‌لیست کارها')
        ->assertSee('بدون مرحله');

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'keywords', 'k_view' => 'plan']))
        ->assertOk()
        ->assertSee('برنامه محتوا')
        ->assertSee('برنامه‌ریزی‌شده')
        ->assertSee('در حال انجام');
});

test('the graph tab renders the keyword graph and its brief panel', function () {
    $painting = SeoKeyword::factory()->create([
        'keyword' => 'interior painting melbourne',
        'status' => KeywordStatus::Published,
        'priority' => 1,
        'target_url' => '/services/interior-painting',
        'current_position' => 7,
    ]);

    SeoKeyword::factory()->create([
        'keyword' => 'exterior painting melbourne',
        'priority' => 2,
        'target_url' => '/services/exterior-painting',
    ]);

    SeoKeyword::factory()->create(['keyword' => 'unrelated keyword', 'status' => KeywordStatus::Idea]);

    SeoTask::factory()->create(['title' => 'Graph brief task', 'keyword_id' => $painting->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'graph']))
        ->assertOk()
        ->assertSee('گراف کلمات کلیدی')
        ->assertSee('بریف کلمه کلیدی')
        ->assertSee('کلمات در گراف')
        ->assertSee('ارتباط متنی')
        ->assertSee('اولویت بالا')
        ->assertSee('منتشرشده')
        ->assertSee('interior painting melbourne')
        ->assertSee('weight', false)
        ->assertSee('Graph brief task')
        ->assertSee('/services/interior-painting')
        ->assertSee('data-graph', false)
        ->assertDontSee('کلیدواژه‌های منتشرشده');
});

test('graph filters narrow the keywords shown as nodes', function () {
    SeoKeyword::factory()->create([
        'keyword' => 'graph alpha painters',
        'status' => KeywordStatus::Published,
        'target_url' => '/alpha',
        'priority' => 1,
    ]);

    SeoKeyword::factory()->create([
        'keyword' => 'graph beta painters',
        'status' => KeywordStatus::Idea,
        'target_url' => '/beta',
        'priority' => 3,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'graph', 'g_status' => 'published']))
        ->assertOk()
        ->assertSee('graph alpha painters')
        ->assertDontSee('graph beta painters');

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'graph', 'g_priority' => '3']))
        ->assertOk()
        ->assertSee('graph beta painters')
        ->assertDontSee('graph alpha painters');

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'graph', 'g_target' => '/alpha']))
        ->assertOk()
        ->assertSee('graph alpha painters')
        ->assertDontSee('graph beta painters');

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'graph', 'g_search' => 'beta']))
        ->assertOk()
        ->assertSee('graph beta painters')
        ->assertDontSee('graph alpha painters');
});

test('an empty graph falls back to the graph tab with a filter reset link and unknown tabs open keywords', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'graph']))
        ->assertOk()
        ->assertSee('کلیدواژه‌ای برای نمایش در گراف پیدا نشد.');

    SeoKeyword::factory()->create(['keyword' => 'visible keyword']);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'unknown']))
        ->assertOk()
        ->assertSee('فهرست کلیدواژه‌ها')
        ->assertSee('visible keyword');
});

test('keyword filters and the plan view use the query string', function () {
    $core = SeoKeyword::factory()->create([
        'keyword' => 'core keyword',
        'cluster' => 'Core',
        'status' => KeywordStatus::Planned,
        'priority' => 2,
    ]);

    $location = SeoKeyword::factory()->create([
        'keyword' => 'location keyword',
        'cluster' => 'Location',
        'status' => KeywordStatus::Planned,
        'priority' => 1,
        'target_date' => now()->addDay(),
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'keywords', 'k_cluster' => 'Core']))
        ->assertOk()
        ->assertSee($core->keyword)
        ->assertDontSee($location->keyword);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'keywords', 'k_search' => 'location']))
        ->assertOk()
        ->assertSee($location->keyword)
        ->assertDontSee($core->keyword);

    $this->actingAs($this->admin)
        ->get(route('admin.seo.index', ['tab' => 'keywords', 'k_view' => 'plan']))
        ->assertOk()
        ->assertSeeInOrder([$location->keyword, $core->keyword]);
});

test('an admin can create update and delete a keyword', function () {
    $payload = [
        'keyword' => 'brand new keyword',
        'cluster' => 'Core',
        'intent' => 'commercial',
        'content_type' => 'service_page',
        'target_url' => '/services/brand-new',
        'priority' => 1,
        'status' => 'planned',
        'difficulty' => 'medium',
        'search_volume' => 450,
        'current_position' => 12,
        'target_date' => '2026-12-15',
        'notes' => 'A keyword note.',
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.seo.keywords.store'), $payload)
        ->assertRedirect();

    $keyword = SeoKeyword::query()->where('keyword', 'brand new keyword')->firstOrFail();

    expect($keyword->status)->toBe(KeywordStatus::Planned)
        ->and($keyword->search_volume)->toBe(450)
        ->and($keyword->target_date->toDateString())->toBe('2026-12-15');

    $payload['priority'] = 3;
    $payload['status'] = 'published';

    $this->actingAs($this->admin)
        ->put(route('admin.seo.keywords.update', $keyword), $payload)
        ->assertRedirect();

    expect($keyword->refresh()->priority)->toBe(3)
        ->and($keyword->status)->toBe(KeywordStatus::Published);

    $this->actingAs($this->admin)
        ->delete(route('admin.seo.keywords.destroy', $keyword))
        ->assertRedirect();

    expect(SeoKeyword::query()->whereKey($keyword->id)->exists())->toBeFalse();
});

test('keyword validation rejects duplicate keywords and invalid enums', function () {
    SeoKeyword::factory()->create(['keyword' => 'existing keyword']);

    $this->actingAs($this->admin)
        ->from(route('admin.seo.index'))
        ->post(route('admin.seo.keywords.store'), [
            'keyword' => 'existing keyword',
            'intent' => 'invalid-intent',
            'content_type' => 'blog_post',
            'priority' => 2,
            'status' => 'idea',
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['keyword', 'intent']);
});

test('an admin can quick-add edit and delete tasks', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.seo.tasks.store'), [
            '_modal' => 'task-quick',
            'title' => 'Quick task',
            'category' => 'content',
            'priority' => 2,
        ])
        ->assertRedirect();

    $task = SeoTask::query()->where('title', 'Quick task')->firstOrFail();

    expect($task->status)->toBe(TaskStatus::Todo)
        ->and($task->completed_at)->toBeNull()
        ->and($task->sort_order)->toBe(0);

    $this->actingAs($this->admin)
        ->put(route('admin.seo.tasks.update', $task), [
            'title' => 'Quick task edited',
            'category' => 'on_page',
            'priority' => 1,
            'status' => 'done',
            'phase' => 'هفته ۱',
            'due_date' => '2026-12-01',
            'sort_order' => 5,
        ])
        ->assertRedirect();

    $task->refresh();

    expect($task->title)->toBe('Quick task edited')
        ->and($task->status)->toBe(TaskStatus::Done)
        ->and($task->completed_at)->not->toBeNull()
        ->and($task->phase)->toBe('هفته ۱');

    $this->actingAs($this->admin)
        ->delete(route('admin.seo.tasks.destroy', $task))
        ->assertRedirect();

    expect(SeoTask::query()->whereKey($task->id)->exists())->toBeFalse();
});

test('non-admin users cannot change SEO records', function () {
    $keyword = SeoKeyword::factory()->create(['status' => KeywordStatus::Idea]);
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->patch(route('admin.seo.keywords.status', $keyword), ['status' => 'published'])
        ->assertForbidden();

    expect($keyword->refresh()->status)->toBe(KeywordStatus::Idea);
});
