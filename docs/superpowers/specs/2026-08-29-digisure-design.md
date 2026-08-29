# DigiSure — Tasarım Dokümanı (Spec)

**Tarih:** 2026-08-29
**Proje:** DigiSure — Zafir Sigorta için dijital sigorta acentesi platformu
**Referans:** dijipol.com'un acente ölçeğinde, entegrasyona hazır bir uyarlaması

---

## 1. Amaç ve Kapsam

Zafir Sigorta'nın müşterilerine online teklif talebi / karşılaştırma / poliçe takibi
sunan, acente personelinin de talepleri ve poliçeleri yönettiği tek bir web
uygulaması. Sigorta şirketi API'leri (Sompo, Quick, HepiYi, Doğa) **henüz yok**;
sistem bu entegrasyonlara hazır "adaptör" mimarisiyle kurulur, o zamana kadar
teklifler acente personeli tarafından elle girilir.

### v1 kapsamı (bu spec)

| Modül | İçerik | Durum |
|---|---|---|
| A | Genel site: anasayfa, 3 ürün sayfası, hakkımızda, iletişim, teklif formu | v1 |
| B | Acente paneli: teklif talebi yönetimi, elle teklif girişi, durum akışı | v1 |
| C | Poliçe & yenileme takibi + otomatik hatırlatma (SMS/e-posta) | v1 |
| D | Müşteri portalı: TC + telefon + SMS OTP girişi, teklif/poliçe görüntüleme | v1 |
| E | Hesaplama araçları: MTV, ÖTV, kasko değer yardımcısı, yakıt maliyeti | v1 |
| F | Online ödeme (sanal POS) + e-imza ile poliçeleştirme | **v2 — kapsam dışı** |

### v1 ürünleri

Trafik, Kasko, Tamamlayıcı/Özel Sağlık. Ürünler ve form alanları veri tabanında
tanımlı (dinamik) — yeni ürün eklemek şema değişikliği gerektirmez.

---

## 2. Teknoloji ve Mimari

- **Laravel 11**, PHP 8.2+
- **MySQL / MariaDB**
- **Blade + Tailwind CSS** (Zafir kurumsal paleti), **Alpine.js** (hafif form etkileşimi)
- **Kuyruk:** `database` driver (Redis yok — paylaşımlı hosting uyumlu)
- **Zamanlayıcı:** Laravel scheduler; sunucuda tek cron: `* * * * * php artisan schedule:run`
- **Local geliştirme:** Laragon (Windows) — PHP 8.2 + MariaDB + Apache
- **Test:** Pest (PHPUnit tabanlı), TDD
- **Deploy:** kullanıcının sorumluluğunda. `public/` document root, `.env` DB + SMS
  anahtarları, `php artisan migrate --seed --force`. Yeni domain, ad **DigiSure**.

### Tek uygulama, üç auth guard

| Alan | URL öreği | Guard | Kimlik |
|---|---|---|---|
| Genel site | `/`, `/trafik-sigortasi`, `/teklif` | — (yok) | — |
| Müşteri portalı | `/hesabim/*` | `customer` | TC + telefon → SMS OTP |
| Acente paneli | `/panel/*` | `panel` (web users) | e-posta + şifre + rol |

### Klasör düzeni

```
app/
  Domain/
    Quote/            QuoteRequestService, teklif durum makinesi
    Policy/           PolicyService, RenewalReminderService
    Insurer/          QuoteProvider arayüzü, QuoteProviderManager, DTO'lar
  Providers/Insurers/
    ManualProvider.php     (v1: tüm şirketler için "beklemede" teklif satırı üretir)
    SompoProvider.php      (stub — API gelince doldurulacak)
    QuickProvider.php      (stub)
    HepiYiProvider.php     (stub)
    DogaProvider.php       (stub)
  Notifications/
    Sms/SmsSender.php (arayüz), NetgsmSmsSender.php, LogSmsSender.php (dev)
  Http/Controllers/{Public,Customer,Panel}/
  Console/Commands/SendRenewalReminders.php
resources/views/{public,customer,panel,mail}/
database/migrations/  seeders/  factories/
config/digisure.php   (SMS sağlayıcı, hatırlatma günleri, hesaplama oran tabloları)
```

