<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hesabım') — {{ config('digisure.agency.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy-tint text-ink antialiased">
    <header class="border-b border-line bg-white">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-3">
            <a href="{{ url('/') }}"><x-brand.logo /></a>
            <nav class="flex items-center gap-4 text-sm font-medium text-muted">
                <a href="{{ route('customer.quotes.index') }}" class="{{ request()->routeIs('customer.quotes.*') ? 'text-navy' : 'hover:text-navy' }}">Tekliflerim</a>
                <a href="/hesabim/policeler" class="{{ request()->is('hesabim/policeler*') ? 'text-navy' : 'hover:text-navy' }}">Poliçelerim</a>
                <a href="{{ route('customer.risk.show') }}" class="{{ request()->routeIs('customer.risk.*') ? 'text-navy' : 'hover:text-navy' }}">Risklerim</a>
                <a href="{{ route('customer.campaign.show') }}" class="{{ request()->routeIs('customer.campaign.*') ? 'text-navy' : 'hover:text-navy' }}">Kampanya</a>
                <a href="{{ route('customer.profile.edit') }}" class="{{ request()->routeIs('customer.profile.*') ? 'text-navy' : 'hover:text-navy' }}">Profilim</a>
                <form method="POST" action="{{ route('customer.logout') }}">@csrf
                    <button class="hover:text-danger">Çıkış</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-10">
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-ok/30 bg-ok/10 px-4 py-3 text-sm text-ok">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
