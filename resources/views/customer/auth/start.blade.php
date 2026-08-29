@extends('layouts.public')

@section('title', 'Hesabım — Giriş')

@section('content')
    <section class="mx-auto max-w-md px-4 py-16">
        <h1 class="text-2xl font-extrabold text-ink">Hesabıma Giriş</h1>
        <p class="mt-2 text-sm text-muted">TC kimlik numaranız ve telefonunuzu girin, telefonunuza tek kullanımlık kod göndereceğiz.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-danger/30 bg-danger-tint px-3 py-2 text-sm text-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('customer.login.start') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-ink">TC Kimlik No</label>
                <input type="text" name="tc_no" value="{{ old('tc_no') }}" maxlength="11" inputmode="numeric" required autofocus
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Telefon</label>
                <input type="tel" name="telefon" value="{{ old('telefon') }}" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
            </div>
            <button type="submit" class="w-full rounded-lg bg-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">
                Kod Gönder
            </button>
        </form>
    </section>
@endsection
