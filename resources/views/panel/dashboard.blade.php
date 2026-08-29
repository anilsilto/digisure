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
            <p class="mt-2 text-3xl font-bold text-zred">{{ $teklifBekleyen }}</p>
        </a>
        <a href="{{ route('panel.policies.index') }}" class="rounded-xl border border-line bg-white p-5 shadow-sm transition hover:shadow-md">
            <p class="text-sm text-muted">Bu Hafta Biten Poliçe</p>
            <p class="mt-2 text-3xl font-bold text-gold">{{ $buHaftaBiten }}</p>
        </a>
    </div>
@endsection
