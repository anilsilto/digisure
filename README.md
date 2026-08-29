# DigiSure

Zafir Sigorta için dijital sigorta acentesi platformu — müşteriler trafik / kasko / sağlık
sigortasında online teklif talebi oluşturur, acente personeli 4 anlaşmalı şirketin (Sompo,
Quick, HEPİYİ, Doğa) tekliflerini girer, müşteri karşılaştırıp seçer; poliçe ve yenileme
takibi + SMS/e-posta hatırlatma.

## Yığın

Laravel 12 · PHP 8.3 · MySQL/MariaDB · Blade + Tailwind v4 · Pest · database queue + scheduler

## Yerel geliştirme (Laragon)

```
composer install
cp .env.example .env && php artisan key:generate
# .env: DB_DATABASE=digisure (Laragon MySQL'i başlat)
php artisan migrate --seed          # local'de DemoSeeder de çalışır
npm install && npm run build        # veya: npm run dev
php artisan serve
```

Demo giriş: `admin@digisure.test` / `parola123` (panel), müşteri girişi TC + telefon + SMS OTP
(SMS sürücüsü `log` iken kod `storage/logs/laravel.log`'a yazılır).

## Test

```
./vendor/bin/pest
```

## Mimari

| Alan | Yol | Kimlik |
|---|---|---|
| Genel site | `/`, `/teklif`, `/hesaplama` | — |
| Müşteri portalı | `/hesabim/*` | `customer` guard, SMS OTP |
| Acente paneli | `/panel/*` | `panel` guard, e-posta + şifre + rol |

- **Teklif motoru:** `app/Domain/Insurer/QuoteProvider` arayüzü + `QuoteProviderManager`.
  v1'de tüm şirketler `ManualProvider` (beklemede kotasyon → personel doldurur). API gelince
  sınıf değişir, akış aynı kalır. Bkz. `docs/DEPLOY.md`.
- **Durum makinesi:** `app/Domain/Quote/QuoteRequestWorkflow`
  (`yeni → teklifler_hazir → kabul → police`, her durumdan `iptal`).
- **Hatırlatmalar:** `RenewalReminderService` + `digisure:*` komutları + scheduler.
- **Bildirim:** `App\Notifications\Sms\SmsSender` (Netgsm / Log) + kuyruğa alınan
  `SendSmsJob` / mail.
- **KVKK:** TC/telefon `encrypted` + HMAC hash (`App\Support\Pii`), `activity_logs`,
  müşteri veri talebi akışı, `digisure:anonymize-old-requests`.

Tasarım ve plan: `docs/superpowers/specs/` ve `docs/superpowers/plans/`.
