@extends('layouts.public')

@section('content')
    {{-- ============ HERO ============ --}}
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
                <div class="mx-auto w-full max-w-6xl overflow-hidden rounded-2xl shadow-2xl shadow-black/40 ring-1 ring-white/10">
                    <img src="{{ asset($teklifGorsel) }}" alt="Anlaşmalı şirketlerin tekliflerini karşılaştırın"
                         class="h-72 w-full object-cover object-[50%_12%] sm:h-80 lg:h-[26rem]">
                </div>
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

    {{-- ============ GÜVEN ŞERİDİ ============ --}}
    <section class="border-b border-line bg-white">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 py-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['ic' => 'M12 3l8 4v5c0 5-3.4 8.4-8 9-4.6-.6-8-4-8-9V7l8-4z', 'b' => 'SEDDK lisanslı broker', 's' => 'Yetkili sigorta brokerliği'],
                ['ic' => 'M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'b' => config('digisure.agency.experience_years') . '+ yıl deneyim', 's' => 'Köklü acente ekibi'],
                ['ic' => 'M4 7h16M4 12h16M4 17h10', 'b' => '4 anlaşmalı şirket', 's' => 'Sompo · Quick · HEPİYİ · Doğa'],
                ['ic' => 'M18 8a6 6 0 00-12 0v5l-2 3h16l-2-3V8zM9 21h6', 'b' => '7/24 hasar & destek', 's' => 'Süreç boyunca yanınızdayız'],
            ] as $item)
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-accent/10 text-accent-dark">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['ic'] }}"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-semibold text-ink">{{ $item['b'] }}</p>
                        <p class="text-sm text-muted">{{ $item['s'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ ÜRÜNLER ============ --}}
    <section class="mx-auto max-w-6xl px-4 py-16">
        <h2 class="text-2xl font-bold text-ink sm:text-3xl">Ürünlerimiz</h2>
        <p class="mt-2 text-muted">İhtiyacınıza uygun ürünü seçin, birkaç dakikada teklif talebi oluşturun.</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            @php
                $urunMeta = [
                    'trafik' => ['Zorunlu trafik sigortanızı anlaşmalı şirketlerden en uygun fiyata bulun.', 'M3 13l2-5a3 3 0 013-2h8a3 3 0 013 2l2 5M5 17h14M6 17v2M18 17v2M7 13h10'],
                    'kasko' => ['Aracınızı çarpma, çalınma, yangın ve doğal afetlere karşı kapsamlı güvenceye alın.', 'M12 3l8 4v5c0 5-3.4 8.4-8 9-4.6-.6-8-4-8-9V7l8-4zM9.5 12l1.8 1.8L15 10'],
                    'saglik' => ['Tamamlayıcı ve özel sağlık planlarını ihtiyaçlarınıza göre karşılaştırın.', 'M12 21C7 17 4 13.5 4 9.5A4.5 4.5 0 0112 6a4.5 4.5 0 018 3.5C20 13.5 17 17 12 21z'],
                ];
            @endphp
            @foreach ($products as $product)
                @php [$aciklama, $ikon] = $urunMeta[$product->key] ?? ['', '']; @endphp
                <div class="flex flex-col rounded-xl border border-line bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-navy-tint text-navy">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ikon }}"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-navy">{{ $product->name }}</h3>
                    <p class="mt-2 flex-1 text-sm text-muted">{{ $aciklama }}</p>
                    <a href="{{ route('urun.show', $product->key) }}"
                       class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-accent hover:text-accent-dark">
                        Detay & Teklif Al <span aria-hidden="true">→</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ NASIL ÇALIŞIR ============ --}}
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

    {{-- ============ ANLAŞMALI ŞİRKETLER ============ --}}
    <section class="mx-auto max-w-6xl px-4 py-16">
        <h2 class="text-2xl font-bold text-ink sm:text-3xl">Anlaşmalı sigorta şirketleri</h2>
        <p class="mt-2 text-muted">Teklifleriniz bu şirketlerin ürünleri arasından karşılaştırılır.</p>
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach (config('digisure.insurer_labels') as $label)
                <div class="flex items-center justify-center rounded-xl border border-line bg-white px-4 py-6 text-center font-semibold text-navy shadow-sm">
                    {{ $label }}
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ NEDEN POLİSURANCE ============ --}}
    <section class="bg-navy-tint">
        <div class="mx-auto max-w-6xl px-4 py-16 lg:py-20">
            <h2 class="text-2xl font-bold text-ink sm:text-3xl">Neden {{ config('digisure.brand') }}?</h2>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Tarafsız karşılaştırma', 'Belirli bir şirketi değil, ihtiyacınıza ve bütçenize en uygun teklifi öne çıkarırız.'],
                    ['Tek noktadan yönetim', 'Teklif talepleri, poliçeler ve yenileme hatırlatmaları aynı hesapta toplanır.'],
                    ['Uzman acente desteği', config('digisure.agency.experience_years') . '+ yıllık ekibimiz poliçeleştirmeyi ve hasar sürecini üstlenir.'],
                    ['KVKK güvencesi', 'Kimlik ve iletişim bilgileriniz şifreli saklanır; yalnızca teklif ve poliçe süreçleri için kullanılır.'],
                ] as $i => [$baslik, $aciklama])
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent/10 text-sm font-bold text-accent-dark">{{ $i + 1 }}</span>
                        <h3 class="mt-3 font-semibold text-navy">{{ $baslik }}</h3>
                        <p class="mt-1 text-sm text-muted">{{ $aciklama }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ SSS ============ --}}
    <section class="mx-auto max-w-3xl px-4 py-16">
        <h2 class="text-2xl font-bold text-ink sm:text-3xl">Sık sorulan sorular</h2>
        <div class="mt-8 divide-y divide-line rounded-xl border border-line bg-white">
            @foreach ([
                ['Teklif almak ücretli mi?', 'Hayır. Teklif talebi oluşturmak ve teklifleri karşılaştırmak tamamen ücretsizdir; yalnızca satın aldığınız poliçenin primini ödersiniz.'],
                ['Kaç şirketten teklif alıyorsunuz?', 'Anlaşmalı olduğumuz Sompo, Quick, HEPİYİ ve Doğa Sigorta’dan teklif toplarız. Yeni anlaşmalar eklendikçe kapsam genişler.'],
                ['Teklifler ne zaman hazır olur?', 'Talebiniz genellikle aynı gün içinde yanıtlanır. Teklifleriniz hazır olduğunda size SMS ile bilgi veririz.'],
                ['Bilgilerim güvende mi?', 'T.C. kimlik numaranız ve telefonunuz şifreli olarak saklanır. Verileriniz yalnızca teklif ve poliçe süreçleri için, KVKK kapsamında işlenir; pazarlama izni vermediğiniz sürece başka amaçla kullanılmaz.'],
                ['Poliçemi nasıl görürüm?', '“Hesabım” bölümünden T.C. kimlik numaranız ve telefonunuzla giriş yapıp SMS ile gelen kodu girin. Poliçe belgenizi PDF olarak indirebilirsiniz.'],
                ['Yenileme hatırlatması yapıyor musunuz?', 'Evet. Poliçenizin bitişine 30, 15 ve 7 gün kala SMS ve e-posta ile hatırlatma göndeririz.'],
            ] as $i => [$soru, $cevap])
                <div x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }" class="px-5">
                    <button type="button" @click="open = !open"
                            class="flex w-full items-center justify-between gap-4 py-4 text-left font-semibold text-ink">
                        <span>{{ $soru }}</span>
                        <svg class="h-5 w-5 shrink-0 text-accent transition" :class="open && 'rotate-45'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                        </svg>
                    </button>
                    <p x-show="open" x-collapse class="pb-4 text-sm leading-relaxed text-muted">{{ $cevap }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ KAPANIŞ CTA ============ --}}
    <section class="bg-navy">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-6 px-4 py-14 text-center sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h2 class="text-2xl font-bold text-white sm:text-3xl">Birkaç dakikada teklifinizi alın</h2>
                <p class="mt-2 text-white/80">Formu doldurun, gerisini uzman ekibimiz halletsin.</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('/teklif') }}"
                   class="rounded-lg bg-accent px-7 py-3.5 font-semibold text-white shadow-lg shadow-accent/25 transition hover:bg-accent-dark">
                    Hemen Teklif Al
                </a>
                <a href="tel:{{ config('digisure.agency.phone_e164') }}"
                   class="font-semibold text-white underline-offset-4 hover:underline">
                    {{ config('digisure.agency.phone') }}
                </a>
            </div>
        </div>
    </section>
@endsection
