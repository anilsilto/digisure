<?php

use App\Models\NotificationLog;
use App\Models\User;

it('saves a manual insurer offer and marks it verildi', function () {
    $r = makeQuoteRequest('kasko');
    $q = $r->quotes()->where('insurer', 'sompo')->first();

    actingPanel($u = User::factory()->personel()->create())
        ->put("/panel/kotasyon/{$q->id}", [
            'premium' => '12500.50',
            'policy_period_months' => 12,
            'coverage_summary' => "IMM 1.000.000\nCam kırılması dahil",
            'insurer_quote_no' => 'SMP-1',
            'valid_until' => now()->addDays(7)->toDateString(),
        ])
        ->assertRedirect();

    $q->refresh();
    expect($q->status)->toBe('verildi')
        ->and((float) $q->premium)->toBe(12500.50)
        ->and($q->coverage_summary)->toBe(['IMM 1.000.000', 'Cam kırılması dahil'])
        ->and($q->entered_by)->toBe($u->id);
});

it('marks quotes ready only from yeni and notifies the customer', function () {
    $r = makeQuoteRequest('trafik');
    $r->quotes()->where('insurer', 'sompo')->first()->update(['status' => 'verildi', 'premium' => 1000]);

    actingPanel()->post("/panel/teklifler/{$r->id}/hazir")->assertRedirect();

    expect($r->fresh()->status)->toBe('teklifler_hazir');
    expect(NotificationLog::where('template', 'teklifler_hazir')->count())->toBe(1);
});

it('refuses hazir when no offer entered', function () {
    $r = makeQuoteRequest('trafik');

    actingPanel()->post("/panel/teklifler/{$r->id}/hazir")->assertSessionHasErrors();

    expect($r->fresh()->status)->toBe('yeni');
});
