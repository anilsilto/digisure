@extends('layouts.panel')

@section('title', 'Kampanya Analizi')
@section('heading', $customer->first_name . ' ' . $customer->last_name . ' — Kampanya Analizi')

@section('content')
    <a href="{{ route('panel.campaign.index') }}" class="text-sm text-muted hover:text-navy">← Kampanya listesi</a>

    <div class="mt-4 grid gap-6 lg:grid-cols-2">
        {{-- Branş seçimi --}}
        <div class="rounded-xl border border-line bg-white p-6">
            <h2 class="text-sm font-semibold text-ink">Sahip Olunan Branşlar</h2>
            <p class="mt-1 text-xs text-muted">Müşterinin poliçelerine göre işaretleyin; puan anında hesaplanır.</p>

            <form method="POST" action="{{ route('panel.campaign.update', $customer->id) }}" class="mt-4 space-y-2">
                @csrf
                @method('PUT')
                @php $secili = $profile->branches ?? []; @endphp
                @foreach ($branchConfig as $key => $meta)
                    <label class="flex items-center justify-between rounded-lg border border-line px-3 py-2 text-sm">
                        <span class="flex items-center gap-2">
                            <input type="checkbox" name="branches[]" value="{{ $key }}" @checked(in_array($key, $secili, true))>
                            {{ $meta['label'] }}
                        </span>
                        <span class="tabular-nums text-muted">{{ $meta['points'] }} puan</span>
                    </label>
                @endforeach
                <button type="submit" class="mt-2 rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">
                    Branşları Kaydet
                </button>
            </form>
        </div>

        {{-- Analiz raporu --}}
        <div class="rounded-xl border border-line bg-white p-6">
            <h2 class="text-sm font-semibold text-ink">Müşteri Analiz Raporu</h2>

            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-muted">Satın Alınan Poliçeler</dt>
                    <dd class="font-medium text-ink">
                        @forelse ($report->branches as $b)
                            {{ $b['label'] }} ({{ $b['points'] }} P){{ ! $loop->last ? ' + ' : '' }}
                        @empty
                            —
                        @endforelse
                    </dd>
                </div>
                <div><dt class="text-muted">Toplam Puan</dt><dd class="text-lg font-bold text-ink">{{ $report->totalPoints }}</dd></div>
                <div>
                    <dt class="text-muted">Kampanya Durumu</dt>
                    <dd>
                        @if ($report->qualified)
                            <span class="rounded-full bg-ok/10 px-2 py-0.5 text-xs font-semibold text-ok">HAK KAZANDI</span>
                        @else
                            <span class="rounded-full bg-danger-tint px-2 py-0.5 text-xs font-semibold text-danger">HAK KAZANAMADI</span>
                        @endif
                    </dd>
                </div>
                <div><dt class="text-muted">Kazanma Koşulu Nedir?</dt><dd class="text-ink">{{ $report->conditionText }}</dd></div>

                @unless ($report->qualified)
                    <div>
                        <dt class="text-muted">Eksik / Öneri (Çapraz Satış)</dt>
                        <dd class="text-ink">
                            Ödül için {{ $report->shortfall }} puan daha gerekiyor.
                            @if ($report->suggestedPackages)
                                <ul class="mt-1 list-disc pl-5">
                                    @foreach ($report->suggestedPackages as $pkg)
                                        <li>{{ $pkg['label'] }}: {{ implode(' + ', $pkg['branches']) }}
                                            (+{{ $pkg['addedPoints'] }} → {{ $pkg['newTotal'] }} puan)</li>
                                    @endforeach
                                </ul>
                            @endif
                        </dd>
                    </div>
                @endunless

                <div>
                    <dt class="text-muted">Müşteri Temsilcisi İçin Satış Söylemi</dt>
                    <dd class="mt-1 rounded-lg bg-navy-tint px-3 py-2 text-ink">{{ $pitch }}</dd>
                </div>
            </dl>

            @if ($report->qualified)
                <form method="POST" action="{{ route('panel.campaign.reward', $customer->id) }}" class="mt-5 space-y-2 border-t border-line pt-4">
                    @csrf
                    <p class="text-sm font-semibold text-ink">Ödül Ata</p>
                    @foreach ($report->rewardOptions as $reward)
                        <label class="flex items-start gap-2 text-sm">
                            <input type="radio" name="reward" value="{{ $reward['key'] }}" class="mt-1"
                                   @checked($profile?->selected_reward === $reward['key'])>
                            <span><span class="font-medium text-ink">{{ $reward['label'] }}</span>
                                <span class="block text-xs text-muted">{{ $reward['desc'] }}</span></span>
                        </label>
                    @endforeach
                    <button type="submit" class="mt-2 rounded-lg bg-accent px-5 py-2.5 text-sm font-semibold text-white hover:bg-accent-dark">
                        Ödülü Kaydet
                    </button>
                    @if ($profile?->reward_selected_at)
                        <p class="text-xs text-muted">
                            Son seçim: {{ $profile->reward_selected_at->format('d.m.Y H:i') }}
                            ({{ $profile->reward_selected_by === 'customer' ? 'müşteri' : 'panel' }})
                        </p>
                    @endif
                </form>
            @endif
        </div>
    </div>
@endsection
