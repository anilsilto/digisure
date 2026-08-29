<?php

use App\Models\QuoteRequest;
use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('creates a quote request with dynamic fields, customer, and 4 pending quotes', function () {
    $res = $this->post('/teklif', [
        'urun' => 'trafik',
        'fields' => [
            'plaka' => '06ABC123', 'tc_kimlik' => '12345678901', 'ruhsat_belge_no' => 'AB123456',
            'tescil_tarihi' => '2020-01-01', 'kullanim_tarzi' => 'Hususi',
        ],
        'ad' => 'Ali', 'soyad' => 'Veli', 'tc_no' => '12345678901',
        'telefon' => '05322652392', 'eposta' => 'a@b.com',
        'kvkk' => '1',
    ]);

    $res->assertRedirect();

    $r = QuoteRequest::first();
    expect($r->status)->toBe('yeni')
        ->and($r->source)->toBe('site')
        ->and($r->reference_no)->toHaveLength(8);
    expect($r->fields()->pluck('value', 'field_key')['plaka'])->toBe('06ABC123');
    expect($r->quotes()->pluck('status', 'insurer')->all())
        ->toBe(['sompo' => 'beklemede', 'quick' => 'beklemede', 'hepiyi' => 'beklemede', 'doga' => 'beklemede']);
    expect($r->customer->kvkk_consent_at)->not->toBeNull();
});

it('refuses without KVKK consent', function () {
    $this->post('/teklif', [
        'urun' => 'trafik',
        'fields' => [
            'plaka' => 'x', 'tc_kimlik' => '12345678901', 'ruhsat_belge_no' => 'x',
            'tescil_tarihi' => '2020-01-01', 'kullanim_tarzi' => 'Hususi',
        ],
        'ad' => 'A', 'soyad' => 'B', 'tc_no' => '12345678901', 'telefon' => '05322652392',
    ])->assertSessionHasErrors('kvkk');

    expect(QuoteRequest::count())->toBe(0);
});

it('validates dynamic required fields', function () {
    $this->post('/teklif', [
        'urun' => 'trafik',
        'fields' => ['plaka' => ''],
        'ad' => 'A', 'soyad' => 'B', 'tc_no' => '12345678901', 'telefon' => '05322652392', 'kvkk' => '1',
    ])->assertSessionHasErrors('fields.plaka');
});
