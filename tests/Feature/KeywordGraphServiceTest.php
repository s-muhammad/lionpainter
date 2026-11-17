<?php

use App\Enums\KeywordStatus;
use App\Enums\TaskStatus;
use App\Models\SeoKeyword;
use App\Models\SeoTask;
use App\Services\Seo\KeywordGraphService;

test('keyword tokens drop stop words punctuation and persian spelling variants', function () {
    $service = new KeywordGraphService;

    expect($service->tokens('نقاشی ساختمان، در تهران!'))
        ->toBe(['نقاشی', 'ساختمان', 'تهران'])
        ->and($service->tokens('پلاستيك'))
        ->toBe(['پلاستیک'])
        ->and($service->tokens('رنگ‌آمیزی'))
        ->toBe(['رنگ', 'امیزی'])
        ->and($service->tokens('برای با از'))
        ->toBe([]);
});

test('the graph links keywords that share meaningful words', function () {
    $interior = SeoKeyword::factory()->create([
        'keyword' => 'interior painting melbourne',
        'priority' => 1,
        'status' => KeywordStatus::Planned,
        'target_url' => '/services/interior',
    ]);

    $exterior = SeoKeyword::factory()->create([
        'keyword' => 'exterior painting melbourne',
        'priority' => 2,
        'status' => KeywordStatus::Published,
        'target_url' => '/services/exterior',
    ]);

    $unrelated = SeoKeyword::factory()->create([
        'keyword' => 'completely unrelated phrase',
    ]);

    SeoTask::factory()->create([
        'title' => 'Brief task',
        'keyword_id' => $interior->id,
        'status' => TaskStatus::Todo,
    ]);

    $graph = (new KeywordGraphService)->build(
        SeoKeyword::query()->with('tasks')->orderBy('priority')->get(),
    );

    expect($graph['edges'])->toHaveCount(1)
        ->and($graph['edges'][0]['source'])->toBe($interior->id)
        ->and($graph['edges'][0]['target'])->toBe($exterior->id)
        ->and($graph['edges'][0]['weight'])->toBe(2);

    $nodes = collect($graph['nodes'])->keyBy('id');

    expect($nodes[$interior->id]['statusLabel'])->toBe('برنامه‌ریزی‌شده')
        ->and($nodes[$interior->id]['priorityLabel'])->toBe('بالا')
        ->and($nodes[$interior->id]['groups']['target'])->toBe('/services/interior')
        ->and($nodes[$interior->id]['tasksCount'])->toBe(1)
        ->and($nodes[$interior->id]['tasks'][0]['title'])->toBe('Brief task')
        ->and($nodes[$interior->id]['degree'])->toBe(1)
        ->and($nodes[$unrelated->id]['degree'])->toBe(0);
});

test('the graph keeps only the strongest links per keyword', function () {
    $hub = SeoKeyword::factory()->create(['keyword' => 'melbourne house painters']);

    foreach (range(1, 8) as $index) {
        SeoKeyword::factory()->create(['keyword' => "melbourne painters service {$index}"]);
    }

    $graph = (new KeywordGraphService)->build(SeoKeyword::query()->get());

    $connected = collect($graph['edges'])
        ->filter(fn (array $edge) => $edge['source'] === $hub->id || $edge['target'] === $hub->id);

    expect($connected)->toHaveCount(4);
});
