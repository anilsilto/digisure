@extends('layouts.public')

@section('title', 'Hesabım — Kod Doğrulama')

@section('content')
    <section class="mx-auto max-w-md px-4 py-16">
        <h1 class="text-2xl font-extrabold text-ink">Doğrulama Kodu</h1>
        <p class="mt-2 text-sm text-muted">Telefonunuza gönderilen 6 haneli kodu girin.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-danger/30 bg-danger-tint px-3 py-2 text-sm text-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('customer.verify.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="tc_no" value="{{ old('tc_no', $tc_no) }}">
            <input type="hidden" name="telefon" value="{{ old('telefon', $telefon) }}">
            <div>
                <label class="block text-sm font-medium text-ink">Kod</label>
                <input type="text" name="kod" inputmode="numeric" maxlength="6" required autofocus
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-center text-lg tracking-[0.4em] focus:border-navy focus:outline-none">
            </div>
            <button type="submit" class="w-full rounded-lg bg-navy px-4 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">
                Giriş Yap
            </button>
        </form>

        <form method="POST" action="{{ route('customer.login.start') }}" class="mt-3">
            @csrf
            <input type="hidden" name="tc_no" value="{{ $tc_no }}">
            <input type="hidden" name="telefon" value="{{ $telefon }}">
            <button type="submit" class="text-sm text-navy underline">Kodu tekrar gönder</button>
        </form>
    </section>
@endsection
