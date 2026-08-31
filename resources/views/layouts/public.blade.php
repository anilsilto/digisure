<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('digisure.agency.name') . ' — ' . config('digisure.agency.slogan'))</title>
    <meta name="description" content="@yield('meta_description', config('digisure.agency.slogan'))">
    <link rel="canonical" href="{{ url()->current() }}">
    @yield('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-ink antialiased flex flex-col">

    @php
        $navLinks = [
            ['/hesaplama', 'Hesaplama Araçları'],
            ['/blog', 'Blog'],
            ['/hakkimizda', 'Hakkımızda'],
            ['/iletisim', 'İletişim'],
            ['/hesabim', 'Hesabım'],
        ];
        $urunLinks = \App\Models\ProductType::active()->get()
            ->map(fn ($p) => ['/' . $p->key . '-sigortasi', $p->name])
            ->all();
    @endphp

    <header x-data="{ mobil: false }" class="sticky top-0 z-40 border-b border-line bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-2">
            <a href="{{ url('/') }}" class="flex items-center">
                <x-brand.logo class="h-14 md:h-20" />
            </a>

            <nav class="hidden items-center gap-7 text-base font-semibold text-navy lg:flex">
                <div class="group relative">
                    <button type="button" class="hover:text-accent">Ürünlerimiz</button>
                    <div class="invisible absolute left-0 top-full z-20 w-56 rounded-lg border border-line bg-white p-2 opacity-0 shadow-lg transition group-hover:visible group-hover:opacity-100">
                        @foreach ($urunLinks as [$href, $etiket])
                            <a href="{{ url($href) }}" class="block rounded px-3 py-2 text-base text-navy hover:bg-navy-tint hover:text-accent">{{ $etiket }}</a>
                        @endforeach
                    </div>
                </div>
                @foreach ($navLinks as [$href, $etiket])
                    <a href="{{ url($href) }}" class="hover:text-accent">{{ $etiket }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <a href="tel:{{ config('digisure.agency.phone_e164') }}"
                   class="hidden items-center gap-2 text-sm font-semibold text-navy hover:text-accent sm:flex">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.3a1 1 0 01.95.68l1 3a1 1 0 01-.5 1.2L7.5 9.5a12 12 0 007 7l1.62-1.25a1 1 0 011.2-.5l3 1a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/>
                    </svg>
                    {{ config('digisure.agency.phone') }}
                </a>

                <a href="{{ url('/teklif') }}"
                   class="rounded-lg bg-accent px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-accent-dark sm:px-6 sm:py-3 sm:text-base">
                    Teklif Al
                </a>

                <button type="button" @click="mobil = !mobil" class="text-navy lg:hidden" aria-label="Menü">
                    <svg x-show="!mobil" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                    <svg x-show="mobil" x-cloak class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobil menü --}}
        <nav x-show="mobil" x-cloak x-collapse class="border-t border-line bg-white px-4 py-3 text-base font-semibold text-navy lg:hidden">
            <p class="px-1 pb-1 pt-2 text-xs font-bold uppercase tracking-wider text-muted">Ürünlerimiz</p>
            @foreach ($urunLinks as [$href, $etiket])
                <a href="{{ url($href) }}" class="block rounded px-1 py-2 hover:text-accent">{{ $etiket }}</a>
            @endforeach
            <div class="my-2 border-t border-line"></div>
            @foreach ($navLinks as [$href, $etiket])
                <a href="{{ url($href) }}" class="block rounded px-1 py-2 hover:text-accent">{{ $etiket }}</a>
            @endforeach
            <a href="tel:{{ config('digisure.agency.phone_e164') }}" class="block rounded px-1 py-2 text-accent">{{ config('digisure.agency.phone') }}</a>
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-line bg-navy-tint">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 text-sm text-muted sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <x-brand.logo class="mb-3 h-12" />
                <p>{{ config('digisure.agency.slogan') }}</p>
                <p class="mt-2 text-xs">{{ config('digisure.agency.licence') }}</p>
            </div>
            <div>
                <h3 class="mb-2 font-semibold text-ink">Ürünler</h3>
                <ul class="space-y-1">
                    @foreach ($urunLinks as [$href, $etiket])
                        <li><a href="{{ url($href) }}" class="hover:text-navy">{{ $etiket }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="mb-2 font-semibold text-ink">Kurumsal</h3>
                <ul class="space-y-1">
                    <li><a href="{{ url('/hakkimizda') }}" class="hover:text-navy">Hakkımızda</a></li>
                    <li><a href="{{ url('/blog') }}" class="hover:text-navy">Blog</a></li>
                    <li><a href="{{ url('/hesaplama') }}" class="hover:text-navy">Hesaplama Araçları</a></li>
                    <li><a href="{{ url('/kvkk-aydinlatma') }}" class="hover:text-navy">KVKK Aydınlatma Metni</a></li>
                    <li><a href="{{ url('/iletisim') }}" class="hover:text-navy">İletişim</a></li>
                </ul>
            </div>
            <div>
                <h3 class="mb-2 font-semibold text-ink">İletişim</h3>
                <p><a href="tel:{{ config('digisure.agency.phone_e164') }}" class="hover:text-navy">{{ config('digisure.agency.phone') }}</a></p>
                <p><a href="mailto:{{ config('digisure.agency.email') }}" class="hover:text-navy">{{ config('digisure.agency.email') }}</a></p>
                <p class="mt-1">{{ config('digisure.agency.address') }}</p>
            </div>
        </div>
        <div class="border-t border-line py-4 text-center text-xs text-muted">
            &copy; {{ date('Y') }} {{ config('digisure.agency.name') }}. Tüm hakları saklıdır.
        </div>
    </footer>

    {{-- WhatsApp --}}
    <a href="https://wa.me/{{ config('digisure.agency.phone_e164') }}" target="_blank" rel="noopener"
       class="fixed bottom-5 right-5 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105"
       aria-label="WhatsApp">
        <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.945C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.59 5.344l-.999 3.648 3.909-1.291zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
    </a>

</body>
</html>
