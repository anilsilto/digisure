<?php

use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('shows only my own quote requests', function () {
    $mine = makeQuoteRequest('trafik');
    $other = makeQuoteRequest('kasko');

    $this->actingAs($mine->customer, 'customer')
        ->get('/hesabim/teklifler')
        ->assertSee($mine->reference_no)
        ->assertDontSee($other->reference_no);
});

it('404s on someone elses request', function () {
    $mine = makeQuoteRequest('trafik');
    $other = makeQuoteRequest('kasko');

    $this->actingAs($mine->customer, 'customer')
        ->get("/hesabim/teklifler/{$other->id}")
        ->assertNotFound();
});

it('renders comparison sorted by premium and accepts a quote', function () {
    $r = makeQuoteRequest('kasko');
    $r->quotes()->where('insurer', 'sompo')->update(['status' => 'verildi', 'premium' => 15000]);
    $r->quotes()->where('insurer', 'quick')->update(['status' => 'verildi', 'premium' => 12000]);

    $this->actingAs($r->customer, 'customer')
        ->get("/hesabim/teklifler/{$r->id}")
        ->assertSeeInOrder(['Quick Sigorta', 'Sompo Sigorta']);

    $cheapest = $r->quotes()->where('insurer', 'quick')->first();

    $this->actingAs($r->customer, 'customer')
        ->post("/hesabim/teklifler/{$r->id}/sec/{$cheapest->id}")
        ->assertRedirect();

    expect($r->fresh()->accepted_quote_id)->toBe($cheapest->id)
        ->and($r->fresh()->status)->toBe('kabul');
});

it('does not let a customer accept a quote from another request', function () {
    $mine = makeQuoteRequest('trafik');
    $other = makeQuoteRequest('kasko');
    $otherQuote = $other->quotes()->first();
    $otherQuote->update(['status' => 'verildi', 'premium' => 100]);

    $this->actingAs($mine->customer, 'customer')
        ->post("/hesabim/teklifler/{$mine->id}/sec/{$otherQuote->id}")
        ->assertNotFound();
});
