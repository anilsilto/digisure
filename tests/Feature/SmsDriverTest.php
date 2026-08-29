<?php

use App\Notifications\Sms\LogSmsSender;
use App\Notifications\Sms\SmsSender;
use Illuminate\Support\Facades\Http;

it('parses a Netgsm success response', function () {
    Http::fake(['*' => Http::response('00 1234567', 200)]);
    config()->set('digisure.sms.driver', 'netgsm');

    $result = app(SmsSender::class)->send('05322652392', 'test');

    expect($result->ok)->toBeTrue()
        ->and($result->providerId)->toBe('1234567');
});

it('flags a Netgsm error response', function () {
    Http::fake(['*' => Http::response('30', 200)]);
    config()->set('digisure.sms.driver', 'netgsm');

    expect(app(SmsSender::class)->send('05322652392', 'test')->ok)->toBeFalse();
});

it('uses the log driver by default', function () {
    expect(app(SmsSender::class))->toBeInstanceOf(LogSmsSender::class);
});
