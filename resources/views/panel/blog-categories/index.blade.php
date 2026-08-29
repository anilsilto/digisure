@extends('layouts.panel')

@section('title', 'Blog Kategorileri')
@section('heading', 'Blog Kategorileri')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('panel.blog-categories.store') }}" class="h-fit rounded-xl border border-line bg-white p-5">
            @csrf
            <label class="block text-sm font-medium text-ink">Yeni kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Örn. Kasko"
                   class="mt-1 w-full rounded-lg border border-line px-3 py-2">
            @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            <button class="mt-3 w-full rounded-lg bg-navy px-4 py-2 text-sm font-semibold text-white">Ekle</button>
        </form>

        <div class="space-y-3 lg:col-span-2">
            @forelse ($kategoriler as $kat)
                <div class="flex flex-wrap items-center gap-3 rounded-xl border border-line bg-white p-4">
                    <form method="POST" action="{{ route('panel.blog-categories.update', $kat->id) }}" class="flex flex-1 flex-wrap items-center gap-2">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $kat->name }}" class="min-w-32 flex-1 rounded border border-line px-2 py-1 text-sm">
                        <input type="text" name="slug" value="{{ $kat->slug }}" class="min-w-32 flex-1 rounded border border-line px-2 py-1 font-mono text-xs">
                        <span class="text-xs text-muted">{{ $kat->posts_count }} yazı</span>
                        <button class="rounded bg-navy px-3 py-1 text-xs font-semibold text-white">Kaydet</button>
                    </form>
                    <form method="POST" action="{{ route('panel.blog-categories.destroy', $kat->id) }}" onsubmit="return confirm('Silinsin mi? Yazıları kategorisiz kalır.')">
                        @csrf @method('DELETE')
                        <button class="rounded border border-danger/40 px-3 py-1 text-xs font-medium text-danger hover:bg-danger-tint">Sil</button>
                    </form>
                </div>
            @empty
                <p class="rounded-xl border border-line bg-white p-8 text-center text-muted">Henüz kategori yok.</p>
            @endforelse
        </div>
    </div>
@endsection
