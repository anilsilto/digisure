# DigiSure — cPanel Deploy Runbook

## Gereksinimler

- PHP **8.3+** (cPanel → MultiPHP Manager)
- Composer (cPanel Terminal veya SSH)
- MySQL/MariaDB veritabanı + kullanıcı
- Cron erişimi

## İlk kurulum

1. Kodu `~/digisure` gibi bir dizine klonla/yükle. **Document root**'u `~/digisure/public` yap
   (cPanel → Domains → doküman kökü) — proje kökünü web'e açma.
2. Bağımlılıklar:
   ```
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build        # yerelde build alıp public/build'i de yükleyebilirsin
   ```
3. `.env`:
   ```
   cp .env.example .env
   php artisan key:generate
   ```
   Doldurulacaklar:
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://alanadi`
   - `DB_DATABASE / DB_USERNAME / DB_PASSWORD`
   - `QUEUE_CONNECTION=database`, `SESSION_DRIVER=database`, `CACHE_STORE=database`
   - `DIGISURE_SMS_DRIVER=netgsm` + `NETGSM_USERCODE / NETGSM_PASSWORD / NETGSM_HEADER`
   - `MAIL_MAILER=smtp` + cPanel SMTP bilgileri, `MAIL_FROM_ADDRESS`
4. Şema + demo dışı seed:
   ```
   php artisan migrate --force --seed
   php artisan storage:link
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
5. `storage/` ve `bootstrap/cache/` yazılabilir olmalı (genelde 755/775).

## Cron

cPanel → Cron Jobs, dakikada bir:

```
* * * * * cd ~/digisure && php artisan schedule:run >> /dev/null 2>&1
```

Bu tek satır şunları çalıştırır (routes/console.php):
- `digisure:expire-policies` — her gün 02:00
- `digisure:generate-renewal-reminders` — her gün 02:05
- `digisure:send-due-reminders` — her gün 08:00

Kuyruğu işlemek için ayrı bir cron (kuyruk worker daemon paylaşımlı hostta çalışmaz):

```
* * * * * cd ~/digisure && php artisan queue:work --stop-when-empty --max-time=55 >> /dev/null 2>&1
```

## Opsiyonel — KVKK veri saklama

2 yıldan eski teklep taleplerinin kişisel form verilerini anonimleştirir:

```
0 3 * * 0 cd ~/digisure && php artisan digisure:anonymize-old-requests >> /dev/null 2>&1
```

## Güncelleme

```
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan queue:restart
```

## Sigorta şirketi API entegrasyonu (sonraki faz)

`app/Providers/Insurers/` altında `SompoProvider` / `QuickProvider` / `HepiYiProvider` /
`DogaProvider` sınıfları oluştur, `App\Domain\Insurer\QuoteProvider` arayüzünü uygula,
`QuoteProviderManager::providers()` içinde `ManualProvider` yerine bağla. Panel ve portal
akışı değişmeden çalışır.
