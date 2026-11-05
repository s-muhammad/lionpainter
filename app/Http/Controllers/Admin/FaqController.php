<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::ordered()->paginate(15),
            'faq' => new Faq(['is_published' => true]),
        ]);
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));

        return back()->with('status', 'سؤال جدید ثبت شد.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('status', 'سؤال به‌روزرسانی شد.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'سؤال حذف شد.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:240'],
            'answer' => ['required', 'string', 'max:3000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = (int) $request->input('sort_order', 0);

        return $data;
    }
}
