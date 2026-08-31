@extends('layouts.public')

@php
    $icerik = [
        'trafik' => [
            'ozet' => 'Zorunlu Mali Sorumluluk Sigortası (ZMS), halk arasındaki adıyla zorunlu trafik sigortası, trafiğe çıkan her motorlu aracın kanunen yaptırması gereken poliçedir. Kusurlu olduğunuz bir kazada karşı tarafın (üçüncü şahısların) uğradığı zararları, poliçe limitleri dâhilinde sigorta şirketi karşılar. Trafik sigortası kendi aracınızın veya kendi bedensel zararınızın masraflarını ödemez; bunun için kasko ve ferdi kaza teminatları gerekir.',
            'aciklama' => 'Trafik sigortası olmadan araç kullanmak idari para cezası, aracın trafikten men edilmesi ve kaza hâlinde tüm zararın şahsen ödenmesi anlamına gelir. Poliçe süresi bir yıldır ve bitiminden önce yenilenmelidir.',
            'kapsam' => [
                ['Maddi zararlar', 'Kazada hasar gören karşı tarafın aracı ve eşyaları.'],
                ['Sağlık giderleri', 'Yaralanan üçüncü şahısların tedavi ve iyileşme masrafları.'],
                ['Sürekli sakatlık', 'Kaza sonucu oluşan kalıcı iş göremezlik için tazminat.'],
                ['Ölüm teminatı', 'Kazada hayatını kaybeden üçüncü şahısların yakınlarına destekten yoksun kalma tazminatı.'],
                ['Manevi tazminat', 'Mahkeme kararıyla hükmedilen manevi tazminatlar (poliçede belirtilen limitle).'],
            ],
            'notlar' => 'Teminat limitleri Sigortacılık ve Özel Emeklilik Düzenleme ve Denetleme Kurumu (SEDDK) tarafından her yıl güncellenir; poliçe teklifinizde güncel limitler yer alır. Alkollü, ehliyetsiz veya kasıtlı sürüşte sigorta şirketi ödeme yapıp size rücu edebilir.',
        ],

        'kasko' => [
            'ozet' => 'Kasko sigortası, zorunlu trafik sigortasının aksine isteğe bağlıdır ve kendi aracınızı güvence altına alır. Çarpma, çarpışma, devrilme, yangın, hırsızlık ve doğal afet gibi risklerde aracınızda oluşan hasarı, poliçe türüne ve teminatlara göre karşılar. Aracınız yeni veya değerliyse kasko, olası bir kayıpta cebinizden büyük bir ödeme yapmanızın önüne geçer.',
            'aciklama' => 'Kasko poliçesinde teminat kapsamı, muafiyetler (hasarın sizde kalan kısmı), anlaşmalı servis şartı ve ek teminatlar şirketten şirkete değişir. Teklif alırken yalnızca prime değil, teminat içeriğine de bakmak gerekir.',
            'kapsam' => [
                ['Çarpma & çarpışma', 'Kaza sonucu araçta oluşan her türlü çarpma, çarpışma ve devrilme hasarı.'],
                ['Yangın & yıldırım', 'Kendiliğinden veya dış etkenle çıkan yangın, infilak ve yıldırım zararları.'],
                ['Hırsızlık', 'Aracın çalınması, çalınmaya teşebbüs veya parça hırsızlığı.'],
                ['Doğal afet', 'Sel, su baskını, dolu, fırtına, çığ, toprak kayması (poliçede belirtilmişse).'],
                ['Cam kırılması', 'Ön, arka ve yan camların kırılması; çoğu poliçede hasarsızlığı etkilemeden.'],
                ['Deprem', 'Deprem ve yanardağ püskürmesi kaynaklı araç hasarları (ek teminat olabilir).'],
            ],
            'turler' => [
                ['Tam kasko', 'En geniş kapsam. Çarpma, yangın, hırsızlık ve doğal afetlerin tamamını, genellikle ikame araç, asistans ve ferdi kaza gibi ek teminatlarla birlikte içerir.'],
                ['Dar kasko', 'Yalnızca seçilen risklere (örneğin çarpma-çarpışma veya sadece yangın-hırsızlık) teminat verir. Primi düşüktür, kapsamı sınırlıdır.'],
                ['Mini onarım / genişletilmiş', 'Küçük çizik ve göçükler için yılda belirli sayıda onarım, cam, anahtar kaybı, lastik gibi günlük hasarlara odaklı ek paketler.'],
            ],
            'notlar' => 'Kasko bedeli, Türkiye Sigorta Birliği (TSB) Kasko Değer Listesi esas alınarak belirlenir. Poliçe bedelinin aracın güncel rayiç değerine eşit olması, eksik veya aşkın sigortadan kaçınmak için önemlidir.',
        ],

        'saglik' => [
            'ozet' => 'Sağlık sigortası, hastane ve tedavi masraflarınızı güvence altına alan bir üründür. İki temel türü vardır: SGK’nız devam ederken özel hastane fark ücretlerini karşılayan Tamamlayıcı Sağlık Sigortası (TSS) ve SGK şartı aramadan geniş bir kurum ağında tedavi imkânı sunan Özel Sağlık Sigortası (ÖSS).',
            'aciklama' => 'TSS daha uygun primlidir ve SGK anlaşmalı özel hastanelerde geçerlidir; ÖSS ise daha yüksek primli, daha geniş hastane ağına ve teminat esnekliğine sahiptir. Doğru ürün, hastane tercihinize, bütçenize ve sağlık geçmişinize göre değişir.',
            'kapsam' => [
                ['Yatarak tedavi', 'Ameliyat, tetkik, yoğun bakım, oda-yemek ve refakat masrafları.'],
                ['Ayakta tedavi', 'Muayene, tahlil, görüntüleme ve reçeteli ilaç (limit ve katılım payıyla).'],
                ['Doğum', 'Normal doğum ve sezaryen giderleri (bekleme süresi sonrası, ek teminat).'],
                ['Acil durumlar', 'Kaza ve acil hastalıklarda ilk günden geçerli teminat.'],
                ['Ambulans & yurt dışı acil', 'Kara ambulansı ve yurt dışında acil sağlık giderleri (poliçeye göre).'],
                ['Diş (ek teminat)', 'Diş muayenesi, dolgu, çekim gibi işlemler için ilave paket.'],
            ],
            'turler' => [
                ['Tamamlayıcı Sağlık Sigortası (TSS)', 'SGK’lı olmanız şarttır. SGK anlaşmalı özel hastanede, SGK’nın karşılamadığı fark ücretini üstlenir. Uygun primli, en yaygın tercih.'],
                ['Özel Sağlık Sigortası (ÖSS)', 'SGK şartı yoktur. Anlaşmalı kurum listesindeki hastanelerde geniş teminatla tedavi sağlar. Primi daha yüksek, kapsamı daha esnektir.'],
            ],
            'notlar' => 'Poliçe öncesi var olan (mevcut) hastalıklar kapsam dışı bırakılabilir veya ek primle kapsanır. Doğum ve bazı planlı ameliyatlarda bekleme süresi uygulanır. Poliçeyi kesintisiz yenilediğinizde ömür boyu yenileme garantisi kazanabilirsiniz.',
        ],
    ][$product->key] ?? null;

    $neden = [
        ['Tarafsız karşılaştırma', 'Belirli bir şirketi değil, ihtiyacınıza ve bütçenize en uygun teklifi öne çıkarırız.'],
        ['Aynı gün teklif', 'Talebiniz genellikle aynı gün yanıtlanır; teklifler hazır olunca SMS ile bilgi veririz.'],
        ['Uzman acente desteği', config('digisure.agency.experience_years') . '+ yıllık ekip poliçeleştirme ve hasar sürecini üstlenir.'],
        ['SEDDK lisanslı broker', 'Tüm işlemler yetkili sigorta brokerliği çatısı altında yürütülür.'],
    ];

    $sss = [
        'trafik' => [
            ['Trafik sigortası olmadan araç kullanabilir miyim?', 'Hayır. Zorunludur; sigortasız araç kullanımı idari para cezası ve aracın trafikten men edilmesiyle sonuçlanır. Kaza yaparsanız karşı tarafın tüm zararını şahsen ödersiniz.'],
            ['Trafik sigortası kendi aracımın hasarını karşılar mı?', 'Hayır. Trafik sigortası yalnızca kusurlu olduğunuz kazada karşı tarafın zararını karşılar. Kendi aracınız için kasko gerekir.'],
            ['Primim neden değişiyor?', 'Hasarsızlık basamağınız, aracın özellikleri, tescil ili ve sürücü profili primi belirler. Kazasız her yıl basamağınız yükselir ve indiriminiz artar.'],
            ['Poliçemi ne zaman yenilemeliyim?', 'Bitiş tarihinden önce. Arada boşluk kalırsa hem sigortasız kalırsınız hem de kademe hakkınız etkilenebilir. Biz bitişe 30/15/7 gün kala hatırlatırız.'],
            ['Yurt dışına çıkarken geçerli mi?', 'Türkiye sınırları içinde geçerlidir. Yurt dışı için ayrıca Yeşil Kart (uluslararası motorlu taşıt sigortası) yaptırmanız gerekir.'],
        ],
        'kasko' => [
            ['Kasko zorunlu mu?', 'Hayır, isteğe bağlıdır. Ancak aracınız yeni veya değerliyse, olası bir hasar/hırsızlıkta maddi kaybı önlemek için güçlü bir tavsiyedir.'],
            ['Tam kasko ile dar kasko farkı nedir?', 'Tam kasko çarpma, yangın, hırsızlık ve doğal afetlerin tamamını kapsar. Dar kasko yalnızca seçtiğiniz risklere teminat verir; primi düşük, kapsamı sınırlıdır.'],
            ['Hasarsızlık indirimi kaskoda da var mı?', 'Evet. Kazasız geçen her yıl için kasko priminizde indirim kademesi yükselir. Kusurlu hasarda kademe düşebilir.'],
            ['İkinci el araca kasko yaptırılır mı?', 'Evet. Aracın yaşı ve durumuna göre şirketler ekspertiz isteyebilir; rayiç değer üzerinden poliçe düzenlenir.'],
            ['Kasko deprem hasarını karşılar mı?', 'Deprem ve yanardağ teminatı çoğu poliçede ek teminat olarak sunulur. Poliçenizde açıkça belirtilmişse karşılanır.'],
        ],
        'saglik' => [
            ['TSS ile ÖSS arasındaki fark nedir?', 'TSS için SGK’lı olmanız şarttır ve SGK anlaşmalı özel hastanelerde fark ücretini karşılar. ÖSS SGK şartı aramaz, daha geniş hastane ağı ve teminatla çalışır, primi daha yüksektir.'],
            ['SGK’lıyım, TSS yaptırmam gerekir mi?', 'Zorunlu değildir. Özel hastane hizmetini fark ücreti ödemeden almak istiyorsanız mantıklıdır.'],
            ['Mevcut hastalıklarım kapsanır mı?', 'Poliçe öncesi var olan rahatsızlıklar kapsam dışı bırakılabilir veya ek primle kapsanır. Başvuruda sağlık beyanını eksiksiz doldurmak çok önemlidir.'],
            ['Çocuklarım için yaptırabilir miyim?', 'Evet. Aile poliçesiyle eş ve çocukları tek poliçede toplayabilirsiniz.'],
            ['Bekleme süresi var mı?', 'Acil durumlar ilk günden geçerlidir. Doğum genellikle 12 ay, bazı planlı ameliyatlar 3–12 ay bekleme süresine tabidir.'],
        ],
    ][$product->key] ?? [];
