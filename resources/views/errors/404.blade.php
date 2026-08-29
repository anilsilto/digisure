@extends('layouts.public')

@section('title', 'Sayfa bulunamadı — ' . config('digisure.brand'))

@section('content')
    <section class="mx-auto max-w-lg px-4 py-24 text-center">
        @php $mascot = collect(['png', 'webp', 'jpg'])->map(fn ($e) => "img/mascot.$e")->first(fn ($r) => file_exists(public_path($r))); @endphp
        @if ($mascot)
            <img src="{{ asset($mascot) }}" alt="" class="mx-auto h-32 w-32 rounded-full object-cover">
        @endif
        <p class="mt-6 text-5xl font-extrabold text-navy">404</p>
        <h1 class="mt-2 text-xl font-bold text-ink">Aradığınız sayfa bulunamadı</h1>
        <p class="mt-2 text-sm text-muted">Bağlantı taşınmış veya kaldırılmış olabilir.</p>
        <a href="{{ url('/') }}"
           class="mt-8 inline-block rounded-lg bg-accent px-6 py-3 text-sm font-semibold text-white transition hover:bg-accent-dark">
            Ana sayfaya dön
        </a>
    </section>
@endsection
