@extends('layouts.panel')

@section('title', 'Kampanya')
@section('heading', config('digisure.campaign.name') . ' Kampanyası')

@section('content')
    <p class="mb-4 text-sm text-muted">
        Müşteri toplam {{ config('digisure.campaign.threshold') }} puana ulaşınca ödül menüsünden 1 hak seçer.
        Branş seçimini müşteri kaydına girerek yapın.
    </p>

    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full divide-y divide-line text-sm">
            <thead class="bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Müşteri</th>
                    <th class="px-4 py-3">Puan</th>
                    <th class="px-4 py-3">Durum</th>
                    <th class="px-4 py-3">Seçili Ödül</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($customers as $customer)
                    @php $report = $analyzer->analyze($customer->campaignProfile->branches ?? []); @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $report->totalPoints }}</td>
                        <td class="px-4 py-3">
                            @if ($report->qualified)
                                <span class="rounded-full bg-ok/10 px-2 py-0.5 text-xs font-medium text-ok">HAK KAZANDI</span>
                            @else
                                <span class="rounded-full bg-muted/10 px-2 py-0.5 text-xs font-medium text-muted">{{ $report->shortfall }} puan eksik</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $customer->campaignProfile?->rewardLabel() ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('panel.campaign.show', $customer->id) }}" class="font-medium text-navy hover:text-accent">Analiz →</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-muted">Henüz müşteri yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
