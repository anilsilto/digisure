<?php

use App\Models\Customer;
use App\Models\Policy;
use Database\Seeders\ProductTypeSeeder;

beforeEach(fn () => $this->seed(ProductTypeSeeder::class));

it('creates a policy from an accepted quote and advances the request', function () {
    $r = makeAcceptedQuoteRequest('kasko', premium: 12000, insurer: 'quick');

    actingPanel()->post("/panel/teklifler/{$r->id}/policelestir", [
        'policy_no' => 'QK-2026-1',
        'start_date' => '2026-09-01',
        'end_date' => '2027-09-01',
    ])->assertRedirect();

    $p = Policy::first();
    expect($p->insurer)->toBe('quick')
        ->and((float) $p->premium)->toBe(12000.0)
        ->and($p->end_date->toDateString())->toBe('2027-09-01')
        ->and($r->fresh()->status)->toBe('police');
});

it('rejects policyfication unless request is kabul', function () {
    $r = makeQuoteRequest('trafik');

    actingPanel()->post("/panel/teklifler/{$r->id}/policelestir", [
        'policy_no' => 'x', 'start_date' => '2026-09-01', 'end_date' => '2027-09-01',
    ])->assertStatus(422);
});

it('lets staff add an off-site policy', function () {
    actingPanel()->post('/panel/policeler', [
        'urun' => 'trafik', 'insurer' => 'doga', 'policy_no' => 'DG-1', 'premium' => '3400',
        'start_date' => '2026-08-01', 'end_date' => '2027-08-01',
        'ad' => 'Veli', 'soyad' => 'Ak', 'tc_no' => '98765432109', 'telefon' => '05001112233',
    ])->assertRedirect();

    expect(Policy::where('policy_no', 'DG-1')->exists())->toBeTrue();
    expect(Customer::whereTc('98765432109')->exists())->toBeTrue();
});
