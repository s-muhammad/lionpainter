<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $posts = Post::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['is_published' => true, 'author' => 'Lion Painters']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($request, $data['title']);
        $data['cover_image'] = $this->storeImage($request, 'cover_image');
        $data['reading_time'] = (int) $request->input('reading_time', 0);
        $data['published_at'] = $request->input('published_at') ?: now();

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('status', 'نوشته جدید ایجاد شد.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($request, $data['title'], $post);
        $data['cover_image'] = $this->storeImage($request, 'cover_image', $post->cover_image);
        $data['reading_time'] = (int) $request->input('reading_time', 0);
        $data['published_at'] = $request->input('published_at') ?: $post->published_at;

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('status', 'نوشته به‌روزرسانی شد.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'نوشته حذف شد.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:60'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'author' => ['required', 'string', 'max:100'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:60'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    private function slug(Request $request, string $title, ?Post $post = null): string
    {
        $slug = $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($title);
        $base = $slug;
        $i = 2;

        while (Post::where('slug', $slug)->when($post, fn ($q) => $q->whereKeyNot($post->id))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
