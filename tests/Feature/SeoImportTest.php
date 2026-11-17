<?php

use App\Enums\KeywordStatus;
use App\Enums\TaskStatus;
use App\Models\SeoKeyword;
use App\Models\SeoTask;
use App\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
});

function seoCsvFile(string $content, string $name = 'import.csv'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($name, $content);
}

test('keyword CSV import creates new rows and updates existing keywords', function () {
    $existing = SeoKeyword::factory()->create([
        'keyword' => 'interior painting melbourne',
        'cluster' => 'Old',
        'priority' => 3,
        'status' => KeywordStatus::Idea,
    ]);

    $csv = implode("\n", [
        'keyword,cluster,intent,content_type,target_url,priority,status,difficulty,search_volume,current_position,target_date,notes',
        'interior painting melbourne,Core,commercial,service_page,/services/interior-painting,1,published,low,320,4,2026-12-01,Updated note',
        'exterior painting melbourne,Core,commercial,service_page,/services/exterior-painting,2,planned,medium,120,,,',
    ])."\n";

    $this->actingAs($this->admin)
        ->from(route('admin.seo.index', ['tab' => 'keywords']))
        ->post(route('admin.seo.keywords.import'), ['file' => seoCsvFile($csv, 'keywords.csv')])
        ->assertRedirect();

    $result = session('import_result');

    expect($result)->not->toBeNull()
        ->and($result['created'])->toBe(1)
        ->and($result['updated'])->toBe(1)
        ->and($result['skipped'])->toBe(0)
        ->and(SeoKeyword::query()->count())->toBe(2);

    $existing->refresh();

    expect($existing->cluster)->toBe('Core')
        ->and($existing->priority)->toBe(1)
        ->and($existing->status)->toBe(KeywordStatus::Published)
        ->and($existing->search_volume)->toBe(320)
        ->and($existing->current_position)->toBe(4)
        ->and($existing->target_date->toDateString())->toBe('2026-12-01')
        ->and($existing->notes)->toBe('Updated note');
});

test('keyword CSV import upserts duplicate rows and skips invalid rows', function () {
    $csv = implode("\n", [
        'keyword,cluster,intent,content_type,priority,status',
        'duplicate keyword,Core,commercial,blog_post,1,idea',
        'duplicate keyword,Location,local,blog_post,2,planned',
        ',Core,commercial,blog_post,2,idea',
        'bad intent,Core,not-an-intent,blog_post,2,idea',
        'bad priority,Core,commercial,blog_post,9,idea',
    ])."\n";

    $this->actingAs($this->admin)
        ->from(route('admin.seo.index', ['tab' => 'keywords']))
        ->post(route('admin.seo.keywords.import'), ['file' => seoCsvFile($csv)])
        ->assertRedirect();

    $result = session('import_result');

    expect($result['created'])->toBe(1)
        ->and($result['updated'])->toBe(1)
        ->and($result['skipped'])->toBe(3)
        ->and($result['skipped_rows'])->toHaveCount(3)
        ->and(array_column($result['skipped_rows'], 'row'))->toBe([4, 5, 6])
        ->and(SeoKeyword::query()->count())->toBe(1);

    $keyword = SeoKeyword::query()->where('keyword', 'duplicate keyword')->firstOrFail();

    expect($keyword->cluster)->toBe('Location')
        ->and($keyword->intent->value)->toBe('local')
        ->and($keyword->status)->toBe(KeywordStatus::Planned)
        ->and($keyword->priority)->toBe(2);

    $reasons = implode(' ', array_column($result['skipped_rows'], 'reason'));

    expect($reasons)->toContain('کلیدواژه')
        ->and($reasons)->toContain('قصد جستجو')
        ->and($reasons)->toContain('اولویت');
});

test('keyword CSV import fails clearly when required headers are missing', function () {
    $csv = "title,category\nSome task,content\n";

    $this->actingAs($this->admin)
        ->from(route('admin.seo.index', ['tab' => 'keywords']))
        ->post(route('admin.seo.keywords.import'), ['file' => seoCsvFile($csv)])
        ->assertRedirect()
        ->assertSessionHas('import_error');

    expect(SeoKeyword::query()->count())->toBe(0)
        ->and(session('import_error'))->toContain('keyword');
});

test('task CSV import links keywords skips invalid rows and records warnings', function () {
    $keyword = SeoKeyword::factory()->create(['keyword' => 'interior painting melbourne']);

    $csv = implode("\n", [
        'title,category,priority,status,phase,due_date,keyword,notes,sort_order,completed_at',
        'Audit meta titles,on_page,1,done,هفته ۱,2026-11-20,interior painting melbourne,,0,',
        'Fix sitemap,technical,2,todo,هفته ۱,,missing keyword,,1,',
        'Broken task,not-a-category,2,todo,هفته ۲,,, ,2,',
    ])."\n";

    $this->actingAs($this->admin)
        ->from(route('admin.seo.index', ['tab' => 'tasks']))
        ->post(route('admin.seo.tasks.import'), ['file' => seoCsvFile($csv, 'tasks.csv')])
        ->assertRedirect();

    $result = session('import_result');

    expect($result['created'])->toBe(2)
        ->and($result['updated'])->toBe(0)
        ->and($result['skipped'])->toBe(1)
        ->and($result['warnings'])->toHaveCount(1)
        ->and(SeoTask::query()->count())->toBe(2);

    $linked = SeoTask::query()->where('title', 'Audit meta titles')->firstOrFail();
    $unlinked = SeoTask::query()->where('title', 'Fix sitemap')->firstOrFail();

    expect($linked->keyword_id)->toBe($keyword->id)
        ->and($linked->status)->toBe(TaskStatus::Done)
        ->and($linked->completed_at)->not->toBeNull()
        ->and($linked->due_date->toDateString())->toBe('2026-11-20')
        ->and($unlinked->keyword_id)->toBeNull();

    expect($result['warnings'][0]['reason'])->toContain('missing keyword');
});

test('keywords and tasks can be exported as CSV', function () {
    $keyword = SeoKeyword::factory()->create([
        'keyword' => 'exported keyword',
        'cluster' => 'Core',
    ]);

    $task = SeoTask::factory()->create([
        'title' => 'Exported task',
        'keyword_id' => $keyword->id,
    ]);

    $keywordResponse = $this->actingAs($this->admin)->get(route('admin.seo.keywords.export'));
    $keywordResponse->assertOk();

    $keywordCsv = $keywordResponse->streamedContent();

    expect($keywordCsv)->toContain('keyword,cluster,intent,content_type,target_url,priority,status')
        ->toContain('exported keyword');

    $taskResponse = $this->actingAs($this->admin)->get(route('admin.seo.tasks.export'));
    $taskResponse->assertOk();

    $taskCsv = $taskResponse->streamedContent();

    expect($taskCsv)->toContain('title,category,priority,status,phase,due_date,keyword')
        ->toContain('Exported task')
        ->toContain('exported keyword');
});
