@extends('layouts.panel')

@section('title', 'Müşteri — Varlıklar & Risk')
@section('heading', $customer->first_name . ' ' . $customer->last_name . ' — Varlıklar & Risk')

@php
    $bandText = ['yesil' => 'text-ok', 'sari' => 'text-gold', 'kirmizi' => 'text-danger'][$report->band];
@endphp

@section('content')
    <div class="mb-4 flex flex-wrap items-center gap-3 text-sm">
        <a href="{{ route('panel.customers.index') }}" class="text-muted hover:text-navy">← Müşteriler</a>
        <a href="{{ route('panel.campaign.show', $customer->id) }}" class="text-navy hover:text-accent">Kampanya analizi →</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Risk raporu --}}
        <div class="rounded-xl border border-line bg-white p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="text-sm font-semibold text-ink">Risk Raporu</h2>
                <span class="text-2xl font-extrabold {{ $bandText }}">%{{ $report->riskPct }}
                    <span class="text-xs font-medium">({{ $report->bandLabel() }})</span></span>
            </div>

            <div class="mt-4 space-y-4">
                @foreach ($report->assets as $asset)
                    <div class="rounded-lg border border-line p-3">
                        <p class="text-sm font-semibold text-ink">{{ $asset['typeLabel'] }} · {{ $asset['label'] }}</p>
                        <ul class="mt-2 space-y-1 text-sm">
                            @foreach ($asset['items'] as $item)
                                <li class="flex items-center justify-between gap-2">
                                    <span class="{{ $item['has'] ? 'text-ok' : 'text-danger' }}">
                                        {{ $item['has'] ? '✓' : '✕' }} {{ $item['label'] }}
                                    </span>
                                    @unless ($item['has'])
                                        <form method="POST" action="{{ route('panel.customers.risk-lead', $customer->id) }}">
                                            @csrf
                                            <input type="hidden" name="product_key" value="{{ $item['product_key'] }}">
                                            <button class="rounded border border-line px-2 py-1 text-xs font-medium text-navy hover:bg-navy-tint">
                                                Teklif talebi aç
                                            </button>
                                        </form>
                                    @endunless
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Varlık yönetimi --}}
        <div class="rounded-xl border border-line bg-white p-6">
            <h2 class="text-sm font-semibold text-ink">Varlıklar</h2>
            <p class="mt-1 text-xs text-muted">Poliçe ve tekliflerden otomatik türetilir; elle de ekleyebilirsiniz.</p>

            <form method="POST" action="{{ route('panel.customers.asset.store', $customer->id) }}" class="mt-3 flex flex-wrap items-end gap-2">
                @csrf
                <select name="type" class="rounded-lg border border-line px-3 py-2 text-sm">
                    @foreach ($assetTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <input name="label" required maxlength="80" placeholder="Tanım (plaka, semt...)"
                       class="flex-1 rounded-lg border border-line px-3 py-2 text-sm">
                <button class="rounded-lg bg-navy px-4 py-2 text-sm font-semibold text-white">Ekle</button>
                @error('label') <p class="w-full text-xs text-danger">{{ $message }}</p> @enderror
            </form>

            @if ($assets->isNotEmpty())
                <ul class="mt-4 divide-y divide-line text-sm">
                    @foreach ($assets as $asset)
                        <li class="flex items-center justify-between py-2">
                            <span>{{ $asset->typeLabel() }} · {{ $asset->label }}
                                <span class="text-xs text-muted">({{ ['turetilmis' => 'poliçeden', 'musteri' => 'müşteri', 'acente' => 'acente'][$asset->source] ?? $asset->source }})</span>
                            </span>
                            <form method="POST" action="{{ route('panel.customers.asset.remove', [$customer->id, $asset->id]) }}"
                                  onsubmit="return confirm('Kaldırılsın mı?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium text-danger hover:underline">Kaldır</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4 text-sm text-muted">Kayıtlı varlık yok.</p>
            @endif
        </div>
    </div>
@endsection
