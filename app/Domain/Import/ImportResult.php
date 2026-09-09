<?php

namespace App\Domain\Import;

use App\Models\Customer;

/**
 * Tek bir içe aktarma satırının sonucu.
 */
final class ImportResult
{
    private function __construct(
        public string $status,      // created | updated | skipped
        public ?Customer $customer = null,
        public ?string $reason = null,   // skipped ise sebep
        public ?string $note = null,     // poliçe kısmen atlandıysa açıklama
    ) {}

    public static function created(Customer $customer, ?string $note = null): self
    {
        return new self('created', $customer, null, $note);
    }

    public static function updated(Customer $customer, ?string $note = null): self
    {
        return new self('updated', $customer, null, $note);
    }

    public static function skip(string $reason): self
    {
        return new self('skipped', null, $reason);
    }

    public function isSkipped(): bool
    {
        return $this->status === 'skipped';
    }
}
