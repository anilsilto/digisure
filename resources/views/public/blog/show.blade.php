@extends('layouts.public')

@section('title', $post->metaTitle() . ' — ' . config('digisure.brand'))
@section('meta_description', $post->metaDescription())

@section('head')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->metaTitle() }}">
    <meta property="og:description" content="{{ $post->metaDescription() }}">
    <meta property="og:url" content="{{ route('blog.show', $post) }}">
    @if ($post->coverUrl())
        <meta property="og:image" content="{{ $post->coverUrl() }}">
    @endif
    <meta name="twitter:card" content="{{ $post->coverUrl() ? 'summary_large_image' : 'summary' }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->metaDescription(),
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => ['@type' => 'Organization', 'name' => config('digisure.agency.name')],
            'publisher' => ['@type' => 'Organization', 'name' => config('digisure.brand')],
            'mainEntityOfPage' => route('blog.show', $post),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-12 lg:py-16">
        <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-accent hover:text-accent-dark">← Blog</a>

        @if ($post->category)
            <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-accent-dark">{{ $post->category->name }}</p>
        @endif
        <h1 class="mt-1 text-3xl font-extrabold leading-tight text-ink sm:text-4xl">{{ $post->title }}</h1>
        <p class="mt-3 text-sm text-muted">
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>
            · {{ config('digisure.agency.name') }}
        </p>

        @if ($post->coverUrl())
            <img src="{{ $post->coverUrl() }}" alt="{{ $post->title }}" class="mt-6 w-full rounded-xl object-cover">
        @endif

        <div class="prose-blog mt-8">
            {!! $post->renderedBody() !!}
        </div>

        <div class="mt-10 rounded-xl bg-navy p-6 text-center text-white sm:flex sm:items-center sm:justify-between sm:text-left">
            <p class="font-semibold">Trafik, kasko veya sağlık sigortası mı arıyorsunuz?</p>
            <a href="{{ url('/teklif') }}" class="mt-3 inline-block rounded-lg bg-accent px-6 py-3 text-sm font-bold text-white hover:bg-accent-dark sm:mt-0">
                Hemen Teklif Al
            </a>
        </div>

        @if ($benzerler->isNotEmpty())
            <section class="mt-12">
                <h2 class="text-lg font-bold text-ink">İlgili yazılar</h2>
                <ul class="mt-4 space-y-2">
                    @foreach ($benzerler as $b)
                        <li>
                            <a href="{{ route('blog.show', $b) }}" class="font-semibold text-navy hover:text-accent">{{ $b->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </article>
@endsection
