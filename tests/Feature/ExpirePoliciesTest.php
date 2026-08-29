<?php

use App\Models\Policy;
use App\Models\QuoteRequestField;

it('expires policies past end_date', function () {
    Policy::factory()->create(['status' => 'aktif', 'end_date' => today()->subDay()]);
    Policy::factory()->create(['status' => 'aktif', 'end_date' => today()->addDay()]);

    $this->artisan('digisure:expire-policies')->assertSuccessful();

    expect(Policy::where('status', 'suresi_doldu')->count())->toBe(1);
    expect(Policy::where('status', 'aktif')->count())->toBe(1);
});

it('anonymizes old quote requests idempotently', function () {
    $r = makeQuoteRequest('trafik');
    $r->fields()->create(['field_key' => 'plaka', 'value' => '06ABC123']);
    $r->forceFill(['created_at' => now()->subYears(3)])->save();

    $this->artisan('digisure:anonymize-old-requests')->assertSuccessful();
    $this->artisan('digisure:anonymize-old-requests')->assertSuccessful();

    expect(QuoteRequestField::where('quote_request_id', $r->id)->whereNotNull('value')->count())->toBe(0);
});
