@extends('customer.layouts.portal')

@php use App\Domain\Quote\QuoteStatus; @endphp

@section('title', 'Teklif Karşılaştırma')

@section('content')
    <a href="{{ route('customer.quotes.index') }}" class="text-sm text-navy hover:underline">← Tekliflerim</a>

    <h1 class="mt-2 text-2xl font-extrabold text-ink">{{ $quoteRequest->productType->name }}</h1>
    <p class="text-xs text-muted">Ref: <span class="font-mono">{{ $quoteRequest->reference_no }}</span> ·
        Durum: <span class="font-semibold text-navy">{{ QuoteStatus::tryFrom($quoteRequest->status)?->label() ?? $quoteRequest->status }}</span></p>

    @if ($quotes->isEmpty())
        <p class="mt-6 rounded-xl border border-line bg-white p-8 text-center text-muted">
            Teklifler henüz hazırlanıyor. Hazır olduğunda size SMS ile haber vereceğiz.
        </p>
    @else
        <div class="mt-6 overflow-x-auto rounded-xl border border-line bg-white">
            <table class="min-w-full text-sm">
                <thead class="border-b border-line bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                    <tr>
                        <th class="px-4 py-3">Şirket</th>
                        <th class="px-4 py-3">Prim</th>
                        <th class="px-4 py-3">Teminat</th>
                        <th class="px-4 py-3">Süre</th>
                        <th class="px-4 py-3">Geçerlilik</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($quotes as $quote)
                        <tr class="{{ $quoteRequest->accepted_quote_id === $quote->id ? 'bg-ok/5' : '' }}">
                            <td class="px-4 py-3 font-semibold text-navy">{{ $insurerLabels[$quote->insurer] ?? $quote->insurer }}</td>
                            <td class="px-4 py-3 font-bold text-ink">{{ number_format((float) $quote->premium, 2, ',', '.') }} TL</td>
                            <td class="px-4 py-3 text-muted">
                                <ul class="list-inside list-disc">
                                    @foreach ($quote->coverage_summary ?? [] as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-4 py-3">{{ $quote->policy_period_months ? $quote->policy_period_months . ' ay' : '—' }}</td>
                            <td class="px-4 py-3 text-muted">{{ optional($quote->valid_until)->format('d.m.Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($quoteRequest->accepted_quote_id === $quote->id)
                                    <span class="text-xs font-semibold text-ok">Seçildi ✓</span>
                                @elseif (in_array($quoteRequest->status, ['yeni', 'teklifler_hazir'], true))
                                    <form method="POST" action="{{ route('customer.quotes.accept', [$quoteRequest, $quote]) }}">
                                        @csrf
                                        <button class="rounded-lg bg-zred px-3 py-1.5 text-xs font-semibold text-white hover:bg-zred-dark">
                                            Bu Teklifi Seç
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
