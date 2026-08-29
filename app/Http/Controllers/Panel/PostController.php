<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Support\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::with('category')->latest();

        if ($durum = $request->query('durum')) {
            $query->where('status', $durum);
        }

        return view('panel.posts.index', [
            'posts' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only('durum'),
        ]);
    }

    public function create(): View
    {
        return view('panel.posts.form', [
            'post' => new Post(['status' => 'taslak']),
            'kategoriler' => BlogCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $post = Post::create([
            ...$data,
            'slug' => Post::uniqueSlug($data['title']),
            'user_id' => $request->user('panel')->id,
            'published_at' => $data['status'] === 'yayinda' ? now() : null,
        ]);

        ActivityLogger::log('blog.olusturuldu', $post);

        return redirect()->route('panel.posts.edit', $post)->with('status', 'Yazı oluşturuldu.');
    }

    public function edit(Post $post): View
    {
        return view('panel.posts.form', [
            'post' => $post,
            'kategoriler' => BlogCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);

        $yayinaAlindi = $data['status'] === 'yayinda' && ! $post->published_at;

        $post->update([
            ...$data,
            'slug' => $request->filled('slug')
                ? Post::uniqueSlug($request->input('slug'), $post->id)
                : $post->slug,
            'published_at' => $data['status'] === 'yayinda'
                ? ($post->published_at ?? now())
                : null,
        ]);

        ActivityLogger::log($yayinaAlindi ? 'blog.yayinlandi' : 'blog.guncellendi', $post);

        return back()->with('status', 'Yazı kaydedildi.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->cover_path) {
            Storage::disk('public')->delete($post->cover_path);
        }
        $post->delete();

        return redirect()->route('panel.posts.index')->with('status', 'Yazı silindi.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'status' => ['required', 'in:taslak,yayinda'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'cover' => ['nullable', 'image', 'max:4096'],
        ]);

        unset($data['cover']);

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('blog', 'public');
        }

        return $data;
    }
}
