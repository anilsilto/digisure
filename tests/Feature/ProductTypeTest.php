<?php

use App\Models\ProductType;
use Database\Seeders\ProductTypeSeeder;

it('seeds the core products with schemas', function () {
    $this->seed(ProductTypeSeeder::class);

    $keys = ProductType::active()->pluck('key')->all();
    expect($keys)->toContain('trafik', 'kasko', 'saglik');
    expect($keys[0])->toBe('trafik'); // sort sırası korunur
    expect(ProductType::where('key', 'trafik')->first()->field_schema[0]['name'])->toBe('plaka');
});
