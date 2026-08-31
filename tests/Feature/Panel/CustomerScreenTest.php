<?php

use App\Models\Customer;
use App\Models\QuoteRequest;
use App\Models\RiskEvent;
use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('guards the customer screens from guests', function () {
    $this->get('/panel/musteriler')->assertRedirect('/panel/giris');
});

it('lists customers and filters by name', function () {
    Customer::factory()->create(['first_name' => 'Ayşe', 'last_name' => 'Kaya']);
    Customer::factory()->create(['first_name' => 'Mehmet', 'last_name' => 'Demir']);

    actingPanel()->get('/panel/musteriler?q=Kaya')
        ->assertOk()
        ->assertSee('Ayşe Kaya')
        ->assertDontSee('Mehmet Demir');
});

it('shows a customer risk report with assets', function () {
    $customer = Customer::factory()->create();
    $customer->assets()->create(['type' => 'konut', 'label' => 'Bostancı', 'source' => 'musteri', 'status' => 'aktif']);

    actingPanel()->get("/panel/musteriler/{$customer->id}")
        ->assertOk()
        ->assertSee('Risk Raporu')
        ->assertSee('Bostancı');
});

it('lets the agent add and remove an asset', function () {
    $customer = Customer::factory()->create();

    actingPanel()->post("/panel/musteriler/{$customer->id}/varlik", ['type' => 'isyeri', 'label' => 'Depo'])
        ->assertRedirect();

    $asset = $customer->assets()->sole();
    expect($asset->source)->toBe('acente');

    actingPanel()->delete("/panel/musteriler/{$customer->id}/varlik/{$asset->id}")->assertRedirect();
    expect($asset->fresh()->status)->toBe('pasif');
});

it('creates a panel-sourced quote request without a risk event', function () {
    $customer = Customer::factory()->create();

    actingPanel()->post("/panel/musteriler/{$customer->id}/risk-talep", ['product_key' => 'konut'])
        ->assertRedirect();

    expect(QuoteRequest::sole()->source)->toBe('panel');
    expect(RiskEvent::count())->toBe(0);
});
