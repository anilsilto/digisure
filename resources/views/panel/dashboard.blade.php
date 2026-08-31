@extends('layouts.panel')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <a href="/panel/teklifler?durum=yeni" class="rounded-xl border border-line bg-white p-5 shadow-sm transition hover:shadow-md">
            <p class="text-sm text-muted">Yeni Talepler</p>
            <p class="mt-2 text-3xl font-bold text-navy">{{ $yeniTalep }}</p>
        </a>
        <a href="/panel/teklifler?durum=teklif_bekleyen" class="rounded-xl border border-line bg-white p-5 shadow-sm transition hover:shadow-md">
            <p class="text-sm text-muted">Teklif Bekleyen</p>
            <p class="mt-2 text-3xl font-bold text-accent">{{ $teklifBekleyen }}</p>
        </a>
        <a href="{{ route('panel.policies.index') }}" class="rounded-xl border border-line bg-white p-5 shadow-sm transition hover:shadow-md">
            <p class="text-sm text-muted">Bu Hafta Biten Poliçe</p>
            <p class="mt-2 text-3xl font-bold text-gold">{{ $buHaftaBiten }}</p>
        </a>
    </div>

    <h2 class="mb-3 mt-8 text-sm font-semibold text-ink">Risk Paneli &amp; Çapraz Satış <span class="font-normal text-muted">(son 30 gün)</span></h2>
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-line bg-white p-5">
            <p class="text-sm text-muted">Farkındalık Tıklama Oranı</p>
            <p class="mt-2 text-3xl font-bold text-navy">%{{ $farkindalik['rate'] }}</p>
            <p class="mt-1 text-xs text-muted">{{ $farkindalik['clicked'] }} / {{ $farkindalik['opened'] }} müşteri talep bıraktı</p>
        </div>
        <div class="rounded-xl border border-line bg-white p-5">
            <p class="text-sm text-muted">Çapraz Satış Dönüşümü</p>
            <p class="mt-2 text-3xl font-bold text-accent">%{{ $caprazSatis['rate'] }}</p>
            <p class="mt-1 text-xs text-muted">{{ $caprazSatis['converted'] }} / {{ $caprazSatis['leads'] }} oto-dışı talep 7 günde poliçeye döndü</p>
        </div>
        <div class="rounded-xl border border-line bg-white p-5">
            <p class="text-sm text-muted">Portföyde Trafik Payı</p>
            <p class="mt-2 text-3xl font-bold text-gold">%{{ $portfoy['trafikPct'] }}</p>
            <p class="mt-1 text-xs text-muted">{{ $portfoy['total'] }} aktif poliçe</p>
        </div>
    </div>

    @if ($portfoy['mix'])
        <div class="mt-4 overflow-x-auto rounded-xl border border-line bg-white p-5">
            <p class="mb-3 text-sm font-semibold text-ink">Branş Dağılımı</p>
            <div class="space-y-2">
                @foreach ($portfoy['mix'] as $row)
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-40 shrink-0 text-muted">{{ $row['name'] }}</span>
                        <span class="h-2 flex-1 overflow-hidden rounded-full bg-navy-tint">
                            <span class="block h-full bg-navy" style="width: {{ max($row['pct'], 2) }}%"></span>
                        </span>
                        <span class="w-16 shrink-0 text-right tabular-nums text-ink">{{ $row['adet'] }} · %{{ $row['pct'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
