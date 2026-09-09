<?php

namespace App\Domain\Import;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Eski panelden gelen ham hücre değerlerini sisteme uygun forma çevirir.
 * Telefon formatı kritik: giriş sırasında Pii::hash yalnızca rakamları alır ve
 * kanonik forma çevirmez; bu yüzden müşterinin girişte yazacağı biçimde
 * (0 + 10 hane) saklıyoruz.
 */
class ImportNormalizer
{
    /**
     * "0 (532) 265 23 92", "+90 532 ...", "532 265 2392" -> "05322652392".
     * Türk cep numarası kalıbına uymuyorsa null döner.
     */
    public static function phone(?string $raw): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $raw);

        if ($digits === '') {
            return null;
        }

        // Ülke kodu (90) varyasyonlarını at.
        if (Str::startsWith($digits, '90') && strlen($digits) === 12) {
            $digits = substr($digits, 2);
        } elseif (Str::startsWith($digits, '0090') && strlen($digits) === 14) {
            $digits = substr($digits, 4);
        }

        // 11 hane, "0" ile başlıyorsa baştaki sıfırı at.
        if (strlen($digits) === 11 && $digits[0] === '0') {
            $digits = substr($digits, 1);
        }

        // Geçerli cep: 10 hane ve "5" ile başlar.
        if (strlen($digits) === 10 && $digits[0] === '5') {
            return '0'.$digits;
        }

        return null;
    }

    /**
     * TC Kimlik: 11 hane, sıfırla başlamaz, hepsi aynı değil. Geçersizse null.
     * (Tam mod-10 çek yapılmıyor — eski kayıtlarda nadir bozukluğu elemek için
     *  fazla katı olmamak adına.)
     */
    public static function tcNo(?string $raw): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $raw);

        if (strlen($digits) !== 11 || $digits[0] === '0') {
            return null;
        }

        if (preg_match('/^(\d)\1{10}$/', $digits)) {
            return null;
        }

        return $digits;
    }

    /**
     * "12.05.1980", "1980-05-12", "12/05/1980" -> Y-m-d. Çözülemezse null.
     */
    public static function date(?string $raw): ?string
    {
        $value = trim((string) $raw);

        if ($value === '') {
            return null;
        }

        $ceiling = (int) date('Y') + 10; // poliçe bitiş tarihleri gelecekte olabilir

        foreach (['d.m.Y', 'd/m/Y', 'Y-m-d', 'd-m-Y', 'd.m.y', 'Y/m/d'] as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $value);
                if ($parsed && $parsed->year >= 1900 && $parsed->year <= $ceiling) {
                    return $parsed->format('Y-m-d');
                }
            } catch (\Throwable) {
                // sıradaki formatı dene
            }
        }

        return null;
    }

    /**
     * "AYŞE   yılmaz" -> "Ayşe Yılmaz". Boşsa null.
     */
    public static function name(?string $raw): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $raw));

        if ($value === '') {
            return null;
        }

        return Str::of($value)->lower()->title()->value();
    }

    /**
     * "Ayşe Nur YILMAZ" -> ["Ayşe Nur", "Yılmaz"] (son kelime soyad).
     *
     * @return array{0:?string,1:?string}
     */
    public static function splitFullName(?string $raw): array
    {
        $value = self::name($raw);

        if ($value === null || ! str_contains($value, ' ')) {
            return [$value, null];
        }

        $parts = explode(' ', $value);
        $last = array_pop($parts);

        return [implode(' ', $parts), $last];
    }

    public static function email(?string $raw): ?string
    {
        $value = strtolower(trim((string) $raw));

        return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;
    }
}
