<?php

namespace App\Services\Seo;

use App\Enums\KeywordStatus;
use App\Models\SeoKeyword;
use App\Models\SeoTask;
use Illuminate\Support\Collection;

/**
 * Builds the keyword relationship graph shown in the SEO notebook.
 *
 * Nodes are keywords; edges connect keywords whose phrases share a
 * meaningful word (textual relationship).
 */
class KeywordGraphService
{
    /**
     * Hard cap so the browser never has to render an unbounded graph.
     */
    public const MAX_NODES = 200;

    private const MAX_EDGES_PER_NODE = 4;

    /**
     * Very common words stop being a connection: if a word appears in more
     * keywords than this, it is treated as noise for that token.
     */
    private const MAX_TOKEN_GROUP = 24;

    private const STOP_WORDS = [
        'و', 'در', 'به', 'از', 'با', 'برای', 'یا', 'که', 'این', 'آن', 'را', 'می',
        'ها', 'های', 'یک', 'تا', 'بر', 'است', 'بود', 'شد', 'شده', 'شدن', 'هم',
        'اما', 'اگر', 'پس', 'چه', 'هر', 'همه', 'نیز', 'دیگر', 'شود', 'کرد', 'کند',
        'کنید', 'کنم', 'کن', 'هست', 'نیست', 'خود', 'روی', 'بین', 'نزد', 'طی',
        'فقط', 'بیش', 'کم', 'کدام', 'چگونه', 'چرا', 'کجا', 'کی', 'آیا', 'نه',
        'بله', 'او', 'ما', 'شما', 'آنها', 'ایشان', 'من', 'تو', 'وی', 'the', 'and',
        'for', 'with', 'from', 'near', 'best', 'how', 'what', 'why', 'when', 'your',
    ];

    private const STATUS_TONES = [
        'idea' => ['bg' => '#f1f5f9', 'text' => '#475569'],
        'planned' => ['bg' => '#fdf6e7', 'text' => '#a9863f'],
        'in_progress' => ['bg' => '#e0f2fe', 'text' => '#0369a1'],
        'published' => ['bg' => '#ecfdf5', 'text' => '#047857'],
        'needs_update' => ['bg' => '#fff7ed', 'text' => '#c2410c'],
        'skipped' => ['bg' => '#f8fafc', 'text' => '#94a3b8'],
    ];

    private const PRIORITY_TEXT = [
        1 => '#dc2626',
        2 => '#b45309',
        3 => '#64748b',
    ];

    /**
     * @param  Collection<int, SeoKeyword>  $keywords
     * @return array{nodes: array<int, array<string, mixed>>, edges: array<int, array{source: int, target: int, weight: int}>}
     */
    public function build(Collection $keywords): array
    {
        $keywords = $keywords->take(self::MAX_NODES)->values();

        $edges = $this->edges($keywords);
        $degree = [];

        foreach ($edges as $edge) {
            $degree[$edge['source']] = ($degree[$edge['source']] ?? 0) + 1;
            $degree[$edge['target']] = ($degree[$edge['target']] ?? 0) + 1;
        }

        $nodes = $keywords
            ->map(fn (SeoKeyword $keyword) => $this->node($keyword, $degree[$keyword->id] ?? 0))
            ->all();

        return [
            'nodes' => $nodes,
            'edges' => $edges,
        ];
    }

    /**
     * Meaningful words of a keyword phrase.
     *
     * @return array<int, string>
     */
    public function tokens(string $keyword): array
    {
        $value = str_replace(['ي', 'ك', 'ۀ', 'ة', 'أ', 'إ', 'آ', 'ٱ'], ['ی', 'ک', 'ه', 'ه', 'ا', 'ا', 'ا', 'ا'], $keyword);
        $value = str_replace("\u{200c}", ' ', $value);
        $value = mb_strtolower($value, 'UTF-8');
        $value = (string) preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value);

        $words = preg_split('/\s+/u', trim($value)) ?: [];

