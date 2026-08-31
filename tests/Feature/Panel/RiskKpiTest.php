<?php

use App\Domain\Risk\RiskKpi;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use App\Models\RiskEvent;
use Database\Seeders\ProductTypeSeeder;

beforeEach(function () {
    $this->seed(ProductTypeSeeder::class);
    $this->kpi = new RiskKpi;
});

function kpid(string $key): int
{
    return ProductType::where('key', $key)->value('id');
}

it('computes the awareness click rate from events', function () {
    $a = Customer::factory()->create();
    $b = Customer::factory()->create();
    $c = Customer::factory()->create();

    foreach ([$a, $b, $c] as $customer) {
        RiskEvent::create(['customer_id' => $customer->id, 'type' => 'panel_goruntulendi']);
    }
    RiskEvent::create(['customer_id' => $a->id, 'type' => 'talep_olusturuldu', 'product_key' => 'kasko']);

    $result = $this->kpi->awarenessClickRate();

    expect($result)->toMatchArray(['opened' => 3, 'clicked' => 1, 'rate' => 33]);
});

function backdate(object $model, $when): void
{
    $model->created_at = $when;
    $model->save();
}

it('counts a cross-sell conversion only within the window and branch', function () {
    $customer = Customer::factory()->create();
    $lead = RiskEvent::create(['customer_id' => $customer->id, 'type' => 'talep_olusturuldu', 'product_key' => 'konut']);
    backdate($lead, now()->subDays(3));

    // Aynı branştan, talepten 2 gün sonra kesilen poliçe -> sayılır.
    $policy = Policy::factory()->for($customer)->create(['product_type_id' => kpid('konut')]);
    backdate($policy, now()->subDay());

    $result = $this->kpi->crossSellConversion();

    expect($result)->toMatchArray(['leads' => 1, 'converted' => 1, 'rate' => 100]);
});

it('does not count a policy issued after the conversion window', function () {
    $customer = Customer::factory()->create();
    $lead = RiskEvent::create(['customer_id' => $customer->id, 'type' => 'talep_olusturuldu', 'product_key' => 'saglik']);
    backdate($lead, now()->subDays(20));

    $policy = Policy::factory()->for($customer)->create(['product_type_id' => kpid('saglik')]);
    backdate($policy, now()->subDays(5)); // talepten 15 gün sonra

    expect($this->kpi->crossSellConversion()['converted'])->toBe(0);
});

it('ignores auto branches in cross-sell', function () {
    $customer = Customer::factory()->create();
    RiskEvent::create(['customer_id' => $customer->id, 'type' => 'talep_olusturuldu', 'product_key' => 'kasko']);

    expect($this->kpi->crossSellConversion()['leads'])->toBe(0);
});

it('builds the portfolio mix with a trafik share', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->count(3)->for($customer)->create(['product_type_id' => kpid('trafik'), 'status' => 'aktif']);
    Policy::factory()->for($customer)->create(['product_type_id' => kpid('kasko'), 'status' => 'aktif']);

    $mix = $this->kpi->portfolioMix();

    expect($mix['total'])->toBe(4)
        ->and($mix['trafikPct'])->toBe(75)
        ->and($mix['mix'][0]['key'])->toBe('trafik');
});

it('renders the kpi cards on the dashboard', function () {
    actingPanel()->get('/panel')
        ->assertOk()
        ->assertSee('Farkındalık Tıklama Oranı')
        ->assertSee('Çapraz Satış Dönüşümü')
        ->assertSee('Portföyde Trafik Payı');
});
