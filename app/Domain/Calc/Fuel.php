<?php

namespace App\Domain\Calc;

/**
 * Yakıt maliyeti: mesafe (km) × tüketim (L/100km) × birim fiyat.
 * Varsayılanlar: config('digisure.rates.fuel').
 */
class Fuel
{
    /**
     * @return array{litres: float, cost: float}
     */
    public static function calc(float $distanceKm, ?float $consumption = null, ?float $pricePerLitre = null): array
    {
        $consumption ??= (float) config('digisure.rates.fuel.default_consumption');
        $pricePerLitre ??= (float) config('digisure.rates.fuel.price_per_litre');

        $litres = round($distanceKm / 100 * $consumption, 2);

        return ['litres' => $litres, 'cost' => round($litres * $pricePerLitre, 2)];
    }
}
