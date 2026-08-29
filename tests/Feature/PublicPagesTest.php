<?php

use Database\Seeders\ProductTypeSeeder;

it('renders all public pages', function () {
    $this->seed(ProductTypeSeeder::class);

    foreach (['/', '/hakkimizda', '/iletisim', '/kvkk-aydinlatma', '/trafik-sigortasi', '/kasko-sigortasi', '/saglik-sigortasi'] as $url) {
        $this->get($url)->assertOk();
    }
});

it('home lists the three products with quote CTAs', function () {
    $this->seed(ProductTypeSeeder::class);

    $this->get('/')
        ->assertSee('Trafik')
        ->assertSee('Kasko')
        ->assertSee('Sağlık')
        ->assertSee('Teklif Al');
});
