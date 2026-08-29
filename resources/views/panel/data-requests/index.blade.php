@extends('layouts.panel')

@section('title', 'KVKK Veri Talepleri')
@section('heading', 'KVKK Veri Talepleri')

@section('content')
    <div class="overflow-x-auto rounded-xl border border-line bg-white">
        <table class="min-w-full text-sm">
            <thead class="border-b border-line bg-navy-tint text-left text-xs uppercase tracking-wider text-muted">
                <tr>
                    <th class="px-4 py-3">Müşteri</th>
                    <th class="px-4 py-3">Tür</th>
                    <th class="px-4 py-3">Durum</th>
                    <th class="px-4 py-3">Tarih</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($requests as $req)
                    <tr>
                        <td class="px-4 py-3">{{ $req->customer->first_name }} {{ $req->customer->last_name }}</td>
                        <td class="px-4 py-3">{{ $req->type === 'sil' ? 'Silme' : 'İndirme' }}</td>
                        <td class="px-4 py-3">{{ $req->status }}</td>
                        <td class="px-4 py-3 text-muted">{{ $req->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if ($req->status !== 'tamamlandi')
                                <form method="POST" action="{{ route('panel.data-requests.handle', $req) }}">
                                    @csrf
                                    <button class="rounded-lg bg-navy px-3 py-1.5 text-xs font-medium text-white">Tamamlandı</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-muted">Talep yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
@endsection