---

## 3. Veri Modeli

### Kimlik / kişiler
- **users** — panel personeli. `name`, `email` (tekil), `password`, `role`
  (`admin` | `personel`), `is_active`.
- **customers** — `first_name`, `last_name`, `tc_no` (encrypted cast + `tc_hash`
  tekil aranabilirlik için), `phone` (encrypted + `phone_hash`), `email`,
  `birth_date`, `address`, `kvkk_consent_at`, `marketing_consent_at`.
- **otp_codes** — `phone_hash`, `code_hash`, `expires_at`, `attempts`,
  `consumed_at`, `ip`. TTL 3 dk.

### Teklif akışı
- **product_types** — `key` (`trafik`|`kasko`|`saglik`), `name`, `icon`,
  `field_schema` (JSON: [{name,label,type,required,options?,group?}]), `is_active`,
  `sort`.
- **quote_requests** — `customer_id`, `product_type_id`, `status`
  (`yeni` → `teklifler_hazir` → `kabul` → `police` → `iptal`), `source`
  (`site` | `panel`), `assigned_user_id`, `reference_no` (müşteriye verilen kod),
  `note`.
- **quote_request_fields** — `quote_request_id`, `field_key`, `value` (string).
  Ürün formunun dinamik cevapları.
- **quotes** — `quote_request_id`, `insurer` (`sompo`|`quick`|`hepiyi`|`doga`),
  `status` (`beklemede` | `verildi` | `reddedildi`), `premium` (decimal),
  `coverage_summary` (JSON), `policy_period_months`, `insurer_quote_no`,
  `valid_until`, `origin` (`api` | `manuel`), `file_path` (PDF), `entered_by`.
- **accepted_quote_id** `quote_requests` üzerinde nullable FK.

### Poliçe / yenileme
- **policies** — `customer_id`, `product_type_id`, `insurer`, `policy_no`,
  `start_date`, `end_date`, `premium`, `quote_id` (nullable kaynak teklif),
  `file_path`, `status` (`aktif` | `yenilendi` | `iptal` | `suresi_doldu`),
  `renewed_from_policy_id` (nullable).
- **renewal_reminders** — `policy_id`, `remind_on` (end_date − N gün),
  `channel` (`sms` | `eposta`), `status` (`bekliyor` | `gonderildi` | `hata`),
  `sent_at`. Hatırlatma günleri config: `[30, 15, 7]`.

### Log / sistem
- **notification_logs** — `notifiable_type/id`, `channel`, `template`, `payload`,
  `status`, `provider_response`, `sent_at`.
- **activity_logs** — `user_id` (nullable), `subject_type/id`, `action`,
  `changes` (JSON), `ip`. Panelde her değişiklik + KVKK denetimi için.

### Kilit kararlar
1. TC ve telefon **uygulama katmanında şifreli** saklanır (`encrypted` cast);
   arama için ayrı `*_hash` (HMAC-SHA256, `APP_KEY` türevli) kolonu.
2. Dinamik form alanları ayrı tabloda → panelden ürün/alan eklenebilir.
3. `coverage_summary`, `field_schema`, `changes` → JSON kolon.
4. Para alanları `decimal(12,2)`, TL.

---

## 4. Teklif Motoru ve Sigorta Şirketi Adaptörleri

### QuoteProvider arayüzü

```php
interface QuoteProvider {
    public function key(): string;              // 'sompo' ...
    public function supports(ProductType $p): bool;
    public function requestQuote(QuoteRequest $r): QuoteOutcome;
    // QuoteOutcome = Quote verisi | PendingManual (elle girilecek)
}
```

- **QuoteProviderManager** — `config/digisure.php`'deki `enabled_insurers`
  listesinden aktif sağlayıcıları çözer.
