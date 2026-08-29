<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — {{ config('digisure.agency.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy-tint text-ink antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden w-60 shrink-0 flex-col border-r border-line bg-white md:flex">
            <div class="border-b border-line px-5 py-4">
                <x-brand.logo />
                <p class="mt-1 text-xs text-muted">Acente Paneli</p>
            </div>
            <nav class="flex-1 space-y-1 p-3 text-sm">
                @php
                    $item = 'block rounded-lg px-3 py-2 font-medium text-muted hover:bg-navy-tint hover:text-navy';
                    $active = 'block rounded-lg px-3 py-2 font-medium bg-navy text-white';
                @endphp
                <a href="{{ route('panel.dashboard') }}" class="{{ request()->routeIs('panel.dashboard') ? $active : $item }}">Dashboard</a>
                <a href="/panel/teklifler" class="{{ request()->is('panel/teklifler*') ? $active : $item }}">Teklifler</a>
                <a href="/panel/policeler" class="{{ request()->is('panel/policeler*') ? $active : $item }}">Poliçeler</a>
                <a href="/panel/musteriler" class="{{ request()->is('panel/musteriler*') ? $active : $item }}">Müşteriler</a>
                <a href="{{ route('panel.data-requests.index') }}" class="{{ request()->is('panel/veri-talepleri*') ? $active : $item }}">KVKK Talepleri</a>

                @can('panel.admin')
                    <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-muted/70">Yönetim</p>
                    <a href="{{ route('panel.products.index') }}" class="{{ request()->is('panel/urunler*') ? $active : $item }}">Ürünler</a>
                    <a href="/panel/kullanicilar" class="{{ request()->is('panel/kullanicilar*') ? $active : $item }}">Kullanıcılar</a>
                    <a href="/panel/ayarlar" class="{{ request()->is('panel/ayarlar*') ? $active : $item }}">Ayarlar</a>
                @endcan
            </nav>
            <form method="POST" action="{{ route('panel.logout') }}" class="border-t border-line p-3">
                @csrf
                <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-muted hover:bg-zred-tint hover:text-zred">
                    Çıkış
                </button>
            </form>
        </aside>

        <div class="flex-1">
            <header class="flex items-center justify-between border-b border-line bg-white px-6 py-3">
                <h1 class="text-lg font-semibold text-ink">@yield('heading', 'Panel')</h1>
                <span class="text-sm text-muted">{{ auth('panel')->user()?->name }}</span>
            </header>

            <main class="p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-ok/30 bg-ok/10 px-4 py-3 text-sm text-ok">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
