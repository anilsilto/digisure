<?php

use App\Support\Pii;

it('hashes deterministically and ignores formatting', function () {
    expect(Pii::hash('0532 265 23 92'))->toBe(Pii::hash('05322652392'))
        ->and(Pii::hash('05322652392'))->toHaveLength(64);
});

it('masks middle digits', fn () => expect(Pii::mask('05322652392'))->toBe('053******92'));
