<?php

use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('lists and filters quote requests', function () {
    $r = makeQuoteRequest('trafik');

    actingPanel()->get('/panel/teklifler?durum=yeni')
        ->assertOk()
        ->assertSee($r->reference_no);
});

it('shows a request detail with masked customer data', function () {
    $r = makeQuoteRequest('trafik');

    actingPanel()->get("/panel/teklifler/{$r->id}")
        ->assertOk()
        ->assertSee($r->reference_no)
        ->assertSee('Sompo Sigorta');
});

it('guards the inbox from guests', function () {
    $this->get('/panel/teklifler')->assertRedirect('/panel/giris');
});

it('tags risk panel requests in the inbox', function () {
    $r = makeQuoteRequest('saglik');
    $r->update(['source' => 'risk_paneli']);

    actingPanel()->get('/panel/teklifler')
        ->assertOk()
        ->assertSee('Risk Paneli');
});
