<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TaskCategory;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\SeoTask;
use App\Services\Seo\TaskCsvService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SeoTaskController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'priority' => $request->input('priority', 2),
            'status' => $request->input('status', TaskStatus::Todo->value),
        ]);

        $data = $this->validated($request);
        $task = new SeoTask($data);
        $task->completed_at = $task->status === TaskStatus::Done ? now() : null;
        $task->save();

        return back()->with('status', 'کار جدید ثبت شد.');
    }

    public function update(Request $request, SeoTask $task): RedirectResponse
    {
        $data = $this->validated($request);
        $status = TaskStatus::from($data['status']);
        unset($data['status']);

        $task->fill($data);
        $task->changeStatus($status);

        return back()->with('status', 'کار به‌روزرسانی شد.');
    }

    public function destroy(SeoTask $task): RedirectResponse
    {
        $task->delete();

        return back()->with('status', 'کار حذف شد.');
    }

    public function toggle(SeoTask $task): RedirectResponse
    {
        $task->toggleDone();

        return back()->with('status', $task->isDone() ? 'کار انجام شد.' : 'کار دوباره باز شد.');
    }

    public function import(Request $request, TaskCsvService $csv): RedirectResponse
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

    public function export(TaskCsvService $csv): StreamedResponse
    {
        return response()->streamDownload(function () use ($csv) {
            $handle = fopen('php://output', 'wb');
            $csv->writeTo($handle);
            fclose($handle);
        }, $csv->filename(), ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::enum(TaskCategory::class)],
            'priority' => ['required', 'integer', 'between:1,3'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'phase' => ['nullable', 'string', 'max:100'],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
            'keyword_id' => ['nullable', 'integer', Rule::exists('seo_keywords', 'id')],
            'notes' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
