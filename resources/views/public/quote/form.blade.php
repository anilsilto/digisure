@extends('layouts.public')

@section('title', 'Teklif Al — ' . config('digisure.agency.name'))

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-14">
        <h1 class="text-3xl font-extrabold text-ink">Teklif Al</h1>
        <p class="mt-2 text-sm text-muted">Ürünü seçin, kısa formu doldurun; anlaşmalı şirketlerin teklifleri hazır olunca size SMS ile haber vereceğiz.</p>

        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($products as $p)
                <a href="{{ route('teklif.form', ['urun' => $p->key]) }}"
                   class="rounded-full border px-4 py-1.5 text-sm font-medium transition
                          {{ $selected && $selected->key === $p->key
                             ? 'border-navy bg-navy text-white'
                             : 'border-line bg-white text-muted hover:border-navy' }}">
                    {{ $p->name }}
                </a>
            @endforeach
        </div>

        @if ($errors->any())
            <div class="mt-6 rounded-lg border border-zred/30 bg-zred-tint px-4 py-3 text-sm text-zred-dark">
                Lütfen işaretli alanları kontrol edin.
            </div>
        @endif

        <form method="POST" action="{{ route('teklif.store') }}" class="mt-8 space-y-8">
            @csrf
            <input type="hidden" name="urun" value="{{ $selected?->key }}">
            <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-navy">{{ $selected?->name }} bilgileri</h2>
                @foreach ($selected?->field_schema ?? [] as $field)
                    @php $name = 'fields.' . $field['name']; @endphp
                    <div>
                        <label class="block text-sm font-medium text-ink">
                            {{ $field['label'] ?? $field['name'] }}
                            @if ($field['required'] ?? false)<span class="text-zred">*</span>@endif
                        </label>

                        @if (($field['type'] ?? 'text') === 'select')
                            <select name="{{ $name }}"
                                    class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                                <option value="">Seçiniz</option>
                                @foreach ($field['options'] ?? [] as $opt)
                                    <option value="{{ $opt }}" @selected(old($name) === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ ['date' => 'date', 'number' => 'number', 'tel' => 'tel'][$field['type'] ?? 'text'] ?? 'text' }}"
                                   name="{{ $name }}" value="{{ old($name) }}"
                                   class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                        @endif

                        @error($name) <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>

            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-navy">İletişim bilgileriniz</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-ink">Ad <span class="text-zred">*</span></label>
                        <input type="text" name="ad" value="{{ old('ad') }}"
                               class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                        @error('ad') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink">Soyad <span class="text-zred">*</span></label>
                        <input type="text" name="soyad" value="{{ old('soyad') }}"
                               class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                        @error('soyad') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink">TC Kimlik No <span class="text-zred">*</span></label>
                        <input type="text" name="tc_no" value="{{ old('tc_no') }}" maxlength="11" inputmode="numeric"
                               class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                        @error('tc_no') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink">Telefon <span class="text-zred">*</span></label>
                        <input type="tel" name="telefon" value="{{ old('telefon') }}"
                               class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                        @error('telefon') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-ink">E-posta</label>
                        <input type="email" name="eposta" value="{{ old('eposta') }}"
                               class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                        @error('eposta') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="flex items-start gap-2 text-sm text-muted">
                    <input type="checkbox" name="kvkk" value="1" class="mt-0.5" @checked(old('kvkk'))>
                    <span>
                        <a href="{{ route('kvkk') }}" target="_blank" class="font-medium text-navy underline">KVKK Aydınlatma Metni</a>'ni
                        okudum, kişisel verilerimin teklif ve poliçe süreçleri için işlenmesini kabul ediyorum. <span class="text-zred">*</span>
                    </span>
                </label>
                @error('kvkk') <p class="mt-1 text-xs text-zred">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="rounded-lg bg-zred px-6 py-3 text-sm font-semibold text-white transition hover:bg-zred-dark">
                Teklif Talebi Gönder
            </button>
        </form>
    </section>
@endsection
