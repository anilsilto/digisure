@extends('customer.layouts.portal')

@section('title', 'Kampanya')

@section('content')
    <h1 class="text-2xl font-extrabold text-ink">{{ $campaignName }}</h1>
    <p class="mt-1 text-sm text-muted">
        Zafir Sigorta ile aldığınız poliçeler puana dönüşür; {{ $report->threshold }} puana ulaşınca
        aşağıdaki avantajlardan birini seçersiniz.
    </p>

    <div class="mt-6 rounded-xl border border-line bg-white p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-muted">Toplam Puanınız</p>
                <p class="text-3xl font-extrabold text-navy">{{ $report->totalPoints }}<span class="text-lg text-muted"> / {{ $report->threshold }}</span></p>
            </div>
            @if ($report->qualified)
                <span class="rounded-full bg-ok/10 px-3 py-1 text-sm font-semibold text-ok">Kampanyaya hak kazandınız 🎉</span>
            @else
                <span class="rounded-full bg-muted/10 px-3 py-1 text-sm font-semibold text-muted">Ödül için {{ $report->shortfall }} puan daha</span>
            @endif
        </div>

        @if ($report->branches)
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($report->branches as $b)
                    <span class="rounded-lg border border-line px-2.5 py-1 text-xs text-ink">{{ $b['label'] }} · {{ $b['points'] }} P</span>
                @endforeach
            </div>
        @else
            <p class="mt-4 text-sm text-muted">Tanımlı bir kampanya branşınız bulunmuyor. Detaylar için acentenizle görüşün.</p>
        @endif

        @unless ($report->qualified)
            @if ($report->suggestedPackages)
                <div class="mt-4 rounded-lg bg-navy-tint p-4 text-sm">
                    <p class="font-semibold text-ink">Barajı geçmenin en hızlı yolu</p>
                    <ul class="mt-1 list-disc pl-5 text-muted">
                        @foreach ($report->suggestedPackages as $pkg)
                            <li>{{ implode(' + ', $pkg['branches']) }} → {{ $pkg['newTotal'] }} puan</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endunless
    </div>

    <div class="mt-6 rounded-xl border border-line bg-white p-6">
        <h2 class="text-sm font-semibold text-ink">Avantaj Menüsü</h2>
        @if ($report->qualified)
            <p class="mt-1 text-xs text-muted">Bir avantaj seçin. Seçiminizi daha sonra değiştirebilirsiniz.</p>
            <form method="POST" action="{{ route('customer.campaign.reward') }}" class="mt-4 space-y-2">
                @csrf
                @foreach ($report->rewardOptions as $reward)
                    <label class="flex items-start gap-2 rounded-lg border border-line px-3 py-2 text-sm">
                        <input type="radio" name="reward" value="{{ $reward['key'] }}" class="mt-1"
                               @checked($profile?->selected_reward === $reward['key'])>
                        <span><span class="font-medium text-ink">{{ $reward['label'] }}</span>
                            <span class="block text-xs text-muted">{{ $reward['desc'] }}</span></span>
                    </label>
                @endforeach
                @error('reward') <p class="text-xs text-danger">{{ $message }}</p> @enderror
                <button type="submit" class="mt-2 rounded-lg bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-dark">
                    Seçimimi Kaydet
                </button>
                @if ($profile?->selected_reward)
                    <p class="text-xs text-muted">Mevcut seçiminiz: <span class="font-medium text-ink">{{ $profile->rewardLabel() }}</span></p>
                @endif
            </form>
        @else
            <p class="mt-1 text-xs text-muted">{{ $report->threshold }} puana ulaştığınızda buradan seçim yapabilirsiniz.</p>
            <ul class="mt-4 space-y-2">
                @foreach ($report->rewardOptions as $reward)
                    <li class="rounded-lg border border-line px-3 py-2 text-sm opacity-60">
                        <span class="font-medium text-ink">{{ $reward['label'] }}</span>
                        <span class="block text-xs text-muted">{{ $reward['desc'] }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
