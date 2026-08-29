<?php

namespace App\Support;

/**
 * Kişisel veri (TC, telefon) için tek yönlü aranabilir hash ve maskeleme.
 *
 * Ham değerler modelde `encrypted` cast ile saklanır; sorgu yapabilmek için
 * yanında APP_KEY türevli HMAC-SHA256 hash kolonu tutulur.
 */
class Pii
{
    /**
     * Rakam dışı karakterleri atıp (telefon/TC formatı fark etmesin) HMAC-SHA256 üretir.
     */
    public static function hash(string $value): string
    {
        $normalized = preg_replace('/\D/', '', $value);

        if ($normalized === '' || $normalized === null) {
            $normalized = trim($value);
        }

        return hash_hmac('sha256', $normalized, self::key());
    }

    /**
     * "05322652392" -> "053******92" (ilk 3 + orta yıldız + son 2).
     */
    public static function mask(string $value): string
    {
        $len = strlen($value);

        if ($len <= 5) {
            return str_repeat('*', $len);
        }

        return substr($value, 0, 3) . str_repeat('*', $len - 5) . substr($value, -2);
    }

    private static function key(): string
    {
        $key = (string) config('app.key');

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return $key;
    }
}
