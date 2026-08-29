@extends('layouts.panel')

@section('title', 'Poliçe ' . $policy->policy_no)
@section('heading', 'Poliçe ' . $policy->policy_no)

@section('content')
    <div class="max-w-2xl space-y-6">
        <div class="rounded-xl border border-line bg-white p-6">
            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-muted">Müşteri</dt><dd class="font-medium text-ink">{{ $policy->customer->first_name }} {{ $policy->customer->last_name }}</dd></div>
                <div><dt class="text-muted">Ürün</dt><dd class="font-medium text-ink">{{ $policy->productType->name }}</dd></div>
                <div><dt class="text-muted">Şirket</dt><dd class="font-medium text-ink">{{ $insurerLabels[$policy->insurer] ?? $policy->insurer }}</dd></div>
                <div><dt class="text-muted">Prim</dt><dd class="font-medium text-ink">{{ number_format((float) $policy->premium, 2, ',', '.') }} TL</dd></div>
                <div><dt class="text-muted">Başlangıç</dt><dd class="font-medium text-ink">{{ $policy->start_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-muted">Bitiş</dt><dd class="font-medium text-ink">{{ $policy->end_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-muted">Durum</dt><dd class="font-medium text-ink">{{ $policy->status }}</dd></div>
            </dl>
        </div>

        <div class="rounded-xl border border-line bg-white p-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Yenileme Hatırlatmaları</h2>
            <ul class="mt-3 space-y-1 text-sm text-muted">
                @forelse ($policy->reminders as $reminder)
                    <li>{{ $reminder->remind_on->format('d.m.Y') }} · {{ $reminder->channel }} · {{ $reminder->status }}</li>
                @empty
                    <li>Kayıt yok.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
