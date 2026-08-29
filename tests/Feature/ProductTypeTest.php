<?php

use App\Models\ProductType;
use Database\Seeders\ProductTypeSeeder;

it('seeds three active products with schemas', function () {
    $this->seed(ProductTypeSeeder::class);

    expect(ProductType::active()->pluck('key')->all())->toBe(['trafik', 'kasko', 'saglik']);
    expect(ProductType::where('key', 'trafik')->first()->field_schema[0]['name'])->toBe('plaka');
});
