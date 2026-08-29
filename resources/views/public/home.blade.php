@extends('layouts.public')

@section('content')
    <section class="bg-navy text-white">
        <div class="mx-auto max-w-6xl px-4 py-20">
            <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-white/70">{{ config('digisure.agency.name') }}</p>
            <h1 class="max-w-2xl text-4xl font-extrabold leading-tight sm:text-5xl">
                {{ config('digisure.agency.slogan') }}
            </h1>
            <p class="mt-4 max-w-xl text-white/80">
                Trafik, kasko ve sağlık sigortasında birden fazla şirketin teklifini tek ekranda karşılaştırın.
            </p>
            <a href="{{ url('/teklif') }}"
               class="mt-8 inline-block rounded-lg bg-zred px-6 py-3 font-semibold text-white shadow transition hover:bg-zred-dark">
                Hemen Teklif Al
            </a>
        </div>
    </section>
@endsection
