@extends('layouts.panel')

@section('title', 'Blog')
@section('heading', 'Blog Yazıları')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <form method="GET" class="flex items-end gap-3 text-sm">
            <div>
                <label class="block text-xs font-medium text-muted">Durum</label>
                <select name="durum" class="mt-1 rounded-lg border border-line px-2 py-1.5">
                    <option value="">Tümü</option>
                    <option value="yayinda" @selected(($filters['durum'] ?? '') === 'yayinda')>Yayında</option>
                    <option value="taslak" @selected(($filters['durum'] ?? '') === 'taslak')>Taslak</option>
                </select>
            </div>
            <button class="rounded-lg bg-navy px-4 py-2 font-medium text-white">Filtrele</button>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('panel.blog-categories.index') }}" class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-navy hover:bg-navy-tint">Kategoriler</a>
            <a href="{{ route('panel.posts.create') }}" class="rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent-dark">+ Yeni Yazı</a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full text-sm">
            <thead class="border-b border-line bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Başlık</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Durum</th>
                    <th class="px-4 py-3">Yayın</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($posts as $post)
                    <tr class="hover:bg-navy-tint/50">
                        <td class="px-4 py-3">
                            <a href="{{ route('panel.posts.edit', $post->id) }}" class="font-semibold text-navy hover:underline">{{ $post->title }}</a>
                            <div class="text-xs text-muted">/blog/{{ $post->slug }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $post->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $post->status === 'yayinda' ? 'bg-ok/10 text-ok' : 'bg-navy-tint-2 text-muted' }}">{{ $post->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $post->published_at?->format('d.m.Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($post->status === 'yayinda')
                                <a href="{{ route('blog.show', $post) }}" target="_blank" class="text-xs text-navy hover:underline">Görüntüle</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-muted">Yazı yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $posts->links() }}</div>
@endsection
