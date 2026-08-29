<?php

use App\Domain\Insurer\QuoteProviderManager;

it('returns a manual provider per enabled insurer', function () {
    expect(collect(app(QuoteProviderManager::class)->providers())->map->key()->all())
        ->toBe(['sompo', 'quick', 'hepiyi', 'doga']);
});
