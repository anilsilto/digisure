<?php

it('serves all calculator pages', function () {
    foreach (['/hesaplama', '/hesaplama/mtv', '/hesaplama/otv', '/hesaplama/yakit', '/hesaplama/kasko-deger'] as $url) {
        $this->get($url)->assertOk();
    }
});

it('computes fuel cost through the form', function () {
    $this->post('/hesaplama/yakit', ['mesafe' => 100, 'tuketim' => 8, 'fiyat' => 45])
        ->assertOk()
        ->assertSee('360');
});