        return collect($words)
            ->map(fn (string $word) => trim($word))
            ->filter(fn (string $word) => mb_strlen($word) >= 2 && ! in_array($word, self::STOP_WORDS, true))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, SeoKeyword>  $keywords
     * @return array<int, array{source: int, target: int, weight: int}>
     */
    private function edges(Collection $keywords): array
    {
        $index = [];

        foreach ($keywords as $keyword) {
            foreach ($this->tokens($keyword->keyword) as $token) {
                $index[$token][] = $keyword->id;
            }
        }

        $weights = [];

        foreach ($index as $ids) {
            $ids = array_values(array_unique($ids));

            if (count($ids) < 2 || count($ids) > self::MAX_TOKEN_GROUP) {
                continue;
            }

            for ($i = 0; $i < count($ids); $i++) {
                for ($j = $i + 1; $j < count($ids); $j++) {
                    $a = min($ids[$i], $ids[$j]);
                    $b = max($ids[$i], $ids[$j]);

                    $weights["{$a}-{$b}"] = ($weights["{$a}-{$b}"] ?? 0) + 1;
                }
            }
        }

        if ($weights === []) {
            return [];
        }

        $edges = [];

        foreach ($weights as $pair => $weight) {
            [$source, $target] = array_map('intval', explode('-', $pair));

            $edges[] = ['source' => $source, 'target' => $target, 'weight' => $weight];
        }

        return $this->limitPerNode($edges);
    }

    /**
     * Keep only the strongest relationships so the graph stays readable.
     *
     * @param  array<int, array{source: int, target: int, weight: int}>  $edges
     * @return array<int, array{source: int, target: int, weight: int}>
     */
    private function limitPerNode(array $edges): array
    {
        $byNode = [];

        foreach ($edges as $edge) {
            $byNode[$edge['source']][] = $edge;
            $byNode[$edge['target']][] = $edge;
        }

        $kept = [];

        foreach ($byNode as $list) {
            usort($list, fn (array $a, array $b) => $b['weight'] <=> $a['weight']);

            foreach (array_slice($list, 0, self::MAX_EDGES_PER_NODE) as $edge) {
                $kept["{$edge['source']}-{$edge['target']}"] = $edge;
            }
        }

        return array_values($kept);
    }

    /**
     * @return array<string, mixed>
     */
    private function node(SeoKeyword $keyword, int $degree): array
    {
        $tone = self::STATUS_TONES[$keyword->status->value] ?? self::STATUS_TONES['idea'];

        return [
            'id' => $keyword->id,
            'label' => $keyword->keyword,
            'groups' => [
                'target' => filled($keyword->target_url) ? $keyword->target_url : 'بدون صفحه هدف',
                'cluster' => filled($keyword->cluster) ? $keyword->cluster : 'بدون خوشه',
                'status' => $keyword->status->label(),
            ],
            'status' => $keyword->status->value,
            'statusLabel' => $keyword->status->label(),
            'statusBg' => $tone['bg'],
            'statusText' => $tone['text'],
            'priority' => $keyword->priority,
            'priorityLabel' => $this->priorityLabels()[$keyword->priority] ?? $keyword->priority,
            'priorityText' => self::PRIORITY_TEXT[$keyword->priority] ?? self::PRIORITY_TEXT[2],
            'intentLabel' => $keyword->intent->label(),
            'contentTypeLabel' => $keyword->content_type->label(),
            'targetUrl' => $keyword->target_url,
            'position' => $keyword->current_position,
            'volume' => $keyword->search_volume,
            'difficultyLabel' => $keyword->difficulty?->label(),
            'targetDate' => $keyword->target_date?->locale('fa')->translatedFormat('j M Y'),
            'published' => $keyword->status === KeywordStatus::Published,
            'degree' => $degree,
            'tasks' => $this->tasks($keyword),
            'tasksCount' => $keyword->relationLoaded('tasks') ? $keyword->tasks->count() : 0,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function tasks(SeoKeyword $keyword): array
    {
        if (! $keyword->relationLoaded('tasks')) {
            return [];
        }

        return $keyword->tasks
            ->sortBy('sort_order')
            ->take(4)
            ->map(fn (SeoTask $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'done' => $task->isDone(),
                'due' => $task->due_date?->locale('fa')->translatedFormat('j M Y'),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function priorityLabels(): array
    {
        return [1 => 'بالا', 2 => 'متوسط', 3 => 'پایین'];
    }
}
