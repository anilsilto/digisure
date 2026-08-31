@extends('customer.layouts.portal')

@section('title', 'Risklerim')

@php
    $bandRenk = [
        'yesil' => ['bg' => 'bg-ok/10', 'text' => 'text-ok', 'ring' => 'ring-ok/30'],
        'sari' => ['bg' => 'bg-gold/10', 'text' => 'text-gold', 'ring' => 'ring-gold/30'],
        'kirmizi' => ['bg' => 'bg-danger-tint', 'text' => 'text-danger', 'ring' => 'ring-danger/30'],
    ][$report->band];
@endphp

@section('content')
    <h1 class="text-2xl font-extrabold text-ink">Risk ve Güvence Durumum</h1>
    <p class="mt-1 text-sm text-muted">
        Sahip olduğunuz varlıklara karşılık eksik güvenceleriniz aşağıda. Bir eksiği kapatmak için
        tek dokunuşla acentemize teklif talebi bırakabilirsiniz.
    </p>

    {{-- Risk skoru kartı --}}
    <div class="mt-6 rounded-xl border border-line bg-white p-6 ring-1 {{ $bandRenk['ring'] }}">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-muted">Risk Skorunuz</p>
                <p class="text-4xl font-extrabold {{ $bandRenk['text'] }}">%{{ $report->riskPct }}</p>
                <p class="mt-1 text-sm font-medium {{ $bandRenk['text'] }}">{{ $report->bandLabel() }}</p>
            </div>
            <div class="max-w-xs text-sm text-muted">
                @if ($report->riskPct === 0)
                    Tebrikler, değerlendirdiğimiz alanların tamamı güvence altında.
                @else
                    Varlıklarınızın <span class="font-semibold text-ink">%{{ $report->riskPct }}</span>’lik kısmı
                    eksik güvencede. Aşağıdaki adımlarla bu oranı düşürebilirsiniz.
                @endif
            </div>
        </div>

        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-navy-tint">
            <div class="h-full {{ $report->band === 'yesil' ? 'bg-ok' : ($report->band === 'sari' ? 'bg-gold' : 'bg-danger') }}"
                 style="width: {{ max($report->riskPct, 3) }}%"></div>
        </div>
    </div>

    @unless ($report->hasDeclaredAssets)
        <div class="mt-4 rounded-lg bg-navy-tint px-4 py-3 text-sm text-ink">
            Aracınızı, konutunuzu veya işyerinizi eklerseniz risk tablonuz netleşir.
        </div>
    @endunless

    {{-- Varlık kartları --}}
    <div class="mt-6 space-y-4">
        @foreach ($report->assets as $asset)
            <div class="rounded-xl border border-line bg-white p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="rounded-full bg-navy-tint px-2 py-0.5 text-xs font-semibold text-navy">{{ $asset['typeLabel'] }}</span>
                        <span class="ml-2 font-semibold text-ink">{{ $asset['label'] }}</span>
                    </div>
                    @if ($asset['missingCount'] === 0)
                        <span class="text-xs font-medium text-ok">Tam güvencede</span>
                    @else
                        <span class="text-xs font-medium text-danger">{{ $asset['missingCount'] }} eksik</span>
                    @endif
                </div>

                <ul class="mt-3 space-y-2">
                    @foreach ($asset['items'] as $item)
                        <li class="flex flex-wrap items-start justify-between gap-2 rounded-lg border border-line px-3 py-2 text-sm">
                            <span class="flex items-start gap-2">
                                <span class="{{ $item['has'] ? 'text-ok' : 'text-danger' }}">{{ $item['has'] ? '✓' : '✕' }}</span>
                                <span>
                                    <span class="font-medium text-ink">{{ $item['label'] }}</span>
                                    @unless ($item['has'])
                                        <span class="block text-xs text-muted">{{ $item['has'] ? '' : 'Bu güvence eksik.' }}
                                            @if ($item['note']) {{ $item['note'] }} @endif
                                        </span>
                                    @endunless
                                </span>
                            </span>
                            @unless ($item['has'])
                                <form method="POST" action="{{ route('customer.risk.lead') }}">
                                    @csrf
                                    <input type="hidden" name="product_key" value="{{ $item['product_key'] }}">
                                    <input type="hidden" name="asset_id" value="{{ $asset['id'] }}">
                                    <button class="rounded-lg bg-navy px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-dark">
                                        Riski Kapat / Teklif Al
                                    </button>
                                </form>
                            @endunless
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    {{-- Varlık ekle / kaldır --}}
    <div class="mt-6 rounded-xl border border-line bg-white p-5">
        <h2 class="text-sm font-semibold text-ink">Varlık Ekle</h2>
        <form method="POST" action="{{ route('customer.risk.asset.store') }}" class="mt-3 flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="block text-xs text-muted">Tür</label>
                <select name="type" class="mt-1 rounded-lg border border-line px-3 py-2 text-sm">
                    @foreach ($assetTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs text-muted">Tanım (ör. plaka, semt)</label>
                <input name="label" required maxlength="80"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-navy focus:outline-none">
                @error('label') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <button class="rounded-lg bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy-dark">Ekle</button>
        </form>

        @if ($declaredAssets->isNotEmpty())
            <ul class="mt-4 divide-y divide-line text-sm">
                @foreach ($declaredAssets as $asset)
                    <li class="flex items-center justify-between py-2">
                        <span>{{ $asset->typeLabel() }} · {{ $asset->label }}
                            <span class="text-xs text-muted">({{ $asset->source === 'turetilmis' ? 'poliçelerden' : 'sizin eklediğiniz' }})</span>
                        </span>
                        <form method="POST" action="{{ route('customer.risk.asset.remove', $asset->id) }}"
                              onsubmit="return confirm('Bu varlık listeden kaldırılsın mı?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-medium text-danger hover:underline">Kaldır</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
