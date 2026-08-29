@extends('layouts.public')

@section('title', 'MTV Hesaplama')

@section('content')
    <section class="mx-auto max-w-lg px-4 py-16">
        <a href="{{ route('calc.index') }}" class="text-sm text-navy hover:underline">← Hesaplama Araçları</a>
        <h1 class="mt-2 text-2xl font-extrabold text-ink">MTV Hesaplama</h1>

        <form method="POST" action="{{ route('calc.mtv') }}" class="mt-6 space-y-4 rounded-xl border border-line bg-white p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-ink">Motor Hacmi (cc)</label>
                <input type="number" name="motor_cc" value="{{ old('motor_cc') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('motor_cc') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Araç Yaşı</label>
                <input type="number" name="arac_yasi" value="{{ old('arac_yasi') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('arac_yasi') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>
            <button class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Hesapla</button>
        </form>

        @if (! is_null($result))
            <div class="mt-4 rounded-xl border border-navy/20 bg-navy-tint p-5">
                @if (is_int($result))
                    <p class="text-sm text-muted">Tahmini yıllık MTV</p>
                    <p class="text-2xl font-bold text-navy">{{ number_format($result, 2, ',', '.') }} TL</p>
                    <p class="mt-1 text-xs text-muted">2026 taslak tarife; kesin tutar için GİB'e bakınız.</p>
                @else
                    <p class="text-sm text-zred">{{ $result }}</p>
                @endif
            </div>
        @endif
    </section>
@endsection
