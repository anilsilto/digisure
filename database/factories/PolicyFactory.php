<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Policy>
 */
class PolicyFactory extends Factory
{
    protected $model = Policy::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'product_type_id' => ProductType::factory(),
            'insurer' => 'sompo',
            'policy_no' => strtoupper(Str::random(10)),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addYear()->toDateString(),
            'premium' => 5000,
            'status' => 'aktif',
        ];
    }
}
