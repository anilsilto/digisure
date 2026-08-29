@extends('customer.layouts.portal')

@php use App\Support\Pii; @endphp

@section('title', 'Profilim')

@section('content')
    <h1 class="text-2xl font-extrabold text-ink">Profilim</h1>

    <div class="mt-6 rounded-xl border border-line bg-white p-6">
        <dl class="mb-6 grid gap-3 text-sm sm:grid-cols-2">
            <div><dt class="text-muted">Ad Soyad</dt><dd class="font-medium text-ink">{{ $customer->first_name }} {{ $customer->last_name }}</dd></div>
            <div><dt class="text-muted">TC Kimlik</dt><dd class="font-medium text-ink">{{ Pii::mask($customer->tc_no) }}</dd></div>
            <div><dt class="text-muted">Telefon</dt><dd class="font-medium text-ink">{{ Pii::mask($customer->phone) }}</dd></div>
        </dl>

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-ink">E-posta</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Adres</label>
                <textarea name="address" rows="3"
                          class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">{{ old('address', $customer->address) }}</textarea>
            </div>
            <label class="flex items-center gap-2 text-sm text-muted">
                <input type="checkbox" name="marketing_consent" value="1" @checked($customer->marketing_consent_at)>
                Kampanya ve bilgilendirme mesajları almak istiyorum.
            </label>
            <button type="submit" class="rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">
                Kaydet
            </button>
        </form>
    </div>

    <div class="mt-6 rounded-xl border border-line bg-white p-6">
        <h2 class="text-sm font-semibold text-ink">KVKK Hakları</h2>
        <p class="mt-1 text-xs text-muted">Kişisel verilerinizin bir kopyasını isteyebilir veya silinmesini talep edebilirsiniz.</p>
        <div class="mt-3 flex gap-3">
            <form method="POST" action="{{ route('customer.data-request.store') }}">
                @csrf
                <input type="hidden" name="type" value="indir">
                <button class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-navy hover:bg-navy-tint">Verilerimi İndir</button>
            </form>
            <form method="POST" action="{{ route('customer.data-request.store') }}"
                  onsubmit="return confirm('Verilerinizin silinmesi talebini onaylıyor musunuz?');">
                @csrf
                <input type="hidden" name="type" value="sil">
                <button class="rounded-lg border border-danger/40 px-4 py-2 text-sm font-medium text-danger hover:bg-danger-tint">Silme Talebi</button>
            </form>
        </div>
    </div>
@endsection
