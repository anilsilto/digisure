@extends('layouts.panel')

@section('title', 'Poliçeler')
@section('heading', 'Poliçeler')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <form method="GET" class="flex flex-wrap items-end gap-3 text-sm">
            <div>
                <label class="block text-xs font-medium text-muted">Durum</label>
                <select name="durum" class="mt-1 rounded-lg border border-line px-2 py-1.5">
                    <option value="">Tümü</option>
                    @foreach (['aktif' => 'Aktif', 'yenilendi' => 'Yenilendi', 'iptal' => 'İptal', 'suresi_doldu' => 'Süresi Doldu'] as $v => $l)
                        <option value="{{ $v }}" @selected(($filters['durum'] ?? '') === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <button class="rounded-lg bg-navy px-4 py-2 font-medium text-white">Filtrele</button>
        </form>
        <a href="{{ route('panel.policies.create') }}" class="rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent-dark">
            + Elle Poliçe Ekle
        </a>
    </div>

    @if ($upcoming->isNotEmpty())
        <div class="mb-4 rounded-xl border border-gold/40 bg-gold/10 p-4 text-sm">
            <p class="font-semibold text-ink">Yaklaşan Yenilemeler (30 gün)</p>
            <ul class="mt-2 space-y-1 text-muted">
                @foreach ($upcoming as $p)
                    <li>
                        <a href="{{ route('panel.policies.show', $p) }}" class="font-mono text-navy hover:underline">{{ $p->policy_no }}</a>
                        — {{ $p->customer->first_name }} {{ $p->customer->last_name }} — bitiş {{ $p->end_date->format('d.m.Y') }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full text-sm">
            <thead class="border-b border-line bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Poliçe No</th>
                    <th class="px-4 py-3">Müşteri</th>
                    <th class="px-4 py-3">Ürün</th>
                    <th class="px-4 py-3">Şirket</th>
                    <th class="px-4 py-3">Başlangıç</th>
                    <th class="px-4 py-3">Bitiş</th>
                    <th class="px-4 py-3">Durum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($policies as $p)
                    <tr class="hover:bg-navy-tint/50">
                        <td class="px-4 py-3"><a href="{{ route('panel.policies.show', $p) }}" class="font-mono font-semibold text-navy hover:underline">{{ $p->policy_no }}</a></td>
                        <td class="px-4 py-3">{{ $p->customer->first_name }} {{ $p->customer->last_name }}</td>
                        <td class="px-4 py-3">{{ $p->productType->name }}</td>
                        <td class="px-4 py-3">{{ $insurerLabels[$p->insurer] ?? $p->insurer }}</td>
                        <td class="px-4 py-3 text-muted">{{ $p->start_date->format('d.m.Y') }}</td>
                        <td class="px-4 py-3 text-muted">{{ $p->end_date->format('d.m.Y') }}</td>
                        <td class="px-4 py-3">{{ $p->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-muted">Poliçe yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $policies->links() }}</div>
@endsection
