@extends('layouts.public')

@section('title', 'Talebiniz Alındı — ' . config('digisure.agency.name'))

@section('content')
    <section class="mx-auto max-w-xl px-4 py-20 text-center">
        @php $mascot = collect(['png', 'webp', 'jpg'])->map(fn ($e) => "img/mascot.$e")->first(fn ($r) => file_exists(public_path($r))); @endphp
        @if ($mascot)
            <img src="{{ asset($mascot) }}" alt="" class="mx-auto h-40 w-40 object-contain drop-shadow-[0_12px_30px_rgba(44,184,218,0.3)]">
        @else
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-ok/10 text-2xl text-ok">✓</div>
        @endif
        <h1 class="mt-6 text-2xl font-extrabold text-ink">Talebiniz alındı</h1>

        @if ($reference_no)
            <p class="mt-3 text-muted">Referans numaranız:
                <span class="font-mono text-lg font-bold text-navy">{{ $reference_no }}</span>
            </p>
        @endif

        <p class="mt-4 text-sm text-muted">
            Anlaşmalı şirketlerin teklifleri hazırlandığında size SMS ile bilgi vereceğiz.
            Teklifleri karşılaştırmak için referans numaranız ve telefonunuzla
            <a href="{{ url('/hesabim') }}" class="font-medium text-navy underline">Hesabım</a> bölümüne giriş yapabilirsiniz.
        </p>

        <a href="{{ url('/') }}" class="mt-8 inline-block rounded-lg border border-line px-5 py-2.5 text-sm font-semibold text-navy hover:bg-navy-tint">
            Ana sayfaya dön
        </a>
    </section>
@endsection
