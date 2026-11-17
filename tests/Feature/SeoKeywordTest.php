<?php

use App\Enums\KeywordContentType;
use App\Enums\KeywordIntent;
use App\Enums\KeywordStatus;
use App\Models\SeoKeyword;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
});

test('keyword scopes filter search and sort the notebook', function () {
    $core = SeoKeyword::factory()->create([
        'keyword' => 'interior painting melbourne',
        'cluster' => 'Core',
        'intent' => KeywordIntent::Commercial,
        'content_type' => KeywordContentType::ServicePage,
        'priority' => 1,
        'status' => KeywordStatus::Planned,
        'target_url' => '/services/interior-painting',
    ]);

    $location = SeoKeyword::factory()->create([
        'keyword' => 'painters balwyn',
        'cluster' => 'Location',
        'intent' => KeywordIntent::Local,
        'content_type' => KeywordContentType::LocationPage,
        'priority' => 3,
        'status' => KeywordStatus::InProgress,
    ]);

    $idea = SeoKeyword::factory()->create([
        'keyword' => 'how long does painting take',
        'cluster' => 'Guides',
        'intent' => KeywordIntent::Informational,
        'content_type' => KeywordContentType::BlogPost,
        'priority' => 2,
        'status' => KeywordStatus::Idea,
    ]);

    expect(SeoKeyword::query()->search('interior')->pluck('id')->all())->toBe([$core->id])
        ->and(SeoKeyword::query()->search('Location')->pluck('id')->all())->toBe([$location->id])
        ->and(SeoKeyword::query()->search('/services/interior')->pluck('id')->all())->toBe([$core->id])
        ->and(SeoKeyword::query()->status('planned')->pluck('id')->all())->toBe([$core->id])
        ->and(SeoKeyword::query()->priority(3)->pluck('id')->all())->toBe([$location->id])
        ->and(SeoKeyword::query()->cluster('Guides')->pluck('id')->all())->toBe([$idea->id])
        ->and(SeoKeyword::query()->intent('local')->pluck('id')->all())->toBe([$location->id])
        ->and(SeoKeyword::query()->contentType('blog_post')->pluck('id')->all())->toBe([$idea->id])
        ->and(SeoKeyword::query()->published()->count())->toBe(0)
        ->and(SeoKeyword::query()->plan()->orderBy('id')->pluck('id')->all())->toBe([$core->id, $location->id]);
});

test('plan view orders keywords by priority then target date with null dates last', function () {
    $late = SeoKeyword::factory()->create([
        'keyword' => 'late high priority',
        'priority' => 1,
        'status' => KeywordStatus::Planned,
        'target_date' => now()->addDays(20),
    ]);

    $soon = SeoKeyword::factory()->create([
        'keyword' => 'soon high priority',
        'priority' => 1,
        'status' => KeywordStatus::InProgress,
        'target_date' => now()->addDays(2),
    ]);

    $undated = SeoKeyword::factory()->create([
        'keyword' => 'undated high priority',
        'priority' => 1,
        'status' => KeywordStatus::Planned,
        'target_date' => null,
    ]);

    $low = SeoKeyword::factory()->create([
        'keyword' => 'low priority',
        'priority' => 3,
        'status' => KeywordStatus::Planned,
        'target_date' => now()->addDay(),
    ]);

    expect(SeoKeyword::query()->plan()->planOrdered()->pluck('id')->all())
        ->toBe([$soon->id, $late->id, $undated->id, $low->id]);
});

test('keyword sorting only accepts known columns', function () {
    SeoKeyword::factory()->create(['keyword' => 'zebra', 'priority' => 3]);
    SeoKeyword::factory()->create(['keyword' => 'alpha', 'priority' => 1]);

    expect(SeoKeyword::query()->sorted('priority', 'asc')->pluck('keyword')->all())->toBe(['alpha', 'zebra'])
        ->and(SeoKeyword::query()->sorted('priority', 'desc')->pluck('keyword')->all())->toBe(['zebra', 'alpha'])
        ->and(SeoKeyword::query()->sorted('not_a_column', 'weird')->pluck('keyword')->all())->toBe(['alpha', 'zebra']);
});

test('an admin can change a keyword status inline', function () {
    $keyword = SeoKeyword::factory()->create(['status' => KeywordStatus::Idea]);

    $this->actingAs($this->admin)
        ->patch(route('admin.seo.keywords.status', $keyword), ['status' => 'published'])
        ->assertRedirect();

    expect($keyword->refresh()->status)->toBe(KeywordStatus::Published);

    $this->actingAs($this->admin)
        ->patch(route('admin.seo.keywords.status', $keyword), ['status' => 'not-a-status'])
        ->assertSessionHasErrors('status');
});

test('an admin can bulk change keyword statuses', function () {
    $first = SeoKeyword::factory()->create(['status' => KeywordStatus::Idea]);
    $second = SeoKeyword::factory()->create(['status' => KeywordStatus::Idea]);
    $untouched = SeoKeyword::factory()->create(['status' => KeywordStatus::Idea]);

    $this->actingAs($this->admin)
        ->patch(route('admin.seo.keywords.bulk-status'), [
            'ids' => [$first->id, $second->id],
            'status' => 'planned',
        ])
        ->assertRedirect();

    expect($first->refresh()->status)->toBe(KeywordStatus::Planned)
        ->and($second->refresh()->status)->toBe(KeywordStatus::Planned)
        ->and($untouched->refresh()->status)->toBe(KeywordStatus::Idea);
});
