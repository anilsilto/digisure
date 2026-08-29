<?php

use App\Domain\Quote\QuoteRequestService;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(fn () => $this->withoutVite())
    ->in('Feature');

pest()->extend(TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Ortak test yardımcıları
|--------------------------------------------------------------------------
*/

/**
 * Gerçek akışla (QuoteRequestService) bir teklif talebi + 4 beklemede kotasyon üretir.
 */
function makeQuoteRequest(string $productKey = 'trafik'): QuoteRequest
{
    $product = ProductType::where('key', $productKey)->first()
        ?? ProductType::factory()->create(['key' => $productKey, 'name' => ucfirst($productKey), 'field_schema' => []]);

    return app(QuoteRequestService::class)->create(
        product: $product,
        fields: [],
        customerAttrs: [
            'tc_no' => (string) fake()->numerify('###########'),
            'first_name' => 'Test',
            'last_name' => 'Musteri',
            'phone' => '05'.fake()->numerify('#########'),
            'email' => fake()->unique()->safeEmail(),
        ],
        source: 'site',
        kvkk: true,
    );
}

/**
 * Kabul edilmiş (accepted_quote_id dolu, status=kabul) bir teklif talebi.
 */
function makeAcceptedQuoteRequest(string $productKey = 'trafik', float $premium = 5000, string $insurer = 'sompo'): QuoteRequest
{
    $request = makeQuoteRequest($productKey);
    $quote = $request->quotes()->where('insurer', $insurer)->first();
    $quote->update(['status' => 'verildi', 'premium' => $premium, 'policy_period_months' => 12]);
    $request->update(['status' => 'kabul', 'accepted_quote_id' => $quote->id]);

    return $request->fresh();
}

/**
 * Panel guard'ında bir personel (veya verilen kullanıcı) olarak oturum açar.
 */
function actingPanel(?User $user = null): TestCase
{
    return test()->actingAs($user ?? User::factory()->personel()->create(), 'panel');
}
