<?php

use App\Models\Customer;
use App\Models\QuoteRequest;
use App\Models\RiskEvent;
use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('guards the risk page from guests', function () {
    $this->get('/hesabim/risklerim')->assertRedirect('/hesabim/giris');
});

it('shows the risk score and missing covers', function () {
    $customer = Customer::factory()->create();
    $customer->assets()->create(['type' => 'arac', 'label' => '34 TEST 34', 'source' => 'musteri', 'status' => 'aktif']);

    $this->actingAs($customer, 'customer')
        ->get('/hesabim/risklerim')
        ->assertOk()
        ->assertSee('Risk Skorunuz')
        ->assertSee('34 TEST 34')
        ->assertSee('Riski Kapat / Teklif Al');
});

it('logs a panel view once per day', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')->get('/hesabim/risklerim')->assertOk();
    $this->actingAs($customer, 'customer')->get('/hesabim/risklerim')->assertOk();

    expect(RiskEvent::where('type', 'panel_goruntulendi')->count())->toBe(1);
});

it('lets the customer add and remove an asset', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/risklerim/varlik', ['type' => 'konut', 'label' => 'Çankaya dairesi'])
        ->assertRedirect();

    $asset = $customer->assets()->sole();
    expect($asset)->type->toBe('konut')->source->toBe('musteri');

    $this->actingAs($customer, 'customer')
        ->delete("/hesabim/risklerim/varlik/{$asset->id}")
        ->assertRedirect();

    expect($asset->fresh()->status)->toBe('pasif');
});

it('rejects an invalid asset type', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/risklerim/varlik', ['type' => 'yat', 'label' => 'Tekne'])
        ->assertSessionHasErrors('type');
});

it('blocks removing another customer\'s asset', function () {
    $mine = Customer::factory()->create();
    $other = Customer::factory()->create();
    $asset = $other->assets()->create(['type' => 'arac', 'label' => 'x', 'source' => 'musteri', 'status' => 'aktif']);

    $this->actingAs($mine, 'customer')
        ->delete("/hesabim/risklerim/varlik/{$asset->id}")
        ->assertForbidden();

    expect($asset->fresh()->status)->toBe('aktif');
});

it('creates a quote request tagged risk_paneli when closing a gap', function () {
    $customer = Customer::factory()->create();
    $asset = $customer->assets()->create(['type' => 'arac', 'label' => '06 ABC 06', 'source' => 'musteri', 'status' => 'aktif']);

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/risklerim/talep', ['product_key' => 'kasko', 'asset_id' => $asset->id])
        ->assertRedirect(route('teklif.received'));

    $request = QuoteRequest::sole();
    expect($request)
        ->source->toBe('risk_paneli')
        ->customer_id->toBe($customer->id);
    expect($request->productType->key)->toBe('kasko');

    expect(RiskEvent::where('type', 'talep_olusturuldu')->first())
        ->product_key->toBe('kasko')
        ->quote_request_id->toBe($request->id)
        ->customer_asset_id->toBe($asset->id);
});

it('rejects a lead for an unknown product', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/risklerim/talep', ['product_key' => 'uzay-aracı'])
        ->assertSessionHasErrors('product_key');

    expect(QuoteRequest::count())->toBe(0);
});
