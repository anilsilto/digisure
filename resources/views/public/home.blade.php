@extends('layouts.public')

@section('content')
    <section class="relative overflow-hidden bg-navy text-white">
        <div class="pointer-events-none absolute left-1/2 top-1/3 hidden h-[42rem] w-[42rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-accent/15 blur-3xl lg:block"></div>

        <div class="relative mx-auto max-w-5xl px-4 py-14 text-center lg:py-20">
            <h1 class="sr-only">{{ config('digisure.agency.name') }} — {{ config('digisure.agency.slogan') }}</h1>

            <div class="mb-5 flex items-center justify-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-accent"></span>
                <span class="h-2 w-2 rounded-full bg-accent/70"></span>
                <span class="h-2 w-2 rounded-full bg-accent/40"></span>
                <span class="ml-2 text-sm font-semibold uppercase tracking-wider text-white/70">{{ config('digisure.agency.name') }}</span>
            </div>

            @php $teklifGorsel = collect(['png', 'webp', 'jpg'])->map(fn ($e) => "img/teklif.$e")->first(fn ($r) => file_exists(public_path($r))); @endphp
            @if ($teklifGorsel)
                <img src="{{ asset($teklifGorsel) }}" alt="Anlaşmalı şirketlerin tekliflerini karşılaştırın"
                     class="mx-auto w-full max-w-3xl rounded-2xl shadow-2xl shadow-black/40 ring-1 ring-white/10">
            @endif

            <p class="mx-auto mt-8 max-w-2xl text-lg text-white/80">
                Trafik, kasko ve sağlık sigortasında anlaşmalı şirketlerin tekliflerini tek ekranda karşılaştırın, uzman ekiple poliçenizi tamamlayın.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('/teklif') }}"
                   class="rounded-lg bg-accent px-7 py-3.5 font-semibold text-white shadow-lg shadow-accent/25 transition hover:bg-accent-dark">
                    Hemen Teklif Al
                </a>
                <a href="#nasil-calisir" class="text-sm font-semibold text-white/80 underline-offset-4 hover:text-white hover:underline">
                    Nasıl çalışır?
                </a>
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
        <div class="mx-auto max-w-6xl px-4 py-16 lg:py-20">
            <h2 class="text-2xl font-bold text-ink sm:text-3xl">Nasıl çalışır?</h2>
            <p class="mt-2 max-w-2xl text-muted">Teklif talebinden poliçeye ve yenileme hatırlatmasına kadar dört adım. Formu doldurmanız yeterli, gerisini uzman ekibimiz yürütür.</p>

            <ol class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $steps = [
                        [
                            'baslik' => 'Ürününüzü seçin, formu doldurun',
                            'aciklama' => 'Trafik, kasko veya sağlık — ihtiyacınıza göre seçin ve size özel kısa formu doldurun. KVKK aydınlatma onayını verip talebinizi gönderin; anında referans numaranızı alırsınız.',
                            'detay' => ['Gerekli: araçta plaka & ruhsat, sağlıkta doğum tarihi', 'Yaklaşık 2 dakika'],
                        ],
                        [
                            'baslik' => 'Anlaşmalı şirketlerden teklif toplayalım',
                            'aciklama' => 'Ekibimiz Sompo, Quick, HEPİYİ ve Doğa Sigorta’dan sizin için teklif ister. Teklifleriniz hazır olduğunda SMS ile haber veririz.',
                            'detay' => ['4 anlaşmalı şirket', 'Genellikle aynı gün'],
                        ],
                        [
                            'baslik' => 'Teklifleri tek ekranda karşılaştırın',
                            'aciklama' => 'TC ve telefonunuzla Hesabım’a giriş yapın; şifre yok, SMS ile gelen kodu girmeniz yeterli. Prim, teminat ve poliçe süresini yan yana görüp size en uygun teklifi seçin.',
                            'detay' => ['Girişte şifre yok', 'Prim + teminat karşılaştırması'],
                        ],
                        [
                            'baslik' => 'Poliçenizi alın, yenilemeyi kaçırmayın',
                            'aciklama' => 'Seçiminizin ardından ekibimiz poliçeleştirmeyi tamamlar; poliçe belgeniz Hesabım’da hazır olur. Bitiş tarihi yaklaşınca SMS ve e-posta ile hatırlatırız.',
                            'detay' => ['Poliçe PDF hesabınızda', 'Bitişe 30 / 15 / 7 gün kala hatırlatma'],
                        ],
                    ];
                @endphp

                @foreach ($steps as $i => $step)
                    <li class="flex flex-col rounded-xl bg-white p-6 shadow-sm">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-accent/10 text-base font-bold text-accent-dark">{{ $i + 1 }}</span>
                        <h3 class="mt-4 font-semibold text-navy">{{ $step['baslik'] }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-muted">{{ $step['aciklama'] }}</p>
                        <ul class="mt-4 space-y-1.5 border-t border-line pt-3 text-xs text-muted">
                            @foreach ($step['detay'] as $d)
                                <li class="flex gap-2">
                                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-accent"></span>
                                    <span>{{ $d }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ol>

            <div class="mt-10">
                <a href="{{ url('/teklif') }}"
                   class="inline-block rounded-lg bg-accent px-7 py-3.5 font-semibold text-white shadow-lg shadow-accent/25 transition hover:bg-accent-dark">
                    Hemen Teklif Al
                </a>
            </div>
        </div>
    </section>
@endsection