- **v1 davranışı:** Tüm 4 şirket `ManualProvider` ile bağlıdır. Talep
  oluşturulduğunda her aktif şirket için `quotes` tablosuna `status=beklemede`,
  `origin=manuel` satır açılır. Panel "4 şirketten teklif bekleniyor" gösterir,
  personel prim + teminatı doldurunca `status=verildi` olur.
- **API geldiğinde:** İlgili `SompoProvider` vb. gerçek implementasyonla
  doldurulur, `config` bağlaması değişir; panel/portal akışı aynı kalır.

### Teklif durum akışı (quote_requests.status)

```
yeni ──(en az bir quote verildi)──> teklifler_hazir
teklifler_hazir ──(müşteri/panel bir teklif seçti)──> kabul
kabul ──(poliçe oluşturuldu)──> police
her durumdan ──> iptal
```

Geçişler `QuoteRequestService` içinde; her geçiş `activity_logs` + gerekiyorsa
müşteriye bildirim (teklifler_hazır → SMS "teklifleriniz hazır").

---

## 5. Modül Detayları

### A — Genel site
- Anasayfa: hero (slogan), 3 ürün kartı, "nasıl çalışır" 3 adım, güven bandı
  (SEDDK broker, iletişim), SSS.
- Ürün sayfaları: `/trafik-sigortasi`, `/kasko-sigortasi`, `/saglik-sigortasi` —
  içerik + "Teklif Al" CTA.
