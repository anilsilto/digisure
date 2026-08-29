@extends('layouts.public')

@section('title', 'Hakkımızda — ' . config('digisure.agency.name'))

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16">
        <h1 class="text-3xl font-extrabold text-ink">Hakkımızda</h1>
        <p class="mt-4 text-muted">
            {{ config('digisure.agency.name') }}, 20 yılı aşkın deneyimiyle bireysel ve kurumsal
            müşterilerine trafik, kasko ve sağlık başta olmak üzere geniş bir sigorta yelpazesinde
            hizmet vermektedir. Amacımız, doğru teminatı en uygun fiyata sunmak ve hasar süreçlerinde
            yanınızda olmak.
        </p>
        <p class="mt-4 text-muted">{{ config('digisure.agency.licence') }}.</p>

        <div class="mt-8 rounded-xl border border-line bg-navy-tint p-6 text-sm text-muted">
            <p><strong class="text-ink">Telefon:</strong> {{ config('digisure.agency.phone') }}</p>
            <p><strong class="text-ink">E-posta:</strong> {{ config('digisure.agency.email') }}</p>
            <p><strong class="text-ink">Adres:</strong> {{ config('digisure.agency.address') }}</p>
        </div>
    </section>
@endsection
