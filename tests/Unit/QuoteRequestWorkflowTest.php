<?php

use App\Domain\Quote\QuoteRequestWorkflow;

it('rejects illegal transitions', function () {
    expect(QuoteRequestWorkflow::canTransition('police', 'yeni'))->toBeFalse();
    expect(QuoteRequestWorkflow::canTransition('yeni', 'teklifler_hazir'))->toBeTrue();
});

it('allows accept from yeni or teklifler_hazir', function () {
    expect(QuoteRequestWorkflow::canTransition('yeni', 'kabul'))->toBeTrue();
    expect(QuoteRequestWorkflow::canTransition('teklifler_hazir', 'kabul'))->toBeTrue();
    expect(QuoteRequestWorkflow::canTransition('iptal', 'kabul'))->toBeFalse();
});
