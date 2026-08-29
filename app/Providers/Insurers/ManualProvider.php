<?php

namespace App\Providers\Insurers;

use App\Domain\Insurer\QuoteDraft;
use App\Domain\Insurer\QuoteProvider;
use App\Models\ProductType;
use App\Models\QuoteRequest;

/**
 * v1: API yok. Talep geldiğinde şirket için "beklemede" bir kotasyon satırı açar;
 * acente personeli primi ve teminatı panelden doldurur.
 */
class ManualProvider implements QuoteProvider
{
    public function __construct(private readonly string $insurerKey) {}

    public function key(): string
    {
        return $this->insurerKey;
    }

    public function supports(ProductType $product): bool
    {
        return true;
    }

    public function requestQuote(QuoteRequest $request): QuoteDraft
    {
        return new QuoteDraft(
            insurer: $this->insurerKey,
            status: 'beklemede',
            origin: 'manuel',
        );
    }
}
