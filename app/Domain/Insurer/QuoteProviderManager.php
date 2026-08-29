<?php

namespace App\Domain\Insurer;

use App\Models\QuoteRequest;
use App\Providers\Insurers\ManualProvider;

class QuoteProviderManager
{
    /**
     * config('digisure.enabled_insurers') listesinden aktif sağlayıcılar.
     *
     * @return array<int, QuoteProvider>
     */
    public function providers(): array
    {
        return array_map(
            fn (string $key): QuoteProvider => new ManualProvider($key),
            config('digisure.enabled_insurers', []),
        );
    }

    /**
     * Talep için her aktif şirkete bir kotasyon satırı açar.
     */
    public function openQuotesFor(QuoteRequest $request): void
    {
        $product = $request->productType;

        foreach ($this->providers() as $provider) {
            if (! $provider->supports($product)) {
                continue;
            }

            $draft = $provider->requestQuote($request);

            $request->quotes()->create([
                'insurer' => $draft->insurer,
                'status' => $draft->status,
                'premium' => $draft->premium,
                'coverage_summary' => $draft->coverageSummary,
                'policy_period_months' => $draft->policyPeriodMonths,
                'origin' => $draft->origin,
            ]);
        }
    }
}
