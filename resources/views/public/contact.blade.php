@extends('layouts.public')

@section('title', 'İletişim — ' . config('digisure.agency.name'))

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="text-3xl font-extrabold text-ink">İletişim</h1>

        @if (session('status'))
            <div class="mt-6 rounded-lg border border-ok/30 bg-ok/10 px-4 py-3 text-sm text-ok">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 grid gap-10 md:grid-cols-2">
            <div class="text-sm text-muted">
                <p><strong class="text-ink">Telefon:</strong> {{ config('digisure.agency.phone') }}</p>
                <p class="mt-1"><strong class="text-ink">E-posta:</strong> {{ config('digisure.agency.email') }}</p>
                <p class="mt-1"><strong class="text-ink">Adres:</strong> {{ config('digisure.agency.address') }}</p>
            </div>

            <form method="POST" action="{{ route('iletisim.send') }}" class="space-y-4">
                @csrf
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                <div>
                    <label class="block text-sm font-medium text-ink">Ad Soyad</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                    @error('name') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                    @error('phone') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink">E-posta (opsiyonel)</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                    @error('email') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink">Mesajınız</label>
                    <textarea name="message" rows="4" required
                              class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="rounded-lg bg-zred px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-zred-dark">
                    Gönder
                </button>
            </form>
        </div>
    </section>
@endsection
