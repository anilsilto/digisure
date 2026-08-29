@extends('layouts.public')

@section('title', 'Yakıt Maliyeti Hesaplama')

@section('content')
    <section class="mx-auto max-w-lg px-4 py-16">
        <a href="{{ route('calc.index') }}" class="text-sm text-navy hover:underline">← Hesaplama Araçları</a>
        <h1 class="mt-2 text-2xl font-extrabold text-ink">Yakıt Maliyeti</h1>

        <form method="POST" action="{{ route('calc.fuel') }}" class="mt-6 space-y-4 rounded-xl border border-line bg-white p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-ink">Mesafe (km)</label>
                <input type="number" step="0.1" name="mesafe" value="{{ old('mesafe') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('mesafe') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Tüketim (L/100km) — boşsa varsayılan</label>
                <input type="number" step="0.1" name="tuketim" value="{{ old('tuketim') }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Yakıt Fiyatı (TL/L) — boşsa varsayılan</label>
                <input type="number" step="0.01" name="fiyat" value="{{ old('fiyat') }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <button class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Hesapla</button>
        </form>

        @if (! is_null($result))
            <div class="mt-4 rounded-xl border border-navy/20 bg-navy-tint p-5 text-sm">
                <p>Tüketilen yakıt: <strong class="text-navy">{{ number_format($result['litres'], 2, ',', '.') }} L</strong></p>
                <p>Tahmini maliyet: <strong class="text-navy">{{ number_format($result['cost'], 2, ',', '.') }} TL</strong></p>
            </div>
        @endif
    </section>
@endsection
