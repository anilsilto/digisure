@extends('layouts.panel')

@section('title', 'İletişim Mesajları')
@section('heading', 'İletişim Mesajları')

@section('content')
    @if ($okunmamis > 0)
        <p class="mb-4 text-sm text-muted">{{ $okunmamis }} okunmamış mesaj.</p>
    @endif

    <div class="space-y-3">
        @forelse ($mesajlar as $mesaj)
            <div class="rounded-xl border border-line bg-white p-5 {{ $mesaj->read_at ? '' : 'ring-1 ring-accent/40' }}">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-ink">
                            {{ $mesaj->name }}
                            @unless ($mesaj->read_at)
                                <span class="ml-2 rounded-full bg-accent/10 px-2 py-0.5 text-xs font-medium text-accent-dark">Yeni</span>
                            @endunless
                        </p>
                        <p class="mt-0.5 text-sm text-muted">
                            <a href="tel:{{ $mesaj->phone }}" class="hover:text-navy">{{ $mesaj->phone }}</a>
                            @if ($mesaj->email)
                                · <a href="mailto:{{ $mesaj->email }}" class="hover:text-navy">{{ $mesaj->email }}</a>
                            @endif
                        </p>
                    </div>
                    <span class="text-xs text-muted">{{ $mesaj->created_at->format('d.m.Y H:i') }}</span>
                </div>

                <p class="mt-3 whitespace-pre-line text-sm text-ink">{{ $mesaj->message }}</p>

                <div class="mt-4 flex gap-2">
                    @unless ($mesaj->read_at)
                        <form method="POST" action="{{ route('panel.contact-messages.read', $mesaj->id) }}">
                            @csrf
                            <button class="rounded-lg bg-navy px-3 py-1.5 text-xs font-medium text-white">Okundu işaretle</button>
                        </form>
                    @endunless
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $mesaj->phone) }}" target="_blank" rel="noopener"
                       class="rounded-lg border border-line px-3 py-1.5 text-xs font-medium text-navy hover:bg-navy-tint">WhatsApp</a>
                    <form method="POST" action="{{ route('panel.contact-messages.destroy', $mesaj->id) }}" onsubmit="return confirm('Mesaj silinsin mi?')">
                        @csrf @method('DELETE')
                        <button class="rounded-lg border border-danger/40 px-3 py-1.5 text-xs font-medium text-danger hover:bg-danger-tint">Sil</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="rounded-xl border border-line bg-white p-10 text-center text-muted">Henüz iletişim mesajı yok.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $mesajlar->links() }}</div>
@endsection
