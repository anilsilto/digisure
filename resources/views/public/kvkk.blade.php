@extends('layouts.public')

@section('title', 'KVKK Aydınlatma Metni — ' . config('digisure.agency.name'))

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 text-sm leading-relaxed text-muted">
        <h1 class="text-3xl font-extrabold text-ink">KVKK Aydınlatma Metni</h1>

        <p class="mt-6">
            6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, veri sorumlusu sıfatıyla
            {{ config('digisure.agency.name') }} tarafından kişisel verileriniz aşağıda açıklanan
            kapsamda işlenmektedir.
        </p>

        <h2 class="mt-6 font-semibold text-ink">İşlenen veriler ve amaç</h2>
        <p class="mt-2">
            Ad-soyad, T.C. kimlik numarası, iletişim bilgileri ve sigorta teklifi için gerekli araç/sağlık
            bilgileri; teklif hazırlanması, poliçe düzenlenmesi, yenileme hatırlatmaları ve yasal
            yükümlülüklerin yerine getirilmesi amacıyla işlenir.
        </p>

        <h2 class="mt-6 font-semibold text-ink">Aktarım</h2>
        <p class="mt-2">
            Verileriniz, yalnızca teklif ve poliçe süreçleri için anlaşmalı sigorta şirketleri ve yetkili
            kamu kurumları ile paylaşılır.
        </p>

        <h2 class="mt-6 font-semibold text-ink">Haklarınız</h2>
        <p class="mt-2">
            KVKK md. 11 kapsamında verilerinize erişme, düzeltilmesini veya silinmesini isteme haklarına
            sahipsiniz. Taleplerinizi {{ config('digisure.agency.email') }} adresine iletebilirsiniz.
        </p>
    </section>
@endsection
