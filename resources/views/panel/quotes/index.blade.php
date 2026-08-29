@extends('layouts.panel')

@section('title', 'Teklifler')
@section('heading', 'Teklif Talepleri')

@section('content')
    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3 rounded-xl border border-line bg-white p-4 text-sm">
        <div>
            <label class="block text-xs font-medium text-muted">Durum</label>
            <select name="durum" class="mt-1 rounded-lg border border-line px-2 py-1.5">
                <option value="">Tümü</option>
                <option value="teklif_bekleyen" @selected(($filters['durum'] ?? '') === 'teklif_bekleyen')>Teklif Bekleyen</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['durum'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-muted">Ürün</label>
            <select name="urun" class="mt-1 rounded-lg border border-line px-2 py-1.5">
                <option value="">Tümü</option>
                @foreach ($products as $p)
                    <option value="{{ $p->key }}" @selected(($filters['urun'] ?? '') === $p->key)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-muted">Personel</label>
            <select name="personel" class="mt-1 rounded-lg border border-line px-2 py-1.5">
                <option value="">Tümü</option>
                @foreach ($staff as $s)
                    <option value="{{ $s->id }}" @selected((string) ($filters['personel'] ?? '') === (string) $s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-muted">Ara (ref / ad)</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="mt-1 rounded-lg border border-line px-2 py-1.5">
        </div>
        <button class="rounded-lg bg-navy px-4 py-2 font-medium text-white">Filtrele</button>
    </form>

    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full text-sm">
            <thead class="border-b border-line bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Ref</th>
                    <th class="px-4 py-3">Müşteri</th>
                    <th class="px-4 py-3">Ürün</th>
                    <th class="px-4 py-3">Durum</th>
                    <th class="px-4 py-3">Bekleyen</th>
                    <th class="px-4 py-3">Personel</th>
                    <th class="px-4 py-3">Tarih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($requests as $r)
                    <tr class="hover:bg-navy-tint/50">
                        <td class="px-4 py-3">
                            <a href="{{ route('panel.quotes.show', $r) }}" class="font-mono font-semibold text-navy hover:underline">{{ $r->reference_no }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $r->customer->first_name }} {{ $r->customer->last_name }}</td>
                        <td class="px-4 py-3">{{ $r->productType->name }}</td>
                        <td class="px-4 py-3">{{ \App\Domain\Quote\QuoteStatus::tryFrom($r->status)?->label() ?? $r->status }}</td>
                        <td class="px-4 py-3">{{ $r->bekleyen_count }}</td>
                        <td class="px-4 py-3">{{ $r->assignedUser?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-muted">{{ $r->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-muted">Kayıt bulunamadı.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
@endsection
