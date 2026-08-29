<?php

use App\Domain\Auth\OtpService;
use App\Models\Customer;
use App\Models\OtpCode;
use App\Support\Pii;
use Illuminate\Validation\ValidationException;

it('sends an OTP for a known tc+phone pair', function () {
    Customer::create(['first_name' => 'A', 'last_name' => 'B', 'tc_no' => '12345678901', 'phone' => '05322652392']);

    app(OtpService::class)->start('12345678901', '0532 265 23 92');

    expect(OtpCode::where('phone_hash', Pii::hash('05322652392'))->count())->toBe(1);
});

it('logs in after correct code and rejects wrong code', function () {
    $c = Customer::create(['first_name' => 'A', 'last_name' => 'B', 'tc_no' => '12345678901', 'phone' => '05322652392']);
    $svc = app(OtpService::class);

    $svc->start('12345678901', '05322652392');
    $code = cache()->pull('__test_last_otp');

    expect(fn () => $svc->verify('12345678901', '05322652392', '000000'))->toThrow(ValidationException::class);
    expect($svc->verify('12345678901', '05322652392', $code)->id)->toBe($c->id);
});

it('rate-limits to 3 sends per hour', function () {
    Customer::create(['first_name' => 'A', 'last_name' => 'B', 'tc_no' => '12345678901', 'phone' => '05322652392']);
    $svc = app(OtpService::class);

    foreach (range(1, 3) as $i) {
        $svc->start('12345678901', '05322652392');
    }

    expect(fn () => $svc->start('12345678901', '05322652392'))->toThrow(ValidationException::class);
});

it('rejects an unknown tc+phone pair', function () {
    Customer::create(['first_name' => 'A', 'last_name' => 'B', 'tc_no' => '12345678901', 'phone' => '05322652392']);

    expect(fn () => app(OtpService::class)->start('99999999999', '05322652392'))
        ->toThrow(ValidationException::class);
});
