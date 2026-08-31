<?php

use App\Domain\Risk\AssetDeriver;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use Database\Seeders\ProductTypeSeeder;

beforeEach(function () {
    $this->seed(ProductTypeSeeder::class);
    $this->deriver = new AssetDeriver;
});

function productId(string $key): int
{
    return ProductType::where('key', $key)->value('id');
}

it('derives an arac asset from a kasko policy', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->for($customer)->create(['product_type_id' => productId('kasko')]);

    $created = $this->deriver->sync($customer);

    expect($created)->toBe(1);
    expect($customer->assets()->first())
        ->type->toBe('arac')
        ->label->toBe('Aracınız')
        ->source->toBe('turetilmis');
});

it('derives konut and isyeri assets alongside arac', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->for($customer)->create(['product_type_id' => productId('trafik')]);
    Policy::factory()->for($customer)->create(['product_type_id' => productId('konut')]);
    Policy::factory()->for($customer)->create(['product_type_id' => productId('isyeri')]);

    $this->deriver->sync($customer);

    expect($customer->assets()->pluck('type')->sort()->values()->all())
        ->toBe(['arac', 'isyeri', 'konut']);
});

it('labels an arac asset with the plate from a quote request', function () {
    $customer = Customer::factory()->create();
    $request = $customer->quoteRequests()->create([
        'product_type_id' => productId('trafik'),
        'status' => 'yeni',
        'source' => 'site',
        'reference_no' => 'REF123',
    ]);
    $request->fields()->create(['field_key' => 'plaka', 'value' => '34 abc 123']);

    $this->deriver->sync($customer);

    expect($customer->assets()->first()->label)->toBe('34ABC123');
});

it('ignores health and personal-accident branches', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->for($customer)->create(['product_type_id' => productId('saglik')]);
    Policy::factory()->for($customer)->create(['product_type_id' => productId('ferdi-kaza')]);

    $created = $this->deriver->sync($customer);

    expect($created)->toBe(0);
    expect($customer->assets()->count())->toBe(0);
});

it('does not create duplicates on repeated sync', function () {
    $customer = Customer::factory()->create();
    Policy::factory()->for($customer)->create(['product_type_id' => productId('kasko')]);
    Policy::factory()->for($customer)->create(['product_type_id' => productId('trafik')]);

    $this->deriver->sync($customer);
    $second = $this->deriver->sync($customer);

    expect($second)->toBe(0);
    expect($customer->assets()->where('type', 'arac')->count())->toBe(1);
});

it('keeps manually added assets untouched', function () {
    $customer = Customer::factory()->create();
    $customer->assets()->create(['type' => 'arac', 'label' => 'Yazlık araba', 'source' => 'musteri', 'status' => 'aktif']);
    Policy::factory()->for($customer)->create(['product_type_id' => productId('kasko')]);

    $this->deriver->sync($customer);

    expect($customer->assets()->pluck('label')->sort()->values()->all())
        ->toBe(['Aracınız', 'Yazlık araba']);
});
