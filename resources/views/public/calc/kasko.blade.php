@extends('layouts.public')

@section('title', 'Kasko Değer Hesaplama')

@section('content')
    <section class="mx-auto max-w-lg px-4 py-16">
        <a href="{{ route('calc.index') }}" class="text-sm text-navy hover:underline">← Hesaplama Araçları</a>
        <h1 class="mt-2 text-2xl font-extrabold text-ink">Kasko Değer / Prim Tahmini</h1>
        <p class="mt-2 text-xs text-muted">
            TSB Kasko Değer Listesi entegre değildir. Aracınızın güncel piyasa/referans değerini
            girin; bu yalnızca kaba bir tahmindir.
        </p>

        <form method="POST" action="{{ route('calc.kasko') }}" class="mt-6 space-y-4 rounded-xl border border-line bg-white p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-ink">Referans Araç Değeri (TL)</label>
                <input type="number" step="0.01" name="referans_deger" value="{{ old('referans_deger') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('referans_deger') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Kasko Oranı (%) — boşsa varsayılan</label>
                <input type="number" step="0.1" name="oran" value="{{ old('oran') }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <button class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Hesapla</button>
        </form>

        @if (! is_null($result))
            <div class="mt-4 rounded-xl border border-navy/20 bg-navy-tint p-5 text-sm">
                <p>Tahmini prim aralığı:
                    <strong class="text-navy">{{ number_format($result['low'], 2, ',', '.') }} – {{ number_format($result['high'], 2, ',', '.') }} TL</strong>
                </p>
                <p class="mt-1 text-xs text-muted">Kesin fiyat için teklif alın.</p>
            </div>
        @endif
    </section>
@endsection
