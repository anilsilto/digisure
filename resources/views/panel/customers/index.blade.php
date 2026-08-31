@extends('layouts.panel')

@section('title', 'Müşteriler')
@section('heading', 'Müşteriler')

@section('content')
    <form method="GET" class="mb-4 flex items-end gap-3 text-sm">
        <div>
            <label class="block text-xs font-medium text-muted">Ara (ad / soyad)</label>
            <input name="q" value="{{ $q }}" class="mt-1 rounded-lg border border-line px-3 py-1.5">
        </div>
        <button class="rounded-lg bg-navy px-4 py-2 font-medium text-white">Ara</button>
    </form>

    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full divide-y divide-line text-sm">
            <thead class="bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Müşteri</th>
                    <th class="px-4 py-3">Aktif Poliçe</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($customers as $customer)
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ $customer->aktif_police_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('panel.customers.show', $customer->id) }}" class="font-medium text-navy hover:text-accent">Varlıklar & Risk →</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-10 text-center text-muted">Kayıt bulunamadı.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
