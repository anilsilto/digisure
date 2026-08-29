<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::published()->with('category')->latest('published_at');

        $aktifKategori = null;
        if ($slug = $request->query('kategori')) {
            $aktifKategori = BlogCategory::where('slug', $slug)->first();
            $query->when($aktifKategori, fn ($q) => $q->where('blog_category_id', $aktifKategori->id));
        }

        return view('public.blog.index', [
            'posts' => $query->paginate(9)->withQueryString(),
            'kategoriler' => BlogCategory::whereHas('posts', fn ($q) => $q->published())->orderBy('name')->get(),
            'aktifKategori' => $aktifKategori,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->status === 'yayinda' && $post->published_at && $post->published_at->isPast(),
            404
        );

        return view('public.blog.show', [
            'post' => $post->load('category', 'author'),
            'benzerler' => Post::published()
                ->where('id', '!=', $post->id)
                ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
