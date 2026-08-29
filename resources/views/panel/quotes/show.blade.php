@extends('layouts.panel')

@php use App\Support\Pii; use App\Domain\Quote\QuoteStatus; @endphp

@section('title', 'Teklif ' . $quoteRequest->reference_no)
@section('heading', 'Teklif ' . $quoteRequest->reference_no)

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Sol: müşteri + form cevapları --}}
        <div class="space-y-6">
            <div class="rounded-xl border border-line bg-white p-5">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Müşteri</h2>
                <p class="mt-2 text-lg font-semibold text-ink">{{ $quoteRequest->customer->first_name }} {{ $quoteRequest->customer->last_name }}</p>
                <dl class="mt-2 space-y-1 text-sm text-muted">
                    <div><dt class="inline font-medium text-ink">TC:</dt>
                        <dd class="inline">{{ $canReveal ? $quoteRequest->customer->tc_no : Pii::mask($quoteRequest->customer->tc_no) }}</dd></div>
                    <div><dt class="inline font-medium text-ink">Telefon:</dt>
                        <dd class="inline">{{ $canReveal ? $quoteRequest->customer->phone : Pii::mask($quoteRequest->customer->phone) }}</dd></div>
                    @if ($quoteRequest->customer->email)
                        <div><dt class="inline font-medium text-ink">E-posta:</dt> <dd class="inline">{{ $quoteRequest->customer->email }}</dd></div>
                    @endif
                </dl>
                <p class="mt-3 text-xs text-muted">
                    Durum: <span class="font-semibold text-navy">{{ QuoteStatus::tryFrom($quoteRequest->status)?->label() ?? $quoteRequest->status }}</span>
                    · Ürün: {{ $quoteRequest->productType->name }}
                    · Kaynak: {{ $quoteRequest->source }}
                </p>
            </div>

            <div class="rounded-xl border border-line bg-white p-5">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Form Bilgileri</h2>
                <dl class="mt-3 space-y-2 text-sm">
                    @forelse ($quoteRequest->fields as $field)
                        <div class="flex justify-between gap-4">
                            <dt class="text-muted">{{ $labels[$field->field_key] ?? $field->field_key }}</dt>
                            <dd class="font-medium text-ink">{{ $field->value }}</dd>
                        </div>
                    @empty
                        <p class="text-muted">Form alanı yok.</p>
                    @endforelse
                </dl>
            </div>

            <form method="POST" action="{{ route('panel.quotes.assign', $quoteRequest) }}" class="rounded-xl border border-line bg-white p-5 text-sm">
                @csrf
                <label class="block font-medium text-ink">Personel Ata</label>
                <div class="mt-2 flex gap-2">
                    <select name="assigned_user_id" class="flex-1 rounded-lg border border-line px-2 py-1.5">
                        <option value="">— Atanmadı —</option>
                        @foreach ($staff as $s)
                            <option value="{{ $s->id }}" @selected($quoteRequest->assigned_user_id === $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-navy px-3 py-1.5 font-medium text-white">Kaydet</button>
                </div>
            </form>
        </div>

        {{-- Sağ: şirket teklifleri --}}
        <div class="lg:col-span-2 space-y-4">
            @if ($quoteRequest->status === 'kabul')
                <form method="POST" action="{{ route('panel.quotes.policelestir', $quoteRequest) }}" enctype="multipart/form-data"
                      class="rounded-xl border border-ok/40 bg-ok/5 p-5">
                    @csrf
                    <h3 class="font-semibold text-ok">Poliçeye Çevir</h3>
                    <p class="mt-1 text-xs text-muted">Kabul edilen teklif: {{ $insurerLabels[$quoteRequest->acceptedQuote?->insurer] ?? '—' }}</p>
                    @error('policelestir') <p class="mt-1 text-sm text-zred">{{ $message }}</p> @enderror
                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <label class="text-sm">Poliçe No <input type="text" name="policy_no" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                        <label class="text-sm">Başlangıç <input type="date" name="start_date" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                        <label class="text-sm">Bitiş <input type="date" name="end_date" class="mt-1 w-full rounded-lg border border-line px-2 py-1.5"></label>
                        <label class="text-sm sm:col-span-3">PDF <input type="file" name="file" accept="application/pdf" class="mt-1 block text-xs"></label>
                    </div>
                    <button class="mt-3 rounded-lg bg-ok px-4 py-2 text-sm font-semibold text-white">Poliçe Oluştur</button>
                </form>
            @endif

            @php $verildiVar = $quoteRequest->quotes->contains('status', 'verildi'); @endphp

            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Şirket Teklifleri</h2>
                <form method="POST" action="{{ route('panel.quotes.ready', $quoteRequest) }}">
                    @csrf
                    <button type="submit" @disabled(! $verildiVar || $quoteRequest->status !== 'yeni')
                            class="rounded-lg bg-zred px-4 py-2 text-sm font-semibold text-white transition hover:bg-zred-dark disabled:cursor-not-allowed disabled:opacity-40">
                        Teklifleri Hazırla
                    </button>
                </form>
            </div>

            @error('hazir') <p class="text-sm text-zred">{{ $message }}</p> @enderror

            @foreach ($quoteRequest->quotes as $quote)
                <form method="POST" action="{{ route('panel.quotes.update', $quote) }}" enctype="multipart/form-data"
                      class="rounded-xl border border-line bg-white p-5">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-navy">{{ $insurerLabels[$quote->insurer] ?? $quote->insurer }}</h3>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $quote->status === 'verildi' ? 'bg-ok/10 text-ok' : 'bg-navy-tint-2 text-muted' }}">
                            {{ $quote->status }}
                        </span>
                    </div>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <label class="text-sm">Prim (TL)
                            <input type="number" step="0.01" name="premium" value="{{ $quote->premium }}" required
                                   class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
                        </label>
                        <label class="text-sm">Poliçe süresi (ay)
                            <input type="number" name="policy_period_months" value="{{ $quote->policy_period_months }}"
                                   class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
                        </label>
                        <label class="text-sm">Teklif no
                            <input type="text" name="insurer_quote_no" value="{{ $quote->insurer_quote_no }}"
                                   class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
                        </label>
                        <label class="text-sm">Geçerlilik
                            <input type="date" name="valid_until" value="{{ optional($quote->valid_until)->toDateString() }}"
                                   class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">
                        </label>
                        <label class="text-sm sm:col-span-2">Teminat özeti (her satır bir madde)
                            <textarea name="coverage_summary" rows="3"
                                      class="mt-1 w-full rounded-lg border border-line px-2 py-1.5">{{ collect($quote->coverage_summary ?? [])->implode("\n") }}</textarea>
                        </label>
                        <label class="text-sm sm:col-span-2">Teklif PDF
                            <input type="file" name="file" accept="application/pdf" class="mt-1 block w-full text-xs">
                        </label>
                    </div>
                    <button class="mt-3 rounded-lg bg-navy px-4 py-2 text-sm font-semibold text-white">Teklifi Kaydet</button>
                </form>
            @endforeach
        </div>
    </div>
@endsection
