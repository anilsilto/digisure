<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QuoteRequest>
 */
class QuoteRequestFactory extends Factory
{
    protected $model = QuoteRequest::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'product_type_id' => ProductType::factory(),
            'status' => 'yeni',
            'source' => 'site',
            'reference_no' => strtoupper(Str::random(8)),
        ];
    }
}
