<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KeywordContentType;
use App\Enums\KeywordDifficulty;
use App\Enums\KeywordIntent;
use App\Enums\KeywordStatus;
use App\Enums\TaskCategory;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\SeoKeyword;
use App\Models\SeoTask;
use App\Services\Seo\KeywordGraphService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoNotebookController extends Controller
{
    /**
     * Tabs available in the notebook.
     */
    private const TABS = ['keywords', 'tasks', 'graph'];

    public function __invoke(Request $request): View
    {
        $tab = $request->query('tab');
        $tab = in_array($tab, self::TABS, true) ? $tab : 'keywords';

        $stats = [
            'keywords_total' => SeoKeyword::query()->count(),
            'keywords_published' => SeoKeyword::query()->published()->count(),
            'tasks_total' => SeoTask::query()->count(),
            'tasks_done' => SeoTask::query()->where('status', TaskStatus::Done->value)->count(),
            'tasks_overdue' => SeoTask::query()->overdue()->count(),
        ];

        if ($tab === 'tasks') {
            return view('admin.seo.index', array_merge(
                compact('tab', 'stats'),
                $this->taskData($request),
            ));
        }

        if ($tab === 'graph') {
            return view('admin.seo.index', array_merge(
                compact('tab', 'stats'),
                $this->graphData($request),
            ));
        }

        return view('admin.seo.index', array_merge(
            compact('tab', 'stats'),
            $this->keywordData($request),
        ));
    }

    private function keywordData(Request $request): array
    {
        $filters = [
            'search' => (string) $this->stringQuery($request, 'k_search'),
            'status' => $this->enumValue($request->query('k_status'), KeywordStatus::class),
            'priority' => $this->priority($request->query('k_priority')),
            'cluster' => $this->stringQuery($request, 'k_cluster'),
            'intent' => $this->enumValue($request->query('k_intent'), KeywordIntent::class),
            'content_type' => $this->enumValue($request->query('k_content_type'), KeywordContentType::class),
        ];

        $sort = $this->stringQuery($request, 'k_sort');
        $filters['sort'] = in_array($sort, SeoKeyword::SORTABLE, true) ? $sort : 'keyword';
        $filters['direction'] = $request->query('k_dir') === 'desc' ? 'desc' : 'asc';

        $viewMode = $request->query('k_view') === 'plan' ? 'plan' : 'table';

        $keywords = null;
        $planGroups = collect();

        if ($viewMode === 'plan') {
            $planKeywords = SeoKeyword::query()->plan()->planOrdered()->get();

            $planGroups = collect([KeywordStatus::Planned, KeywordStatus::InProgress])
                ->mapWithKeys(fn (KeywordStatus $status) => [
                    $status->value => $planKeywords
                        ->filter(fn (SeoKeyword $keyword) => $keyword->status === $status)
                        ->values(),
                ]);
        } else {
            $keywords = SeoKeyword::query()
                ->search($filters['search'])
                ->status($filters['status'])
                ->priority($filters['priority'])
                ->cluster($filters['cluster'])
                ->intent($filters['intent'])
                ->contentType($filters['content_type'])
                ->sorted($filters['sort'], $filters['direction'])
                ->paginate(25, ['*'], 'k_page')
                ->withQueryString();
        }

        return [
            'filters' => $filters,
            'viewMode' => $viewMode,
            'keywords' => $keywords,
            'planGroups' => $planGroups,
            'clusters' => SeoKeyword::query()
                ->whereNotNull('cluster')
                ->where('cluster', '!=', '')
                ->distinct()
                ->orderBy('cluster')
                ->pluck('cluster'),
            'statuses' => KeywordStatus::cases(),
            'intents' => KeywordIntent::cases(),
            'contentTypes' => KeywordContentType::cases(),
            'difficulties' => KeywordDifficulty::cases(),
            'priorityOptions' => $this->priorityOptions(),
        ];
    }

    private function taskData(Request $request): array
    {
        $filters = [
            'status' => $this->enumValue($request->query('t_status'), TaskStatus::class),
            'category' => $this->enumValue($request->query('t_category'), TaskCategory::class),
            'priority' => $this->priority($request->query('t_priority')),
            'phase' => $this->stringQuery($request, 't_phase'),
        ];

        $tasks = SeoTask::query()
            ->with('keyword')
            ->status($filters['status'])
            ->category($filters['category'])
            ->priority($filters['priority'])
            ->when($filters['phase'] === '__none__', fn ($query) => $query->whereNull('phase'))
            ->when(filled($filters['phase']) && $filters['phase'] !== '__none__', fn ($query) => $query->phase($filters['phase']))
            ->ordered()
            ->get();

        $groups = $tasks->groupBy(fn (SeoTask $task) => filled($task->phase) ? $task->phase : '__none__');

        return [
            'taskFilters' => $filters,
            'taskGroups' => $groups,
            'phases' => SeoTask::query()
                ->whereNotNull('phase')
                ->where('phase', '!=', '')
                ->distinct()
                ->orderBy('phase')
                ->pluck('phase'),
            'hasNoPhase' => SeoTask::query()
                ->where(function ($query) {
                    $query->whereNull('phase')->orWhere('phase', '');
                })
                ->exists(),
            'keywordOptions' => SeoKeyword::query()->orderBy('keyword')->get(['id', 'keyword']),
            'taskStatuses' => TaskStatus::cases(),
            'taskCategories' => TaskCategory::cases(),
            'priorityOptions' => $this->priorityOptions(),
        ];
    }

    private function graphData(Request $request): array
    {
        $filters = [
            'search' => (string) $this->stringQuery($request, 'g_search'),
            'status' => $this->enumValue($request->query('g_status'), KeywordStatus::class),
            'priority' => $this->priority($request->query('g_priority')),
            'content_type' => $this->enumValue($request->query('g_content_type'), KeywordContentType::class),
            'target' => $this->stringQuery($request, 'g_target'),
        ];

        $keywords = SeoKeyword::query()
            ->with('tasks')
            ->search($filters['search'])
            ->status($filters['status'])
            ->priority($filters['priority'])
            ->contentType($filters['content_type'])
            ->when(filled($filters['target']), fn ($query) => $query->where('target_url', $filters['target']))
            ->orderBy('priority')
            ->orderBy('keyword')
            ->limit(KeywordGraphService::MAX_NODES)
            ->get();

        $graph = (new KeywordGraphService)->build($keywords);

        return [
            'graphFilters' => $filters,
            'graph' => $graph,
            'graphDefaultId' => $graph['nodes'][0]['id'] ?? null,
            'graphStats' => [
                'keywords' => count($graph['nodes']),
                'links' => count($graph['edges']),
                'priority' => $keywords->where('priority', 1)->count(),
                'published' => $keywords->filter(fn (SeoKeyword $keyword) => $keyword->status === KeywordStatus::Published)->count(),
            ],
            'graphTargets' => SeoKeyword::query()
                ->whereNotNull('target_url')
                ->where('target_url', '!=', '')
                ->distinct()
                ->orderBy('target_url')
                ->pluck('target_url'),
            'graphStatuses' => KeywordStatus::cases(),
            'graphContentTypes' => KeywordContentType::cases(),
            'priorityOptions' => $this->priorityOptions(),
        ];
    }

    private function stringQuery(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        return is_string($value) ? trim($value) : null;
    }

    /**
     * @param  class-string<\BackedEnum>  $enum
     */
    private function enumValue(mixed $value, string $enum): ?string
    {
        return is_string($value) ? $enum::tryFrom($value)?->value : null;
    }

    private function priority(mixed $value): ?int
    {
        return is_string($value) && in_array($value, ['1', '2', '3'], true)
            ? (int) $value
            : null;
    }

    private function priorityOptions(): array
    {
        return [1 => 'بالا', 2 => 'متوسط', 3 => 'پایین'];
    }
}
