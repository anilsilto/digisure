@extends('layouts.public')

@section('title', 'Hesaplama Araçları — ' . config('digisure.agency.name'))

@section('content')
    <section class="mx-auto max-w-4xl px-4 py-16">
        <h1 class="text-3xl font-extrabold text-ink">Hesaplama Araçları</h1>
        <p class="mt-2 text-sm text-muted">Tahmini değerler; nihai tutarlar için tekliflerinizi karşılaştırın.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            @foreach ([
                'calc.mtv' => ['MTV Hesaplama', 'Motor hacmi ve araç yaşına göre yıllık motorlu taşıtlar vergisi.'],
                'calc.otv' => ['ÖTV Hesaplama', 'Motor hacmi ve matraha göre özel tüketim vergisi.'],
                'calc.fuel' => ['Yakıt Maliyeti', 'Mesafe, tüketim ve yakıt fiyatına göre yol maliyeti.'],
                'calc.kasko' => ['Kasko Değer', 'Referans değer ve orana göre tahmini kasko prim aralığı.'],
            ] as $route => [$title, $desc])
                <a href="{{ route($route) }}" class="rounded-xl border border-line bg-white p-5 shadow-sm transition hover:shadow-md">
                    <h2 class="font-semibold text-navy">{{ $title }}</h2>
                    <p class="mt-1 text-sm text-muted">{{ $desc }}</p>
                </a>
            @endforeach
        </div>
    </section>
@endsection
