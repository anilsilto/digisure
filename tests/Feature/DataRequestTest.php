<?php

use App\Models\DataRequest;
use App\Models\ProductType;
use App\Models\User;
use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('lets a customer file a deletion request', function () {
    $r = makeQuoteRequest('trafik');

    $this->actingAs($r->customer, 'customer')
        ->post('/hesabim/veri-talebi', ['type' => 'sil'])
        ->assertRedirect();

    expect(DataRequest::where('type', 'sil')->where('customer_id', $r->customer->id)->exists())->toBeTrue();
});

it('lets an admin toggle a product and edit its schema', function () {
    $p = ProductType::where('key', 'trafik')->first();

    actingPanel(User::factory()->admin()->create())
        ->put("/panel/urunler/{$p->id}", [
            'name' => 'Trafik X',
            'is_active' => '0',
            'sort' => 5,
            'field_schema' => json_encode([['name' => 'plaka', 'label' => 'Plaka', 'type' => 'text', 'required' => true]]),
        ])
        ->assertRedirect();

    $p->refresh();
    expect($p->name)->toBe('Trafik X')
        ->and($p->is_active)->toBeFalse()
        ->and($p->field_schema[0]['name'])->toBe('plaka');
});

it('forbids personel from editing products', function () {
    $p = ProductType::where('key', 'trafik')->first();

    actingPanel(User::factory()->personel()->create())
        ->put("/panel/urunler/{$p->id}", ['name' => 'Nope', 'field_schema' => '[]'])
        ->assertForbidden();
});
