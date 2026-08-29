@extends('customer.layouts.portal')

@section('title', 'Poliçelerim')

@section('content')
    <h1 class="text-2xl font-extrabold text-ink">Poliçelerim</h1>

    <div class="mt-6 space-y-3">
        @forelse ($policies as $policy)
            <div class="rounded-xl border border-line bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-navy">{{ $policy->productType->name }} — {{ $insurerLabels[$policy->insurer] ?? $policy->insurer }}</p>
                        <p class="text-xs text-muted">Poliçe No: <span class="font-mono">{{ $policy->policy_no }}</span></p>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-muted">Bitiş: {{ $policy->end_date->format('d.m.Y') }}</p>
                        <span class="text-xs font-medium text-navy">{{ $policy->status }}</span>
                    </div>
                </div>
                <div class="mt-2 flex gap-3 text-sm">
                    @if ($policy->file_path)
                        <a href="{{ route('customer.policies.download', $policy) }}" class="font-medium text-navy hover:underline">PDF indir</a>
                    @endif
                    <a href="{{ url('/teklif?urun=' . $policy->productType->key) }}" class="font-medium text-accent hover:underline">Yenile</a>
                </div>
            </div>
        @empty
            <p class="rounded-xl border border-line bg-white p-8 text-center text-muted">Aktif poliçeniz yok.</p>
        @endforelse
    </div>
@endsection
