<?php

namespace App\Domain\Calc;

trait BandMatcher
{
    /**
     * "1-3", "1301-1600", "16+", "2501+" gibi aralık anahtarına değer düşüyor mu?
     */
    protected static function inBand(string $band, int|float $value): bool
    {
        if (str_ends_with($band, '+')) {
            return $value >= (float) rtrim($band, '+');
        }

        [$min, $max] = array_pad(explode('-', $band, 2), 2, null);

        return $value >= (float) $min && $value <= (float) $max;
    }
}
