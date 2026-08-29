@extends('layouts.public')

@section('content')
    <section class="relative overflow-hidden bg-navy text-white">
        {{-- baykuşun arkasında yumuşak turkuaz ışıma --}}
        <div class="pointer-events-none absolute -right-24 top-1/2 hidden h-[36rem] w-[36rem] -translate-y-1/2 rounded-full bg-accent/20 blur-3xl lg:block"></div>

        <div class="relative mx-auto flex max-w-6xl flex-col items-center gap-12 px-4 py-16 lg:flex-row lg:justify-between lg:py-24">
            <div class="max-w-xl text-center lg:text-left">
                <div class="mb-4 flex items-center justify-center gap-1.5 lg:justify-start">
                    <span class="h-2 w-2 rounded-full bg-accent"></span>
                    <span class="h-2 w-2 rounded-full bg-accent/70"></span>
                    <span class="h-2 w-2 rounded-full bg-accent/40"></span>
                    <span class="ml-2 text-sm font-semibold uppercase tracking-wider text-white/70">{{ config('digisure.agency.name') }}</span>
                </div>
                <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl">
                    {{ config('digisure.agency.slogan') }}
                </h1>
                <p class="mt-4 text-lg text-white/80">
                    Trafik, kasko ve sağlık sigortasında anlaşmalı şirketlerin tekliflerini tek ekranda karşılaştırın, uzman ekiple poliçenizi tamamlayın.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4 lg:justify-start">
                    <a href="{{ url('/teklif') }}"
                       class="rounded-lg bg-accent px-7 py-3.5 font-semibold text-white shadow-lg shadow-accent/25 transition hover:bg-accent-dark">
                        Hemen Teklif Al
                    </a>
                    <a href="#nasil-calisir" class="text-sm font-semibold text-white/80 underline-offset-4 hover:text-white hover:underline">
                        Nasıl çalışır?
                    </a>
                </div>
            </div>

            <div class="shrink-0">
                <x-brand.hero-mark class="h-56 w-56 drop-shadow-[0_0_60px_rgba(44,184,218,0.35)] sm:h-64 sm:w-64 lg:h-80 lg:w-80" />
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16">
        <h2 class="text-2xl font-bold text-ink">Ürünlerimiz</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            @foreach ($products as $product)
                <div class="rounded-xl border border-line bg-white p-6 shadow-sm transition hover:shadow-md">
                    <h3 class="text-lg font-semibold text-navy">{{ $product->name }}</h3>
                    <p class="mt-2 text-sm text-muted">
                        {{ $product->key === 'trafik' ? 'Zorunlu trafik sigortanızı en uygun fiyata bulun.' : '' }}
                        {{ $product->key === 'kasko' ? 'Aracınızı kapsamlı teminatlarla güvence altına alın.' : '' }}
                        {{ $product->key === 'saglik' ? 'Tamamlayıcı ve özel sağlık planlarını karşılaştırın.' : '' }}
                    </p>
                    <a href="{{ route('urun.show', $product->key) }}"
                       class="mt-4 inline-block text-sm font-semibold text-accent hover:text-accent-dark">
                        Detay & Teklif Al →
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section id="nasil-calisir" class="scroll-mt-16 bg-navy-tint">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <h2 class="text-2xl font-bold text-ink">Nasıl çalışır?</h2>
            <ol class="mt-8 grid gap-6 sm:grid-cols-3">
                @foreach ([
                    ['Bilgilerinizi girin', 'Ürünü seçin, kısa formu doldurun.'],
                    ['Teklifleri karşılaştırın', 'Anlaşmalı şirketlerin teklifleri tek ekranda.'],
                    ['Poliçenizi alın', 'Uzman ekibimiz poliçeleştirmeyi tamamlar.'],
                ] as $i => [$baslik, $aciklama])
                    <li class="rounded-xl bg-white p-6 shadow-sm">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent/10 text-sm font-bold text-accent-dark">{{ $i + 1 }}</span>
                        <p class="mt-3 font-semibold text-navy">{{ $baslik }}</p>
                        <p class="mt-1 text-sm text-muted">{{ $aciklama }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endsection
