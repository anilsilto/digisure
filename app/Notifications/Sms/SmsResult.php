<?php

namespace App\Notifications\Sms;

final class SmsResult
{
    public function __construct(
        public readonly bool $ok,
        public readonly ?string $providerId = null,
        public readonly ?string $raw = null,
    ) {
    }
}
