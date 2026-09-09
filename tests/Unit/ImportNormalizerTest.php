<?php

use App\Domain\Import\ImportNormalizer;

it('normalises Turkish mobile numbers to 0 + 10 digits', function (string $raw, ?string $expected) {
    expect(ImportNormalizer::phone($raw))->toBe($expected);
})->with([
    ['0 (532) 265 23 92', '05322652392'],
    ['+90 532 265 23 92', '05322652392'],
    ['905322652392', '05322652392'],
    ['5322652392', '05322652392'],
    ['0532-265-2392', '05322652392'],
    ['00905322652392', '05322652392'],
    ['212 555 44 33', null],   // sabit hat, cep değil
    ['', null],
    ['abc', null],
    ['0532 265 23', null],     // eksik hane
]);

it('accepts an 11-digit TC and rejects malformed ones', function (string $raw, ?string $expected) {
    expect(ImportNormalizer::tcNo($raw))->toBe($expected);
})->with([
    ['12345678901', '12345678901'],
    [' 123 456 789 01 ', '12345678901'],
    ['01234567890', null],       // sıfırla başlıyor
    ['1234567890', null],        // 10 hane
    ['11111111111', null],       // hepsi aynı
    ['', null],
]);

it('parses common date formats to Y-m-d', function (string $raw, ?string $expected) {
    expect(ImportNormalizer::date($raw))->toBe($expected);
})->with([
    ['12.05.1980', '1980-05-12'],
    ['1980-05-12', '1980-05-12'],
    ['12/05/1980', '1980-05-12'],
    ['boş değil ama saçma', null],
    ['', null],
]);

it('title-cases names and splits full names', function () {
    expect(ImportNormalizer::name('AYŞE   yılmaz'))->toBe('Ayşe Yılmaz');
    expect(ImportNormalizer::splitFullName('ahmet can  ÖZTÜRK'))->toBe(['Ahmet Can', 'Öztürk']);
    expect(ImportNormalizer::splitFullName('madonna'))->toBe(['Madonna', null]);
});

it('validates email or returns null', function () {
    expect(ImportNormalizer::email(' Ali@Example.COM '))->toBe('ali@example.com');
    expect(ImportNormalizer::email('değil-mail'))->toBeNull();
});
