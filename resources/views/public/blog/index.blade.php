@extends('layouts.public')

@section('title', ($aktifKategori ? $aktifKategori->name . ' | ' : '') . 'Blog — ' . config('digisure.brand'))
@section('meta_description', 'Trafik, kasko ve sağlık sigortası hakkında rehberler, hasar süreci ipuçları ve sık sorulan sorular.')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 lg:py-16">
        <p class="text-sm font-semibold uppercase tracking-wider text-accent-dark">{{ config('digisure.brand') }} Blog</p>
        <h1 class="mt-1 text-3xl font-extrabold text-ink sm:text-4xl">Sigorta rehberi</h1>
        <p class="mt-2 max-w-2xl text-muted">Trafik, kasko ve sağlık sigortasında merak edilenler; hasar süreci, teminatlar ve pratik ipuçları.</p>

        @if ($kategoriler->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}"
                   class="rounded-full border px-4 py-1.5 text-sm font-medium transition {{ $aktifKategori ? 'border-line bg-white text-muted hover:border-navy' : 'border-navy bg-navy text-white' }}">
                    Tümü
                </a>
                @foreach ($kategoriler as $kat)
                    <a href="{{ route('blog.index', ['kategori' => $kat->slug]) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium transition {{ $aktifKategori?->id === $kat->id ? 'border-navy bg-navy text-white' : 'border-line bg-white text-muted hover:border-navy' }}">
                        {{ $kat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <article class="flex flex-col overflow-hidden rounded-xl border border-line bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    @if ($post->coverUrl())
                        <img src="{{ $post->coverUrl() }}" alt="" class="h-44 w-full object-cover">
                    @else
                        <div class="h-44 w-full bg-gradient-to-br from-navy to-accent-dark"></div>
                    @endif
                    <div class="flex flex-1 flex-col p-5">
                        @if ($post->category)
                            <span class="text-xs font-semibold uppercase tracking-wide text-accent-dark">{{ $post->category->name }}</span>
                        @endif
                        <h2 class="mt-1 text-lg font-bold leading-snug text-navy">
                            <a href="{{ route('blog.show', $post) }}" class="hover:text-accent">{{ $post->title }}</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm text-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                        <div class="mt-4 flex items-center justify-between text-xs text-muted">
                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>
                            <a href="{{ route('blog.show', $post) }}" class="font-semibold text-accent hover:text-accent-dark">Devamını oku →</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="col-span-full rounded-xl border border-line bg-white p-10 text-center text-muted">Henüz yazı yok.</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    </section>
@endsection
