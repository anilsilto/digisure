<?php

namespace App\Domain\Calc;

use InvalidArgumentException;

/**
 * Motorlu Taşıtlar Vergisi — motor hacmi (cc) ve araç yaşı dilimlerine göre yıllık tutar.
 * Oran tablosu: config('digisure.rates.mtv').
 */
class Mtv
{
    use BandMatcher;

    public static function calc(int $cc, int $vehicleAge): int
    {
        foreach (config('digisure.rates.mtv', []) as $ccBand => $ageBands) {
            if (! self::inBand((string) $ccBand, $cc)) {
                continue;
            }

            foreach ($ageBands as $ageBand => $amount) {
                if (self::inBand((string) $ageBand, $vehicleAge)) {
                    return (int) $amount;
                }
            }
        }

        throw new InvalidArgumentException("MTV tarifesinde {$cc}cc / {$vehicleAge} yaş için karşılık bulunamadı.");
    }
}
