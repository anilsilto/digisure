<?php

namespace App\Domain\Insurer;

/**
 * Bir sigorta şirketinden gelen (veya elle girilecek) teklif taslağı.
 * API entegrasyonu geldiğinde gerçek provider'lar bu DTO'yu dolu döndürür.
 */
final class QuoteDraft
{
    /**
     * @param  array<int, string>|null  $coverageSummary
     */
    public function __construct(
        public readonly string $insurer,
        public readonly string $status = 'beklemede',
        public readonly ?float $premium = null,
        public readonly ?array $coverageSummary = null,
        public readonly ?int $policyPeriodMonths = null,
        public readonly string $origin = 'manuel',
    ) {}
}
