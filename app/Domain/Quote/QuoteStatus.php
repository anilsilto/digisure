<?php

namespace App\Domain\Quote;

enum QuoteStatus: string
{
    case Yeni = 'yeni';
    case TekliflerHazir = 'teklifler_hazir';
    case Kabul = 'kabul';
    case Police = 'police';
    case Iptal = 'iptal';

    public function label(): string
    {
        return match ($this) {
            self::Yeni => 'Yeni',
            self::TekliflerHazir => 'Teklifler Hazır',
            self::Kabul => 'Kabul Edildi',
            self::Police => 'Poliçeleşti',
            self::Iptal => 'İptal',
        };
    }

    /** @return array<string, string> value => label */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $s) => [$s->value => $s->label()])->all();
    }
}