- `/hakkimizda`, `/iletisim` (form → `notification_logs` + e-posta info@).
- **Teklif formu** `/teklif?urun=trafik`:
  1. Ürün seç → dinamik alanlar (`field_schema`'dan render)
  2. Kişisel bilgiler (ad, TC, telefon, e-posta, doğum tarihi)
  3. **KVKK aydınlatma onayı (zorunlu checkbox)** + açık rıza (pazarlama, opsiyonel)
  4. Gönder → `customer` upsert (tc_hash ile), `quote_request` (`source=site`,
     `reference_no` üretilir), `QuoteProviderManager` beklemede teklifleri açar
  5. Ekranda: "Talebiniz alındı, referans no: XXX. Tekliflər hazır olunca SMS
     göndereceğiz." + portala giriş linki
- Spam koruması: honeypot alan + `throttle` (IP başına saatte N talep).

### B — Acente paneli (`/panel`)
- **Giriş:** e-posta + şifre (Laravel Breeze temelli, sade). Rol: `admin` her şey,
  `personel` kendi atanan talepleri + tümünü görür.
- **Dashboard:** yeni talepler, teklif bekleyenler, bugün/bu hafta biten poliçeler,
  yaklaşan yenilemeler.
- **Teklif talepleri listesi:** filtre (durum, ürün, tarih, personel), ata, aç.
- **Talep detayı:** müşteri bilgisi, dinamik form cevapları, 4 şirket satırı;
  her satıra prim + teminat özeti + poliçe süresi + teklif no + PDF yükle.
  "Tekliflər hazır" → müşteriye SMS. Bir teklifi "kabul edildi" işaretle →
  `kabul` durumuna geç.
- **Poliçeye çevir:** kabul edilen tekliften `policy` oluştur (poliçe no,
  tarih aralığı, PDF). → `renewal_reminders` otomatik üretilir.
- **Poliçeler:** liste + filtre, yaklaşan yenilemeler, elle poliçe ekleme
  (site dışı satışlar için).
- **Müşteriler:** liste, detay (poliçe geçmişi, teklip geçmişi, notlar).
- **Ürün yönetimi (admin):** `product_types` + `field_schema` düzenleme (JSON
  editör / basit alan ekleyici).
- **Ayarlar (admin):** SMS sağlayıcı anahtarı, hatırlatma günleri, kullanıcılar.
- Her yazma işlemi `activity_logs`.

### C — Poliçe & yenileme
- `policies` oluşunca `remind_on = end_date - {30,15,7}` için `renewal_reminders`.
- **`php artisan digisure:send-renewal-reminders`** (scheduler'da günlük):
  bugüne ait `bekliyor` hatırlatmaları → kuyruğa SMS + e-posta job, `notification_logs`.
- Yenileme SMS'i portal linki + referans içerir; müşteri "yenile" derse yeni
  `quote_request` (`source=site`, ilgili üründe) açılır.
- Süresi geçen poliçeler günlük komutla `suresi_doldu`.

### D — Müşteri portalı (`/hesabim`)
- **Giriş:** TC + telefon gir → eşleşen `customer` varsa telefona OTP → kod →
  `customer` guard session. Kayıt yok; ilk teklif talebinde müşteri zaten oluşur.
- Rate limit: OTP gönderimi telefon başına saatte 3, kod denemesi 5.
- **Ekranlar:**
  - Tekliflerim: talep listesi + durum. Detayda **karşılaştırma tablosu**
    (şirket / prim / teminat / süre yan yana), "Bu teklifi seç" → `kabul`.
  - Poliçelerim: aktif/geçmiş, PDF indir, bitiş tarihi, "yenile".
  - Profilim: iletişim bilgisi güncelle, KVKK/pazarlama tercihleri, veri talebi.

### E — Hesaplama araçları (`/hesaplama/*`)
Statik, giriş gerektirmez. Oran tabloları `config/digisure.php` (yıl bazlı,
kullanıcı güncelleyebilir):
- **MTV:** araç yaşı × motor hacmi / değer dilimi → 2026 tarifesi.
- **ÖTV:** motor hacmi + matrah dilimi → oran → tutar.
- **Kasko değer yardımcısı:** TSB kasko değer listesi canlı çekilemez; marka/model/yıl
  için elle girilen referans değer + kasko oranı ile tahmini prim aralığı; not düşülür.
- **Yakıt maliyeti:** mesafe × tüketim (L/100km) × yakıt fiyatı.

---

## 6. Bildirim Altyapısı

- **SmsSender** arayüzü: `send(string $phone, string $message): SmsResult`.
  - `NetgsmSmsSender` (HTTP API, `.env` kullanıcı/şifre/başlık).
  - `LogSmsSender` — local/dev, log'a yazar.
  - `config('digisure.sms.driver')` ile seçilir.
- E-posta: Laravel Mail + cPanel SMTP, Blade mail şablonları.
- Tüm gönderimler **kuyruğa** alınır (`ShouldQueue`), `notification_logs`'a işlenir,
  hata → yeniden deneme (3x, backoff).
- Şablonlar: SMS düz metin + yer tutucu (`config`/DB), e-posta Blade.
- Kanallar: OTP (SMS), "tekliflər hazır" (SMS), yenileme hatırlatma (SMS+e-posta),
  iletişim formu (e-posta → acente).

---

## 7. Kimlik Doğrulama Detayı

- **panel guard:** `users` tablosu, e-posta + `bcrypt` şifre. Breeze (Blade)
  iskeleti sadeleştirilmiş. `role` + Laravel Gate/Policy ile yetki. Şifre
  sıfırlama e-posta ile.
- **customer guard:** özel guard, parola yok. Akış: TC+telefon → `tc_hash` &
  `phone_hash` eşleşmesi → OTP üret (`otp_codes`) → SMS → doğrula → login.
  Oturum 30 gün "beni hatırla" opsiyonlu. `throttle` ile brute-force koruması.
- **Public:** auth yok; teklif formu CSRF + honeypot + IP throttle.
- Ortak: HTTPS (deploy), Laravel CSRF, `SameSite=Lax` cookie, security header
  middleware.

---

## 8. Güvenlik ve KVKK

- TC / telefon şifreli saklanır; loglarda maskeli (`123******89`).
- Her teklif formunda KVKK aydınlatma onayı zorunlu, `kvkk_consent_at` damgalanır;
  açık rıza (pazarlama) ayrı ve opsiyonel.
- `activity_logs` — kim hangi kişisel veriye erişti/değiştirdi.
- Müşteri portalında "verilerimi indir" / "silme talebi" → panelde talep kaydı.
- Veri saklama notu: teklif talepleri 2 yıl sonra anonimleştirme komutu (v1'de
  komut hazır, cron opsiyonel).
- Eloquent (prepared statements), Blade auto-escape (XSS), `.env` sırlar,
  dosya yüklemede tip/boyut doğrulama, PDF'ler public olmayan diskte + imzalı URL.
- Rate limiting: OTP, teklif formu, iletişim formu, panel login.

---

## 9. Test Yaklaşımı (TDD, Pest)

Feature testleri:
- Her ürün için teklif talebi oluşturma (dinamik alan doğrulama, KVKK zorunlu,
  customer upsert, beklemede quote satırları).
- OTP akışı: gönderim rate limit, yanlış kod, süresi dolmuş kod, başarı.
- Panel: teklif girişi → `teklifler_hazir` → müşteriye SMS job kuyruğa girdi mi;
  yetki (personel vs admin).
- Karşılaştırma ekranı: müşteri sadece kendi taleplerini görür.
- Poliçeye çevirme → `renewal_reminders` doğru tarihlerle üretildi mi.
- `digisure:send-renewal-reminders` → doğru gün, kuyruk, idempotent (iki kez
  çalışınca tekrar göndermez).
- Hesaplama araçları: bilinen girdi → beklenen çıktı (oran tablosu sabit).

Unit: `tc_hash`/`phone_hash` türetme, teklif durum makinesi geçiş kuralları,
MTV/ÖTV hesap fonksiyonları.

---

## 10. Yapım Sırası (implementasyon planına girdi)

1. Laravel iskeleti + Laragon ortamı + Tailwind + Zafir teması + ortak layout/nav
   + Pest kurulumu.
2. Migration'lar + modeller + factory + seeder (`product_types`: trafik/kasko/saglik
   `field_schema` dahil; demo `users`).
3. Genel site sayfaları (anasayfa, 3 ürün, hakkımızda, iletişim).
4. Dinamik teklif formu → `quote_request` + `customer` upsert + `ManualProvider`
   beklemede teklifler + referans ekranı.
5. Panel auth (Breeze sade) + dashboard + teklif talebi listesi/detayı + elle
   teklif girişi + durum akışı.
6. Müşteri OTP guard + portal (tekliflerim, karşılaştırma, teklif seç, poliçelerim,
   profil).
7. Poliçe oluşturma (kabul edilen tekliften) + `renewal_reminders` üretimi +
   panel poliçe ekranları + elle poliçe ekleme.
8. Bildirimler: `SmsSender`/Netgsm + `LogSmsSender` + mail + kuyruk +
   `digisure:send-renewal-reminders` + scheduler + süresi geçen poliçe komutu.
9. Hesaplama araçları (MTV, ÖTV, kasko değer yardımcısı, yakıt) + config oran tabloları.
10. `activity_logs`, rate limiting, güvenlik header, maskeleme, KVKK talep akışı,
    demo veri, README + deploy notları.

---

## 11. Açık / Ertelenen Konular

- **Sigorta şirketi API'leri:** kullanıcı temin edecek; adaptör sınıfları hazır
  bekleyecek. API sözleşmesi gelince her biri ayrı küçük iş.
- **F modülü (online ödeme + e-imza):** sanal POS + e-imza anlaşması ayrı süreç, v2.
- **SMS sağlayıcı:** varsayılan Netgsm; kullanıcı farklı sağlayıcı seçerse yeni
  `SmsSender` sürücüsü (~1 sınıf).
- **Kasko değer listesi:** canlı entegrasyon yok; elle referans değer + not.
- **Mobil uygulama:** kapsam dışı; site mobil-uyumlu (responsive).
- **Domain adı:** kullanıcı netleştirecek; kod `APP_URL` ile parametrik.
- **Veri taşıma:** v1 sıfırdan; gerekirse Excel/MySQL import komutu sonradan.
