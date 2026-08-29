<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aktif sigorta şirketleri
    |--------------------------------------------------------------------------
    | Teklif motorunun kotasyon açacağı şirketler. API entegrasyonu gelene
    | kadar hepsi ManualProvider ile "beklemede" satır üretir.
    */
    'enabled_insurers' => ['sompo', 'quick', 'hepiyi', 'doga'],

    'insurer_labels' => [
        'sompo'  => 'Sompo Sigorta',
        'quick'  => 'Quick Sigorta',
        'hepiyi' => 'HEPİYİ Sigorta',
        'doga'   => 'Doğa Sigorta',
    ],

    /*
    |--------------------------------------------------------------------------
    | Acente iletişim bilgileri
    |--------------------------------------------------------------------------
    */
    'agency' => [
        'name'    => 'Zafir Sigorta',
        'email'   => env('DIGISURE_AGENCY_EMAIL', 'info@sigortacimzafir.com'),
        'phone'   => '0532 265 23 92',
        'address' => 'Zübeyde Hanım Mah. Sebze Bahçeleri Cd. No:11, 06070 Altındağ / Ankara',
        'licence' => 'SEDDK lisanslı sigorta brokeri',
        'slogan'  => 'Geleceğinizi Güvence Altına Alıyoruz',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS gönderimi
    |--------------------------------------------------------------------------
    | driver: 'log' (geliştirme) | 'netgsm' (canlı)
    */
    'sms' => [
        'driver' => env('DIGISURE_SMS_DRIVER', 'log'),
        'netgsm' => [
            'usercode' => env('NETGSM_USERCODE'),
            'password' => env('NETGSM_PASSWORD'),
            'header'   => env('NETGSM_HEADER'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Yenileme hatırlatmaları
    |--------------------------------------------------------------------------
    | Poliçe bitişinden kaç gün önce hatırlatma üretilecek.
    */
    'reminders' => [
        'days_before' => [30, 15, 7],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hesaplama aracı oran tabloları  —  2026 taslak, acente güncelleyecek
    |--------------------------------------------------------------------------
    | Nihai tablo yapısı Task 13'te doldurulur.
    */
    'rates' => [
        'mtv'  => [],
        'otv'  => [],
        'fuel' => [
            'default_consumption' => 7.5,   // L / 100 km
            'price_per_litre'     => 45.0,  // TL
        ],
    ],

];
