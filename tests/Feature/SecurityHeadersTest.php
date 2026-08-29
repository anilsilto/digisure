<?php

use Database\Seeders\ProductTypeSeeder;

it('sends security headers', function () {
    $this->get('/')
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'same-origin');
});

it('throttles the quote form', function () {
    $this->seed(ProductTypeSeeder::class);

    $res = null;
    foreach (range(1, 7) as $i) {
        $res = $this->post('/teklif', ['urun' => 'trafik', 'fields' => ['plaka' => "06X{$i}"]]);
    }

    expect($res->status())->toBe(429);
});
