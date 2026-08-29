<?php

use App\Domain\Calc\Fuel;
use App\Domain\Calc\KaskoValue;
use App\Domain\Calc\Mtv;
use App\Domain\Calc\Otv;

it('computes MTV from cc and age bands', function () {
    config()->set('digisure.rates.mtv', ['1301-1600' => ['1-3' => 9200, '4-6' => 6900]]);

    expect(Mtv::calc(1498, 2))->toBe(9200)
        ->and(Mtv::calc(1498, 5))->toBe(6900);
});

it('throws when no MTV band matches', function () {
    config()->set('digisure.rates.mtv', ['1301-1600' => ['1-3' => 9200]]);

    expect(fn () => Mtv::calc(3000, 20))->toThrow(InvalidArgumentException::class);
});

it('computes fuel cost with defaults', function () {
    config()->set('digisure.rates.fuel', ['default_consumption' => 8.0, 'price_per_litre' => 45.0]);

    $r = Fuel::calc(100, null, null);

    expect($r['litres'])->toBe(8.0)->and($r['cost'])->toBe(360.0);
});

it('computes OTV by first matching band', function () {
    config()->set('digisure.rates.otv', [
        ['cc_max' => 1600, 'max_price' => 184000, 'rate' => 45.0],
        ['cc_max' => 1600, 'max_price' => null, 'rate' => 50.0],
    ]);

    expect(Otv::calc(1500, 200000)['rate'])->toBe(50.0);
});

it('estimates a kasko value range around the reference', function () {
    $r = KaskoValue::estimate(1_000_000, 4.0);

    expect($r['low'])->toBe(36000.0)->and($r['high'])->toBe(44000.0);
});
