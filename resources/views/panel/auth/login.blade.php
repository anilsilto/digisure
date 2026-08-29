<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Girişi — {{ config('digisure.agency.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-navy-tint px-4 antialiased">
    <div class="w-full max-w-sm rounded-2xl border border-line bg-white p-8 shadow-sm">
        <x-brand.logo />
        <h1 class="mt-4 text-xl font-bold text-ink">Acente Paneli Girişi</h1>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-zred/30 bg-zred-tint px-3 py-2 text-sm text-zred-dark">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('panel.login.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-ink">E-posta</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Şifre</label>
                <input type="password" name="password" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm text-muted">
                <input type="checkbox" name="remember" value="1"> Beni hatırla
            </label>
            <button type="submit"
                    class="w-full rounded-lg bg-navy px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-navy-dark">
                Giriş Yap
            </button>
        </form>
    </div>
</body>
</html>