@endphp

@section('title', $product->name . ' — ' . config('digisure.brand'))
@section('meta_description', $icerik['ozet'] ? \Illuminate\Support\Str::limit($icerik['ozet'], 155) : $product->name)

@section('content')
    <section class="bg-navy text-white">
        <div class="mx-auto max-w-5xl px-4 py-14 lg:py-20">
            <p class="text-sm font-semibold uppercase tracking-wider text-white/70">{{ config('digisure.agency.name') }}</p>
            <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">{{ $product->name }}</h1>
            @if ($icerik)
                <p class="mt-4 max-w-2xl text-white/80">{{ \Illuminate\Support\Str::limit($icerik['ozet'], 180) }}</p>
            @endif
            <a href="{{ url('/teklif?urun=' . $product->key) }}"
               class="mt-7 inline-block rounded-lg bg-accent px-7 py-3.5 font-semibold text-white shadow-lg shadow-accent/25 transition hover:bg-accent-dark">
                Ücretsiz Teklif Al
            </a>
        </div>
    </section>

    @if ($icerik)
        {{-- NEDİR --}}
        <section class="mx-auto max-w-3xl px-4 py-14">
            <h2 class="text-2xl font-bold text-ink sm:text-3xl">{{ $product->name }} Nedir?</h2>
            <p class="mt-4 leading-relaxed text-muted">{{ $icerik['ozet'] }}</p>
            <p class="mt-4 leading-relaxed text-muted">{{ $icerik['aciklama'] }}</p>
        </section>

        {{-- KAPSAM --}}
        <section class="bg-navy-tint">
            <div class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
                <h2 class="text-2xl font-bold text-ink sm:text-3xl">Neleri Kapsar?</h2>
                <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($icerik['kapsam'] as [$baslik, $aciklama])
                        <div class="rounded-xl bg-white p-5 shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-accent/10 text-accent-dark">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                </span>
                                <h3 class="font-semibold text-navy">{{ $baslik }}</h3>
                            </div>
                            <p class="mt-2 text-sm text-muted">{{ $aciklama }}</p>
                        </div>
                    @endforeach
                </div>
                @if (! empty($icerik['notlar']))
                    <p class="mt-6 rounded-lg border border-line bg-white p-4 text-sm text-muted">
                        <strong class="text-ink">Bilgi:</strong> {{ $icerik['notlar'] }}
                    </p>
                @endif
            </div>
        </section>

        {{-- TÜRLER (kasko + sağlık) --}}
        @if (! empty($icerik['turler']))
            <section class="mx-auto max-w-5xl px-4 py-14">
                <h2 class="text-2xl font-bold text-ink sm:text-3xl">{{ $product->name }} Türleri</h2>
                <div class="mt-8 space-y-4">
                    @foreach ($icerik['turler'] as [$baslik, $aciklama])
                        <div class="rounded-xl border border-line bg-white p-5 shadow-sm">
                            <h3 class="font-semibold text-navy">{{ $baslik }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-muted">{{ $aciklama }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endif

    {{-- NEDEN --}}
    <section class="bg-navy-tint">
        <div class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
            <h2 class="text-2xl font-bold text-ink sm:text-3xl">Neden {{ config('digisure.brand') }}?</h2>
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($neden as [$baslik, $aciklama])
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h3 class="font-semibold text-navy">{{ $baslik }}</h3>
                        <p class="mt-1 text-sm text-muted">{{ $aciklama }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SSS --}}
    @if (! empty($sss))
        <section class="mx-auto max-w-3xl px-4 py-14">
            <h2 class="text-2xl font-bold text-ink sm:text-3xl">Sık Sorulan Sorular</h2>
            <div class="mt-8 divide-y divide-line rounded-xl border border-line bg-white">
                @foreach ($sss as $i => [$soru, $cevap])
                    <div x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }" class="px-5">
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-4 py-4 text-left font-semibold text-ink">
                            <span>{{ $soru }}</span>
                            <svg class="h-5 w-5 shrink-0 text-accent transition" :class="open && 'rotate-45'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <p x-show="open" x-collapse class="pb-4 text-sm leading-relaxed text-muted">{{ $cevap }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="bg-navy">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-6 px-4 py-14 text-center sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h2 class="text-2xl font-bold text-white sm:text-3xl">{{ $product->name }} teklifinizi alın</h2>
                <p class="mt-2 text-white/80">Kısa formu doldurun, anlaşmalı şirketlerin tekliflerini karşılaştıralım.</p>
            </div>
            <a href="{{ url('/teklif?urun=' . $product->key) }}"
               class="rounded-lg bg-accent px-7 py-3.5 font-semibold text-white shadow-lg shadow-accent/25 transition hover:bg-accent-dark">
                Ücretsiz Teklif Al
            </a>
        </div>
    </section>
@endsection
