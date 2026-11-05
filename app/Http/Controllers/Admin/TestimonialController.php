<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::ordered()->paginate(10),
            'testimonial' => new Testimonial(['is_published' => true, 'rating' => 5]),
        ]);
    }

    public function store(Request $request)
    {
        Testimonial::create($this->validated($request));

        return back()->with('status', 'نظر جدید ثبت شد.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('status', 'نظر به‌روزرسانی شد.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'نظر حذف شد.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'suburb' => ['nullable', 'string', 'max:120'],
            'project_type' => ['nullable', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = (int) $request->input('sort_order', 0);

        return $data;
    }
}
