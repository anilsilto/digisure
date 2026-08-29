<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductType>
 */
class ProductTypeFactory extends Factory
{
    protected $model = ProductType::class;

    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(1),
            'name' => fake()->word(),
            'icon' => null,
            'field_schema' => [],
            'is_active' => true,
            'sort' => 0,
        ];
    }
}
