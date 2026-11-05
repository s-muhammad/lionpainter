<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service(['is_active' => true, 'is_featured' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($request, $data['title']);
        $data['features'] = $this->lines($request->input('features'));
        $data['image'] = $this->storeImage($request);

        Service::create($data);

        return redirect()->route('admin.services.index')->with('status', 'خدمت جدید با موفقیت ایجاد شد.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validated($request, $service);
        $data['slug'] = $this->slug($request, $data['title'], $service);
        $data['features'] = $this->lines($request->input('features'));
        $data['image'] = $this->storeImage($request, 'image', $service->image);

        $service->update($data);

        return redirect()->route('admin.services.index')->with('status', 'خدمت با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'خدمت حذف شد.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('services', 'slug')->ignore($service)],
            'icon' => ['nullable', 'string', 'max:40'],
            'tagline' => ['nullable', 'string', 'max:190'],
            'excerpt' => ['required', 'string', 'max:600'],
            'description' => ['required', 'string'],
            'features' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = (int) $request->input('sort_order', 0);

        return $data;
    }

    private function slug(Request $request, string $title, ?Service $service = null): string
    {
        $slug = $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($title);
        $base = $slug;
        $i = 2;

        while (Service::where('slug', $slug)->when($service, fn ($q) => $q->whereKeyNot($service->id))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    private function lines(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
