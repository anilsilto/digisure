<?php

use App\Domain\Risk\RiskAnalyzer;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use Database\Seeders\ProductTypeSeeder;

beforeEach(function () {
    $this->seed(ProductTypeSeeder::class);
    $this->analyzer = new RiskAnalyzer;
});

function pid(string $key): int
{
    return ProductType::where('key', $key)->value('id');
}

it('always evaluates the implicit person block even with no declared assets', function () {
    $customer = Customer::factory()->create();

    $report = $this->analyzer->forCustomer($customer);

    expect($report->hasDeclaredAssets)->toBeFalse();
    expect(collect($report->assets)->pluck('type')->all())->toBe(['kisi']);
    expect(collect($report->assets)->firstWhere('type', 'kisi')['items'])->toHaveCount(2); // saglik + ferdi-kaza
});

it('flags every expected cover as missing for a bare car owner', function () {
    $customer = Customer::factory()->create();
    $customer->assets()->create(['type' => 'arac', 'label' => '34 XY 99', 'source' => 'musteri', 'status' => 'aktif']);

    $report = $this->analyzer->forCustomer($customer);

    $arac = collect($report->assets)->firstWhere('type', 'arac');
    expect($arac['missingCount'])->toBe(2)
        ->and(collect($arac['items'])->pluck('has')->all())->toBe([false, false]);

    // kasko (30) çapraz-satışta trafikten (20) önce gelmeli
    expect($report->topActions[0]['product_key'])->toBe('kasko');
    expect($report->band)->toBe('kirmizi');
});

it('marks a cover as held when an active policy exists', function () {
    $customer = Customer::factory()->create();
    $customer->assets()->create(['type' => 'arac', 'label' => 'Aracınız', 'source' => 'musteri', 'status' => 'aktif']);
    Policy::factory()->for($customer)->create(['product_type_id' => pid('trafik'), 'status' => 'aktif']);

    $report = $this->analyzer->forCustomer($customer);

    $arac = collect($report->assets)->firstWhere('type', 'arac');
    expect(collect($arac['items'])->firstWhere('product_key', 'trafik')['has'])->toBeTrue()
        ->and(collect($arac['items'])->firstWhere('product_key', 'kasko')['has'])->toBeFalse();
});

it('ignores cancelled or expired policies', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->for($customer)->create(['product_type_id' => pid('saglik'), 'status' => 'iptal']);

    $report = $this->analyzer->forCustomer($customer);

    $has = collect(collect($report->assets)->firstWhere('type', 'kisi')['items'])->pluck('has')->all();
    expect($has)->toBe([false, false]);
});

it('honours campaign-declared branches as held cover', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create(['branches' => ['tss', 'ferdi_kaza']]);

    $report = $this->analyzer->forCustomer($customer);

    $has = collect(collect($report->assets)->firstWhere('type', 'kisi')['items'])->pluck('has')->all();
    expect($has)->toBe([true, true]);
});

it('returns zero risk and green band for a fully covered customer', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->for($customer)->create(['product_type_id' => pid('saglik'), 'status' => 'aktif']);
    Policy::factory()->for($customer)->create(['product_type_id' => pid('ferdi-kaza'), 'status' => 'aktif']);

    $report = $this->analyzer->forCustomer($customer);

    expect($report->riskPct)->toBe(0)
        ->and($report->band)->toBe('yesil')
        ->and($report->topActions)->toBe([]);
});

it('computes the risk percentage from missing weight share', function () {
    $customer = Customer::factory()->create();
    // Sadece kişi bloğu: saglik(15) + ferdi-kaza(10) = 25 toplam. saglik var, ferdi-kaza yok -> 10/25 = %40
    Policy::factory()->for($customer)->create(['product_type_id' => pid('saglik'), 'status' => 'aktif']);

    $report = $this->analyzer->forCustomer($customer);

    expect($report->riskPct)->toBe(40)
        ->and($report->band)->toBe('sari');
});
