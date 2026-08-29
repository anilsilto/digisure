<?php

namespace App\Domain\Calc;

use InvalidArgumentException;

/**
 * Özel Tüketim Vergisi — ilk eşleşen (cc_max, max_price) dilimindeki oran.
 * Dilimler: config('digisure.rates.otv') = [ ['cc_max'=>?int, 'max_price'=>?float, 'rate'=>float], ... ]
 */
class Otv
{
    /**
     * @return array{rate: float, otv: float, total: float}
     */
    public static function calc(int $cc, float $basePrice): array
    {
        foreach (config('digisure.rates.otv', []) as $band) {
            $ccOk = $band['cc_max'] === null || $cc <= $band['cc_max'];
            $priceOk = $band['max_price'] === null || $basePrice <= $band['max_price'];

            if ($ccOk && $priceOk) {
                $rate = (float) $band['rate'];
                $otv = round($basePrice * $rate / 100, 2);

                return ['rate' => $rate, 'otv' => $otv, 'total' => round($basePrice + $otv, 2)];
            }
        }

        throw new InvalidArgumentException("ÖTV tarifesinde {$cc}cc / {$basePrice} TL için dilim bulunamadı.");
    }
}
