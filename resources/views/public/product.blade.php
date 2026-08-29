@extends('layouts.public')

@php
    $copy = [
        'trafik' => [
            'lead' => 'Zorunlu trafik sigortası (KTK) poliçenizi anlaşmalı şirketlerden karşılaştırın.',
            'points' => ['Yasal zorunlu teminatlar', 'Anlaşmalı servis avantajları', 'Hızlı poliçeleştirme'],
        ],
        'kasko' => [
            'lead' => 'Aracınızı çarpma, çalınma, yangın ve doğal afetlere karşı kapsamlı güvence altına alın.',
            'points' => ['Genişletilmiş teminat paketleri', 'İkame araç ve asistans', 'Cam & mini onarım seçenekleri'],
        ],
        'saglik' => [
            'lead' => 'Tamamlayıcı ve özel sağlık sigortası planlarını ihtiyaçlarınıza göre karşılaştırın.',
            'points' => ['SGK anlaşmalı hastanelerde katkısız tedavi', 'Yatarak & ayakta tedavi seçenekleri', 'Geniş kurum ağı'],
        ],
    ][$product->key] ?? ['lead' => '', 'points' => []];
@endphp

@section('title', $product->name . ' — ' . config('digisure.agency.name'))

@section('content')
    <section class="bg-navy text-white">
        <div class="mx-auto max-w-5xl px-4 py-16">
            <h1 class="text-3xl font-extrabold sm:text-4xl">{{ $product->name }}</h1>
            <p class="mt-3 max-w-2xl text-white/80">{{ $copy['lead'] }}</p>
            <a href="{{ url('/teklif?urun=' . $product->key) }}"
               class="mt-6 inline-block rounded-lg bg-accent px-6 py-3 font-semibold text-white transition hover:bg-accent-dark">
                Teklif Al
            </a>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-4 py-14">
        <h2 class="text-xl font-bold text-ink">Neden {{ config('digisure.agency.name') }}?</h2>
        <ul class="mt-6 grid gap-4 sm:grid-cols-3">
            @foreach ($copy['points'] as $point)
                <li class="rounded-xl border border-line bg-white p-5 text-sm text-muted shadow-sm">{{ $point }}</li>
            @endforeach
        </ul>
    </section>
@endsection
