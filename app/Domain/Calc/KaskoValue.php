<?php

namespace App\Domain\Calc;

/**
 * Kasko değer / prim tahmini.
 *
 * TSB Kasko Değer Listesi canlı entegre edilmez; kullanıcı marka/model/yıl için
 * referans değeri elle girer, kasko oranıyla ±%10 bir prim aralığı tahmin edilir.
 */
class KaskoValue
{
    /**
     * @return array{low: float, high: float, mid: float}
     */
    public static function estimate(float $referenceValue, float $ratePct): array
    {
        $mid = round($referenceValue * $ratePct / 100, 2);

        return [
            'low' => round($mid * 0.9, 2),
            'high' => round($mid * 1.1, 2),
            'mid' => $mid,
        ];
    }
}
