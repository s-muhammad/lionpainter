<?php

namespace App\Services\Seo;

use App\Enums\TaskCategory;
use App\Enums\TaskStatus;
use App\Models\SeoKeyword;
use App\Models\SeoTask;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class TaskCsvService
{
    public const HEADERS = [
        'title', 'category', 'priority', 'status', 'phase',
        'due_date', 'keyword', 'notes', 'sort_order', 'completed_at',
    ];

    public function __construct(private CsvReader $reader) {}

    public function filename(): string
    {
        return 'seo-tasks-'.now()->format('Y-m-d').'.csv';
    }

    /**
     * @return \Generator<int, array<int, string|null>>
     */
    public function rows(): \Generator
    {
        foreach (SeoTask::query()->with('keyword')->orderBy('id')->cursor() as $task) {
            yield [
                $task->title,
                $task->category->value,
                (string) $task->priority,
                $task->status->value,
                $task->phase,
                $task->due_date?->toDateString(),
                $task->keyword?->keyword,
                $task->notes,
                (string) $task->sort_order,
                $task->completed_at?->format('Y-m-d H:i:s'),
            ];
        }
    }

    /**
     * @param  resource  $handle
     */
    public function writeTo($handle): void
    {
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, self::HEADERS, ',', '"', '');

        foreach ($this->rows() as $row) {
            fputcsv($handle, $row, ',', '"', '');
        }
    }

    public function import(UploadedFile $file): CsvImportResult
    {
        $csv = $this->reader->read($file);
        $this->reader->requireHeaders($csv['headers'], ['title', 'category']);

        $result = new CsvImportResult;

        foreach ($csv['rows'] as $row) {
            $validator = Validator::make($row['data'], $this->rules());

            if ($validator->fails()) {
                $result->recordSkipped($row['line'], $validator->errors()->first());

                continue;
            }

            $validated = $validator->validated();
            $keywordText = trim((string) ($validated['keyword'] ?? ''));
            $keyword = null;

            if ($keywordText !== '') {
                $keyword = SeoKeyword::query()->where('keyword', $keywordText)->first();

                if (! $keyword) {
                    $result->recordWarning($row['line'], "کلیدواژه «{$keywordText}» پیدا نشد؛ کار بدون اتصال ثبت شد.");
                }
            }

            $status = filled($validated['status'] ?? null)
                ? TaskStatus::from($validated['status'])
                : TaskStatus::Todo;

            try {
                SeoTask::query()->create([
                    'title' => trim($validated['title']),
                    'category' => $validated['category'],
                    'priority' => filled($validated['priority'] ?? null) ? (int) $validated['priority'] : 2,
                    'status' => $status,
                    'phase' => $this->nullableString($validated['phase'] ?? null),
                    'due_date' => filled($validated['due_date'] ?? null) ? $validated['due_date'] : null,
                    'keyword_id' => $keyword?->id,
                    'notes' => $this->nullableString($validated['notes'] ?? null),
                    'sort_order' => filled($validated['sort_order'] ?? null) ? (int) $validated['sort_order'] : 0,
                    'completed_at' => $status === TaskStatus::Done
                        ? (filled($validated['completed_at'] ?? null) ? Carbon::parse($validated['completed_at']) : now())
                        : null,
                ]);

                $result->recordCreated();
            } catch (Throwable $exception) {
                report($exception);
                $result->recordSkipped($row['line'], 'ذخیره این ردیف ممکن نشد.');
            }
        }

        return $result;
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(TaskCategory::class)],
            'priority' => ['nullable', 'integer', 'between:1,3'],
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
            'phase' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
