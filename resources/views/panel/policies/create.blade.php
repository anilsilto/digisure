@extends('layouts.panel')

@section('title', 'Elle Poliçe Ekle')
@section('heading', 'Elle Poliçe Ekle')

@section('content')
    <form method="POST" action="{{ route('panel.policies.store') }}" enctype="multipart/form-data"
          class="max-w-2xl space-y-5 rounded-xl border border-line bg-white p-6">
        @csrf

        @if ($errors->any())
            <div class="rounded-lg border border-danger/30 bg-danger-tint px-3 py-2 text-sm text-danger">{{ $errors->first() }}</div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-sm">Ürün
                <select name="urun" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
                    @foreach ($products as $p)
                        <option value="{{ $p->key }}" @selected(old('urun') === $p->key)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm">Şirket
                <select name="insurer" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
                    @foreach ($insurerLabels as $key => $label)
                        <option value="{{ $key }}" @selected(old('insurer') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm">Poliçe No <input type="text" name="policy_no" value="{{ old('policy_no') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
            <label class="text-sm">Prim (TL) <input type="number" step="0.01" name="premium" value="{{ old('premium') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
            <label class="text-sm">Başlangıç <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
            <label class="text-sm">Bitiş <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
        </div>

        <fieldset class="border-t border-line pt-4">
            <legend class="text-sm font-semibold text-navy">Müşteri</legend>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                <label class="text-sm">Ad <input type="text" name="ad" value="{{ old('ad') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                <label class="text-sm">Soyad <input type="text" name="soyad" value="{{ old('soyad') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                <label class="text-sm">TC Kimlik <input type="text" name="tc_no" value="{{ old('tc_no') }}" maxlength="11" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                <label class="text-sm">Telefon <input type="tel" name="telefon" value="{{ old('telefon') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                <label class="text-sm sm:col-span-2">E-posta <input type="email" name="eposta" value="{{ old('eposta') }}" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
            </div>
        </fieldset>

        <label class="block text-sm">Poliçe PDF <input type="file" name="file" accept="application/pdf" class="mt-1 block text-xs"></label>

        <button type="submit" class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">Poliçeyi Kaydet</button>
    </form>
@endsection
