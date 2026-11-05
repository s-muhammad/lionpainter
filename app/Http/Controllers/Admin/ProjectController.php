<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $projects = Project::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->ordered()
            ->paginate(10)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'categories' => Project::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function create()
    {
        return view('admin.projects.form', [
            'project' => new Project(['is_published' => true, 'category' => 'Residential']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($request, $data['title']);
        $data['gallery'] = $this->galleryFromText($request->input('gallery'));
        $data['cover_image'] = $this->storeImage($request, 'cover_image');

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('status', 'پروژه جدید ثبت شد.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($request, $data['title'], $project);
        $data['gallery'] = $this->galleryFromText($request->input('gallery'));
        $data['cover_image'] = $this->storeImage($request, 'cover_image', $project->cover_image);

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', 'پروژه به‌روزرسانی شد.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'پروژه حذف شد.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:60'],
            'suburb' => ['nullable', 'string', 'max:120'],
            'year' => ['nullable', 'digits:4'],
            'colour' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'gallery' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = (int) $request->input('sort_order', 0);

        return $data;
    }

    private function slug(Request $request, string $title, ?Project $project = null): string
    {
        $slug = $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($title);
        $base = $slug;
        $i = 2;

        while (Project::where('slug', $slug)->when($project, fn ($q) => $q->whereKeyNot($project->id))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
