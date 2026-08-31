<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aktif sigorta şirketleri
    |--------------------------------------------------------------------------
    | Teklif motorunun kotasyon açacağı şirketler. API entegrasyonu gelene
    | kadar hepsi ManualProvider ile "beklemede" satır üretir.
    */
    // Platform markası (kullanıcıya görünen ad). Acente adı ayrı: 'agency.name'.
    'brand' => env('DIGISURE_BRAND', 'Polisurance'),

    'enabled_insurers' => ['sompo', 'quick', 'hepiyi', 'doga'],

    'insurer_labels' => [
        'sompo' => 'Sompo Sigorta',
        'quick' => 'Quick Sigorta',
        'hepiyi' => 'HEPİYİ Sigorta',
        'doga' => 'Doğa Sigorta',
    ],

    /*
    |--------------------------------------------------------------------------
    | Acente iletişim bilgileri
    |--------------------------------------------------------------------------
    */
    'agency' => [
        'name' => 'Zafir Sigorta',
        'email' => env('DIGISURE_AGENCY_EMAIL', 'info@sigortacimzafir.com'),
        'phone' => '0532 265 23 92',
        'phone_e164' => '905322652392',   // tel: ve WhatsApp linkleri için
        'address' => 'Zübeyde Hanım Mah. Sebze Bahçeleri Cd. No:11, 06070 Altındağ / Ankara',
        'experience_years' => 20,
        'licence' => 'SEDDK lisanslı sigorta brokeri',
        'slogan' => 'Geleceğinizi Güvence Altına Alıyoruz',
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
            'header' => env('NETGSM_HEADER'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Müşteri OTP
    |--------------------------------------------------------------------------
    | Aynı telefona saatte kaç kod gönderilebilir. Local'de test kolaylığı için yüksek.
    */
    'otp' => [
        'max_sends_per_hour' => (int) env('DIGISURE_OTP_MAX_SENDS_PER_HOUR', env('APP_ENV') === 'local' ? 30 : 3),
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
    /*
    |--------------------------------------------------------------------------
    | "Zafir Güvence Karma" kampanyası
    |--------------------------------------------------------------------------
    | Müşteri toplam >= threshold puana ulaşınca ödül menüsünden 1 hak seçer.
    | branches: kampanyaya özel puan listesi (sistemdeki ürün tiplerinden bağımsız).
    | packages: baraj altındaki müşteriye önerilecek hazır kombinasyonlar.
    */
    'campaign' => [
        'name' => 'Zafir Güvence Karma',
        'threshold' => 5,

        'branches' => [
            'kasko' => ['label' => 'Kasko', 'points' => 5],
            'tss' => ['label' => 'Tamamlayıcı Sağlık (TSS)', 'points' => 5],
            'konut' => ['label' => 'Konut Sigortası', 'points' => 3],
            'imm' => ['label' => 'İMM (Yüksek Limitli)', 'points' => 2],
            'ferdi_kaza' => ['label' => 'Ferdi Kaza', 'points' => 1],
            'dask' => ['label' => 'DASK', 'points' => 1],
            'trafik' => ['label' => 'Trafik', 'points' => 0],
        ],

        'rewards' => [
            'oto_bakim' => ['label' => 'Oto Bakım & Test Paketi', 'desc' => 'Periyodik bakım ve check-up hizmetlerinde %10 indirim.'],
            'mobilite' => ['label' => 'Mobilite Desteği', 'desc' => 'Kısa süreli araç kiralamalarında %10 indirim avantajı.'],
            'lastik_vip' => ['label' => 'Lastik VIP Hizmeti', 'desc' => 'Lastik değişim, balans ve depolama hizmetlerinde öncelikli VIP servis.'],
            'lastik_fiyat' => ['label' => 'Lastik Fiyat Koruması', 'desc' => 'Yıl boyunca zamlardan etkilenmeden, sabit fiyat garantisiyle lastik alım hakkı.'],
        ],

        'packages' => [
            ['label' => 'Stratejik Paket (Ev + Araç)', 'branches' => ['konut', 'imm']],
            ['label' => 'Tam Koruma Paketi', 'branches' => ['konut', 'dask', 'ferdi_kaza']],
            ['label' => 'Premium Yol (Hızlı Kazanım)', 'branches' => ['kasko']],
            ['label' => 'Sadakat Yolu', 'branches' => ['tss']],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Müşteri Risk & Güvence Paneli
    |--------------------------------------------------------------------------
    | Varlık tipi => o varlık için beklenen ürün branşları. Her branş gerçek bir
    | product_type.key'tir, böylece her eksiğin bir "Teklif Al" hedefi olur.
    | weight: skora katkı ağırlığı. severity: kirmizi | sari. note: teminat hatırlatması.
    | bands: skor <= yesil ise yeşil, <= sari ise sarı, üstü kırmızı.
    */
    'risk' => [
        'bands' => ['yesil' => 30, 'sari' => 60],

        'rules' => [
            'arac' => [
                'trafik' => ['label' => 'Zorunlu Trafik Sigortası', 'weight' => 20, 'severity' => 'kirmizi'],
                'kasko' => ['label' => 'Kasko', 'weight' => 30, 'severity' => 'kirmizi',
                    'note' => 'Kaskonuza İMM (İhtiyari Mali Mesuliyet) teminatı eklenmesini de değerlendirin.'],
            ],
            'konut' => [
                'konut' => ['label' => 'Konut Paket Sigortası', 'weight' => 25, 'severity' => 'kirmizi',
                    'note' => 'Zorunlu DASK poliçenizin güncel olduğundan emin olun.'],
            ],
            'isyeri' => [
                'isyeri' => ['label' => 'İşyeri Paket Sigortası', 'weight' => 25, 'severity' => 'kirmizi',
                    'note' => 'Yangın, hırsızlık ve kâr kaybı teminatlarını kapsadığından emin olun.'],
            ],
            'kisi' => [
                'saglik' => ['label' => 'Tamamlayıcı Sağlık Sigortası', 'weight' => 15, 'severity' => 'sari'],
                'ferdi-kaza' => ['label' => 'Ferdi Kaza Sigortası', 'weight' => 10, 'severity' => 'sari'],
            ],
        ],
    ],

    'rates' => [
        // 2026 taslak değerler — acente güncelleyecek. cc dilimi => yaş dilimi => yıllık TL.
        'mtv' => [
            '0-1300' => ['1-3' => 4600,  '4-6' => 3200,  '7-11' => 1800,  '12-15' => 700,   '16+' => 250],
            '1301-1600' => ['1-3' => 8000,  '4-6' => 6000,  '7-11' => 3500,  '12-15' => 1500,  '16+' => 600],
            '1601-1800' => ['1-3' => 14000, '4-6' => 11000, '7-11' => 6500,  '12-15' => 2800,  '16+' => 1100],
            '1801-2000' => ['1-3' => 22000, '4-6' => 17000, '7-11' => 10000, '12-15' => 4000,  '16+' => 1600],
            '2001-2500' => ['1-3' => 33000, '4-6' => 24000, '7-11' => 15000, '12-15' => 6000,  '16+' => 2400],
            '2501+' => ['1-3' => 60000, '4-6' => 43000, '7-11' => 26000, '12-15' => 11000, '16+' => 4300],
        ],
        // 2026 taslak — ilk eşleşen dilimdeki oran. cc_max/max_price null = üst sınır yok.
        'otv' => [
            ['cc_max' => 1600, 'max_price' => 184000, 'rate' => 45.0],
            ['cc_max' => 1600, 'max_price' => 220000, 'rate' => 50.0],
            ['cc_max' => 1600, 'max_price' => null,   'rate' => 80.0],
            ['cc_max' => 2000, 'max_price' => 280000, 'rate' => 130.0],
            ['cc_max' => 2000, 'max_price' => null,   'rate' => 150.0],
            ['cc_max' => null, 'max_price' => null,   'rate' => 220.0],
        ],
        'fuel' => [
            'default_consumption' => 7.5,   // L / 100 km
            'price_per_litre' => 45.0,  // TL
        ],
        'kasko' => [
            'default_rate_pct' => 4.0,      // referans değerin %'si — acente güncelleyecek
        ],
    ],

];
