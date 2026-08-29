<?php

use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('updates profile contact + marketing consent', function () {
    $r = makeQuoteRequest('trafik');

    $this->actingAs($r->customer, 'customer')
        ->put('/hesabim/profil', ['email' => 'yeni@x.com', 'address' => 'Ankara', 'marketing_consent' => '1'])
        ->assertRedirect();

    expect($r->customer->fresh()->email)->toBe('yeni@x.com')
        ->and($r->customer->fresh()->address)->toBe('Ankara')
        ->and($r->customer->fresh()->marketing_consent_at)->not->toBeNull();
});

it('clears marketing consent when unchecked', function () {
    $r = makeQuoteRequest('trafik');
    $r->customer->update(['marketing_consent_at' => now()]);

    $this->actingAs($r->customer, 'customer')
        ->put('/hesabim/profil', ['email' => 'x@y.com'])
        ->assertRedirect();

    expect($r->customer->fresh()->marketing_consent_at)->toBeNull();
});
