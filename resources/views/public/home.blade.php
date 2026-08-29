@extends('layouts.public')

@section('content')
    <section class="bg-navy text-white">
        <div class="mx-auto max-w-6xl px-4 py-20">
            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-white/70">{{ config('digisure.agency.name') }}</p>
            <h1 class="max-w-2xl text-4xl font-extrabold leading-tight sm:text-5xl">
                {{ config('digisure.agency.slogan') }}
            </h1>
            <p class="mt-4 max-w-xl text-white/80">
                Trafik, kasko ve sağlık sigortasında birden fazla şirketin teklifini tek ekranda karşılaştırın.
            </p>
            <a href="{{ url('/teklif') }}"
               class="mt-8 inline-block rounded-lg bg-zred px-6 py-3 font-semibold text-white shadow transition hover:bg-zred-dark">
                Hemen Teklif Al
            </a>
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
                       class="mt-4 inline-block text-sm font-semibold text-zred hover:text-zred-dark">
                        Detay & Teklif Al →
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="bg-navy-tint">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <h2 class="text-2xl font-bold text-ink">Nasıl çalışır?</h2>
            <ol class="mt-8 grid gap-6 sm:grid-cols-3">
                <li class="rounded-xl bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold text-zred">1</span>
                    <p class="mt-2 font-semibold text-navy">Bilgilerinizi girin</p>
                    <p class="mt-1 text-sm text-muted">Ürünü seçin, kısa formu doldurun.</p>
                </li>
                <li class="rounded-xl bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold text-zred">2</span>
                    <p class="mt-2 font-semibold text-navy">Teklifleri karşılaştırın</p>
                    <p class="mt-1 text-sm text-muted">Anlaşmalı şirketlerin teklifleri tek ekranda.</p>
                </li>
                <li class="rounded-xl bg-white p-6 shadow-sm">
                    <span class="text-sm font-bold text-zred">3</span>
                    <p class="mt-2 font-semibold text-navy">Poliçenizi alın</p>
                    <p class="mt-1 text-sm text-muted">Uzman ekibimiz poliçeleştirmeyi tamamlar.</p>
                </li>
            </ol>
        </div>
    </section>
@endsection
