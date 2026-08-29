<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('digisure.agency.name') . ' — ' . config('digisure.agency.slogan'))</title>
    <meta name="description" content="@yield('meta_description', config('digisure.agency.slogan'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-ink antialiased flex flex-col">

    <header class="border-b border-line bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
            <a href="{{ url('/') }}" class="flex items-center">
                <x-brand.logo class="h-11 md:h-12" />
            </a>

            <nav class="hidden items-center gap-6 text-sm font-medium text-muted md:flex">
                <div class="group relative">
                    <button type="button" class="hover:text-navy">Ürünlerimiz</button>
                    <div class="invisible absolute left-0 top-full z-20 w-52 rounded-lg border border-line bg-white p-2 opacity-0 shadow-lg transition group-hover:visible group-hover:opacity-100">
                        <a href="{{ url('/trafik-sigortasi') }}" class="block rounded px-3 py-2 hover:bg-navy-tint">Trafik Sigortası</a>
                        <a href="{{ url('/kasko-sigortasi') }}" class="block rounded px-3 py-2 hover:bg-navy-tint">Kasko Sigortası</a>
                        <a href="{{ url('/saglik-sigortasi') }}" class="block rounded px-3 py-2 hover:bg-navy-tint">Sağlık Sigortası</a>
                    </div>
                </div>
                <a href="{{ url('/hesaplama') }}" class="hover:text-navy">Hesaplama Araçları</a>
                <a href="{{ url('/hakkimizda') }}" class="hover:text-navy">Hakkımızda</a>
                <a href="{{ url('/iletisim') }}" class="hover:text-navy">İletişim</a>
                <a href="{{ url('/hesabim') }}" class="hover:text-navy">Hesabım</a>
            </nav>

            <a href="{{ url('/teklif') }}"
               class="rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-accent-dark">
                Teklif Al
            </a>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-line bg-navy-tint">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 text-sm text-muted md:grid-cols-3">
            <div>
                <x-brand.logo class="mb-3" />
                <p>{{ config('digisure.agency.slogan') }}</p>
                <p class="mt-2 text-xs">{{ config('digisure.agency.licence') }}</p>
            </div>
            <div>
                <h3 class="mb-2 font-semibold text-ink">Ürünler</h3>
                <ul class="space-y-1">
                    <li><a href="{{ url('/trafik-sigortasi') }}" class="hover:text-navy">Trafik Sigortası</a></li>
                    <li><a href="{{ url('/kasko-sigortasi') }}" class="hover:text-navy">Kasko Sigortası</a></li>
                    <li><a href="{{ url('/saglik-sigortasi') }}" class="hover:text-navy">Sağlık Sigortası</a></li>
                </ul>
            </div>
            <div>
                <h3 class="mb-2 font-semibold text-ink">İletişim</h3>
                <p>{{ config('digisure.agency.phone') }}</p>
                <p>{{ config('digisure.agency.email') }}</p>
                <p class="mt-1">{{ config('digisure.agency.address') }}</p>
            </div>
        </div>
        <div class="border-t border-line py-4 text-center text-xs text-muted">
            &copy; {{ date('Y') }} {{ config('digisure.agency.name') }}. Tüm hakları saklıdır.
        </div>
    </footer>

</body>
</html>
