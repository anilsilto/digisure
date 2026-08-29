<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\QuoteRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'quote_request_id' => QuoteRequest::factory(),
            'insurer' => 'sompo',
            'status' => 'beklemede',
            'origin' => 'manuel',
        ];
    }
}
