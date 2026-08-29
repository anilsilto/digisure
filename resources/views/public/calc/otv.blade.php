@extends('layouts.public')

@section('title', 'ÖTV Hesaplama')

@section('content')
    <section class="mx-auto max-w-lg px-4 py-16">
        <a href="{{ route('calc.index') }}" class="text-sm text-navy hover:underline">← Hesaplama Araçları</a>
        <h1 class="mt-2 text-2xl font-extrabold text-ink">ÖTV Hesaplama</h1>

        <form method="POST" action="{{ route('calc.otv') }}" class="mt-6 space-y-4 rounded-xl border border-line bg-white p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-ink">Motor Hacmi (cc)</label>
                <input type="number" name="motor_cc" value="{{ old('motor_cc') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('motor_cc') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Matrah (KDV hariç fiyat, TL)</label>
                <input type="number" step="0.01" name="matrah" value="{{ old('matrah') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('matrah') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>
            <button class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Hesapla</button>
        </form>

        @if (! is_null($result))
            <div class="mt-4 rounded-xl border border-navy/20 bg-navy-tint p-5 text-sm">
                @if (is_array($result))
                    <p>ÖTV oranı: <strong class="text-navy">%{{ number_format($result['rate'], 0, ',', '.') }}</strong></p>
                    <p>ÖTV tutarı: <strong class="text-navy">{{ number_format($result['otv'], 2, ',', '.') }} TL</strong></p>
                    <p>ÖTV dahil: <strong class="text-navy">{{ number_format($result['total'], 2, ',', '.') }} TL</strong></p>
                    <p class="mt-1 text-xs text-muted">2026 taslak dilimler; KDV ayrıca uygulanır.</p>
                @else
                    <p class="text-zred">{{ $result }}</p>
                @endif
            </div>
        @endif
    </section>
@endsection
