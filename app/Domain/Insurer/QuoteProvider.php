<?php

namespace App\Domain\Insurer;

use App\Models\ProductType;
use App\Models\QuoteRequest;

/**
 * Her sigorta şirketi için bir implementasyon. v1'de hepsi ManualProvider.
 * API geldiğinde SompoProvider vb. gerçek HTTP çağrısıyla doldurulur.
 */
interface QuoteProvider
{
    public function key(): string;

    public function supports(ProductType $product): bool;

    public function requestQuote(QuoteRequest $request): QuoteDraft;
}
