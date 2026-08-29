<?php

use App\Models\ActivityLog;
use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('records an activity log when a policy is created', function () {
    $r = makeAcceptedQuoteRequest('trafik');

    actingPanel()->post("/panel/teklifler/{$r->id}/policelestir", [
        'policy_no' => 'X-1', 'start_date' => '2026-09-01', 'end_date' => '2027-09-01',
    ]);

    expect(ActivityLog::where('action', 'police.olusturuldu')->count())->toBe(1);
});

it('records an activity log when a manual offer is entered', function () {
    $r = makeQuoteRequest('kasko');
    $q = $r->quotes()->where('insurer', 'sompo')->first();

    actingPanel()->put("/panel/kotasyon/{$q->id}", ['premium' => '1000', 'policy_period_months' => 12]);

    expect(ActivityLog::where('action', 'kotasyon.girildi')->count())->toBe(1);
});
