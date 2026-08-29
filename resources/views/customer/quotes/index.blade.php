@extends('customer.layouts.portal')

@php use App\Domain\Quote\QuoteStatus; @endphp

@section('title', 'Tekliflerim')

@section('content')
    <h1 class="text-2xl font-extrabold text-ink">Tekliflerim</h1>

    <div class="mt-6 space-y-3">
        @forelse ($requests as $r)
            <a href="{{ route('customer.quotes.show', $r) }}"
               class="flex items-center justify-between rounded-xl border border-line bg-white p-4 shadow-sm transition hover:shadow-md">
                <div>
                    <p class="font-semibold text-navy">{{ $r->productType->name }}</p>
                    <p class="text-xs text-muted">Ref: <span class="font-mono">{{ $r->reference_no }}</span> · {{ $r->created_at->format('d.m.Y') }}</p>
                </div>
                <div class="text-right text-sm">
                    <span class="rounded-full bg-navy-tint-2 px-2 py-0.5 text-xs font-medium text-navy">
                        {{ QuoteStatus::tryFrom($r->status)?->label() ?? $r->status }}
                    </span>
                    <p class="mt-1 text-xs text-muted">{{ $r->verildi_count }} teklif hazır</p>
                </div>
            </a>
        @empty
            <p class="rounded-xl border border-line bg-white p-8 text-center text-muted">Henüz teklif talebiniz yok.</p>
        @endforelse
    </div>
@endsection
