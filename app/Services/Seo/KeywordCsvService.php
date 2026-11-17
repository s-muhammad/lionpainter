<?php

namespace App\Services\Seo;

use App\Enums\KeywordContentType;
use App\Enums\KeywordDifficulty;
use App\Enums\KeywordIntent;
use App\Enums\KeywordStatus;
use App\Models\SeoKeyword;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class KeywordCsvService
{
    public const HEADERS = [
        'keyword', 'cluster', 'intent', 'content_type', 'target_url', 'priority',
        'status', 'difficulty', 'search_volume', 'current_position', 'target_date', 'notes',
    ];

    public function __construct(private CsvReader $reader) {}

    public function filename(): string
    {
        return 'seo-keywords-'.now()->format('Y-m-d').'.csv';
    }

    /**
     * @return \Generator<int, array<int, string|null>>
     */
    public function rows(): \Generator
    {
        foreach (SeoKeyword::query()->orderBy('keyword')->cursor() as $keyword) {
            yield [
                $keyword->keyword,
                $keyword->cluster,
                $keyword->intent->value,
                $keyword->content_type->value,
                $keyword->target_url,
                (string) $keyword->priority,
                $keyword->status->value,
                $keyword->difficulty?->value,
                $keyword->search_volume === null ? null : (string) $keyword->search_volume,
                $keyword->current_position === null ? null : (string) $keyword->current_position,
                $keyword->target_date?->toDateString(),
                $keyword->notes,
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
        $this->reader->requireHeaders($csv['headers'], ['keyword', 'intent', 'content_type']);

        $result = new CsvImportResult;

        foreach ($csv['rows'] as $row) {
            $validator = Validator::make($row['data'], $this->rules());

            if ($validator->fails()) {
                $result->recordSkipped($row['line'], $validator->errors()->first());

                continue;
            }

            $validated = $validator->validated();
            $keyword = trim($validated['keyword']);

            try {
                $existing = SeoKeyword::query()->where('keyword', $keyword)->first();
                $payload = $this->payload($validated);

                if ($existing) {
                    $existing->update($payload);
                    $result->recordUpdated();
                } else {
                    SeoKeyword::query()->create(['keyword' => $keyword] + $payload);
                    $result->recordCreated();
                }
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
            'keyword' => ['required', 'string', 'max:255'],
            'cluster' => ['nullable', 'string', 'max:255'],
            'intent' => ['required', Rule::enum(KeywordIntent::class)],
            'content_type' => ['required', Rule::enum(KeywordContentType::class)],
            'target_url' => ['nullable', 'string', 'max:2048'],
            'priority' => ['nullable', 'integer', 'between:1,3'],
            'status' => ['nullable', Rule::enum(KeywordStatus::class)],
            'difficulty' => ['nullable', Rule::enum(KeywordDifficulty::class)],
            'search_volume' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'current_position' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'target_date' => ['nullable', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ];
    }

    private function payload(array $validated): array
    {
        return [
            'cluster' => $this->nullableString($validated['cluster'] ?? null),
            'intent' => $validated['intent'],
            'content_type' => $validated['content_type'],
            'target_url' => $this->nullableString($validated['target_url'] ?? null),
            'priority' => filled($validated['priority'] ?? null) ? (int) $validated['priority'] : 2,
            'status' => filled($validated['status'] ?? null) ? $validated['status'] : KeywordStatus::Idea->value,
            'difficulty' => filled($validated['difficulty'] ?? null) ? $validated['difficulty'] : null,
            'search_volume' => $this->nullableInt($validated['search_volume'] ?? null),
            'current_position' => $this->nullableInt($validated['current_position'] ?? null),
            'target_date' => filled($validated['target_date'] ?? null) ? $validated['target_date'] : null,
            'notes' => $this->nullableString($validated['notes'] ?? null),
        ];
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function nullableInt(int|string|null $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }
}
