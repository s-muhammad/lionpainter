<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KeywordContentType;
use App\Enums\KeywordDifficulty;
use App\Enums\KeywordIntent;
use App\Enums\KeywordStatus;
use App\Http\Controllers\Controller;
use App\Models\SeoKeyword;
use App\Services\Seo\KeywordCsvService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SeoKeywordController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        SeoKeyword::query()->create($this->validated($request));

        return back()->with('status', 'کلیدواژه جدید ثبت شد.');
    }

    public function update(Request $request, SeoKeyword $keyword): RedirectResponse
    {
        $keyword->update($this->validated($request, $keyword));

        return back()->with('status', 'کلیدواژه به‌روزرسانی شد.');
    }

    public function destroy(SeoKeyword $keyword): RedirectResponse
    {
        $keyword->delete();

        return back()->with('status', 'کلیدواژه حذف شد.');
    }

    public function status(Request $request, SeoKeyword $keyword): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(KeywordStatus::class)],
        ]);

        $keyword->update(['status' => $data['status']]);

        return back()->with('status', 'وضعیت کلیدواژه به‌روزرسانی شد.');
    }

    public function bulkStatus(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:500'],
            'ids.*' => ['integer', 'distinct', Rule::exists('seo_keywords', 'id')],
            'status' => ['required', Rule::enum(KeywordStatus::class)],
        ]);

        $count = SeoKeyword::query()
            ->whereIn('id', $data['ids'])
            ->update(['status' => $data['status']]);

        return back()->with('status', "وضعیت {$count} کلیدواژه به‌روزرسانی شد.");
    }

    public function import(Request $request, KeywordCsvService $csv): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        try {
            $result = $csv->import($request->file('file'));
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('import_error', $exception instanceof \InvalidArgumentException
                ? $exception->getMessage()
                : 'خواندن فایل CSV ممکن نشد.');
        }

        return back()
            ->with('import_result', $result->toArray())
            ->with('status', "ورود CSV انجام شد: {$result->created} ایجاد، {$result->updated} به‌روزرسانی، {$result->skipped} ردشده.");
    }

    public function export(KeywordCsvService $csv): StreamedResponse
    {
        return response()->streamDownload(function () use ($csv) {
            $handle = fopen('php://output', 'wb');
            $csv->writeTo($handle);
            fclose($handle);
        }, $csv->filename(), ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validated(Request $request, ?SeoKeyword $keyword = null): array
    {
        return $request->validate([
            'keyword' => ['required', 'string', 'max:255', Rule::unique('seo_keywords', 'keyword')->ignore($keyword?->getKey())],
            'cluster' => ['nullable', 'string', 'max:255'],
            'intent' => ['required', Rule::enum(KeywordIntent::class)],
            'content_type' => ['required', Rule::enum(KeywordContentType::class)],
            'target_url' => ['nullable', 'string', 'max:2048'],
            'priority' => ['required', 'integer', 'between:1,3'],
            'status' => ['required', Rule::enum(KeywordStatus::class)],
            'difficulty' => ['nullable', Rule::enum(KeywordDifficulty::class)],
            'search_volume' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'current_position' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'target_date' => ['nullable', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ]);
    }
}
