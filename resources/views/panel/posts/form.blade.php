@extends('layouts.panel')

@section('title', $post->exists ? 'Yazı Düzenle' : 'Yeni Yazı')
@section('heading', $post->exists ? 'Yazı Düzenle' : 'Yeni Yazı')

@section('content')
    <form method="POST"
          action="{{ $post->exists ? route('panel.posts.update', $post->id) : route('panel.posts.store') }}"
          enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($post->exists) @method('PUT') @endif

        <div class="space-y-4 lg:col-span-2">
            @if ($errors->any())
                <div class="rounded-lg border border-danger/30 bg-danger-tint px-3 py-2 text-sm text-danger">{{ $errors->first() }}</div>
            @endif

            <div class="rounded-xl border border-line bg-white p-5">
                <label class="block text-sm font-medium text-ink">Başlık</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2">

                @if ($post->exists)
                    <label class="mt-4 block text-sm font-medium text-ink">Slug (URL)</label>
                    <input type="text" name="slug" value="{{ old('slug', $post->slug) }}"
                           class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-mono text-sm">
                @endif

                <label class="mt-4 block text-sm font-medium text-ink">Özet</label>
                <textarea name="excerpt" rows="2" class="mt-1 w-full rounded-lg border border-line px-3 py-2">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div x-data="{ preview: false }" class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-medium text-ink">İçerik (Markdown)</label>
                    @if ($post->exists)
                        <button type="button" @click="preview = !preview" class="text-xs font-semibold text-accent-dark">
                            <span x-show="!preview">Önizlemeyi göster</span><span x-show="preview" x-cloak>Düzenlemeye dön</span>
                        </button>
                    @endif
                </div>
                <textarea x-show="!preview" name="body" rows="20" required
                          class="mt-1 w-full rounded-lg border border-line px-3 py-2 font-mono text-sm leading-relaxed">{{ old('body', $post->body) }}</textarea>
                @if ($post->exists)
                    <div x-show="preview" x-cloak class="prose-blog mt-2 rounded-lg border border-line p-4">{!! $post->renderedBody() !!}</div>
                @endif
                <p class="mt-2 text-xs text-muted"># başlık · ## alt başlık · **kalın** · - liste · [metin](https://...) · &gt; alıntı. Ham HTML güvenlik için temizlenir.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl border border-line bg-white p-5">
                <label class="block text-sm font-medium text-ink">Durum</label>
                <select name="status" class="mt-1 w-full rounded-lg border border-line px-3 py-2">
                    <option value="taslak" @selected(old('status', $post->status) === 'taslak')>Taslak</option>
                    <option value="yayinda" @selected(old('status', $post->status) === 'yayinda')>Yayında</option>
                </select>

                <label class="mt-4 block text-sm font-medium text-ink">Kategori</label>
                <select name="blog_category_id" class="mt-1 w-full rounded-lg border border-line px-3 py-2">
                    <option value="">— Yok —</option>
                    @foreach ($kategoriler as $kat)
                        <option value="{{ $kat->id }}" @selected((int) old('blog_category_id', $post->blog_category_id) === $kat->id)>{{ $kat->name }}</option>
                    @endforeach
                </select>

                <label class="mt-4 block text-sm font-medium text-ink">Kapak Görseli</label>
                <input type="file" name="cover" accept="image/*" class="mt-1 block w-full text-xs">
                @if ($post->coverUrl())
                    <img src="{{ $post->coverUrl() }}" alt="" class="mt-2 h-24 w-full rounded object-cover">
                @endif

                <button type="submit" class="mt-5 w-full rounded-lg bg-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Kaydet</button>
            </div>

            <div class="rounded-xl border border-line bg-white p-5">
                <p class="text-sm font-medium text-ink">SEO</p>
                <label class="mt-2 block text-xs text-muted">Meta başlık (boşsa başlık kullanılır)</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5 text-sm">
                <label class="mt-3 block text-xs text-muted">Meta açıklama (~155 karakter)</label>
                <textarea name="meta_description" rows="3" maxlength="300" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5 text-sm">{{ old('meta_description', $post->meta_description) }}</textarea>
            </div>

            @if ($post->exists)
                <button type="submit" form="post-delete" onclick="return confirm('Yazı silinsin mi?')"
                        class="w-full rounded-lg border border-danger/40 px-4 py-2 text-sm font-medium text-danger hover:bg-danger-tint">
                    Yazıyı Sil
                </button>
            @endif
        </div>
    </form>

    @if ($post->exists)
        <form id="post-delete" method="POST" action="{{ route('panel.posts.destroy', $post->id) }}" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endif
@endsection
