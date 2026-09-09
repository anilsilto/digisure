<?php

namespace App\Domain\Import;

use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use App\Support\Pii;
use Illuminate\Support\Carbon;

/**
 * Eski panelden dışa aktarılmış müşteri (ve opsiyonel poliçe) satırlarını
 * sisteme aktarır. TC'den upsert eder — tekrar çalıştırmak güvenlidir.
 */
class CustomerImporter
{
    /**
     * Kanonik alan => olası başlık adları (küçük harf, sadeleştirilmiş).
     * Gerçek export başlıkları geldiğinde buraya eklenir.
     *
     * @var array<string, list<string>>
     */
    private const ALIASES = [
        'full_name' => ['ad soyad', 'adı soyadı', 'isim soyisim', 'müşteri', 'musteri', 'müşteri adı', 'ad-soyad'],
        'first_name' => ['ad', 'isim', 'adi', 'adı', 'first name', 'firstname', 'name'],
        'last_name' => ['soyad', 'soyisim', 'soyadi', 'soyadı', 'last name', 'lastname', 'surname'],
        'tc_no' => ['tc', 'tckn', 'tc no', 'tc kimlik', 'tc kimlik no', 'kimlik no', 'tc_no', 'tckimlik', 'tckimlikno', 'tc kimlik numarası'],
        'phone' => ['telefon', 'tel', 'gsm', 'cep', 'cep telefonu', 'cep no', 'gsm no', 'telefon no', 'phone', 'mobil'],
        'email' => ['e-posta', 'eposta', 'e posta', 'email', 'e-mail', 'mail', 'e_posta'],
        'birth_date' => ['doğum tarihi', 'dogum tarihi', 'd.tarihi', 'birth date', 'birthdate', 'doğum'],
        'address' => ['adres', 'address', 'açık adres', 'ikamet'],
        // poliçe alanları
        'policy_no' => ['poliçe no', 'police no', 'poliçe numarası', 'policy no', 'policy_no', 'polic no'],
        'product' => ['ürün', 'urun', 'branş', 'brans', 'poliçe türü', 'police turu', 'product', 'tür', 'tur', 'sigorta türü'],
        'insurer' => ['şirket', 'sirket', 'sigorta şirketi', 'sigorta sirketi', 'insurer', 'company'],
        'start_date' => ['başlangıç', 'baslangic', 'başlangıç tarihi', 'baslangic tarihi', 'start date', 'tanzim tarihi'],
        'end_date' => ['bitiş', 'bitis', 'bitiş tarihi', 'bitis tarihi', 'vade', 'vade tarihi', 'end date', 'yenileme tarihi'],
        'premium' => ['prim', 'tutar', 'brüt prim', 'brut prim', 'premium', 'net prim'],
    ];

    /** product sütunu değeri => product_types.key eşlemesi için ipuçları. */
    private const PRODUCT_HINTS = [
        'trafik' => 'trafik',
        'kasko' => 'kasko',
        'sağlık' => 'saglik',
        'saglik' => 'saglik',
        'tss' => 'saglik',
        'tamamlayıcı' => 'saglik',
        'konut' => 'konut',
        'dask' => 'konut',
        'işyeri' => 'isyeri',
        'isyeri' => 'isyeri',
        'seyahat' => 'seyahat',
        'ferdi' => 'ferdi-kaza',
    ];

    /** @var array<string, ProductType|null> */
    private array $productCache = [];

    /**
     * Ham başlık dizisini kanonik alan => sütun indeksi eşlemesine çevirir.
     *
     * @param  list<string>  $headers
     * @return array<string, int>
     */
    public function mapHeaders(array $headers): array
    {
        $map = [];

        foreach ($headers as $index => $header) {
            $key = $this->canonicalKey($header);
            if ($key !== null && ! isset($map[$key])) {
                $map[$key] = $index;
            }
        }

        return $map;
    }

    private function canonicalKey(string $header): ?string
    {
        $normalized = mb_strtolower(trim(preg_replace('/\s+/u', ' ', $header)), 'UTF-8');
        $normalized = ltrim($normalized, "\xEF\xBB\xBF"); // BOM

        foreach (self::ALIASES as $canonical => $aliases) {
            if (in_array($normalized, $aliases, true)) {
                return $canonical;
            }
        }

        return null;
    }

    /**
     * @param  array<string, string|null>  $row  kanonik alan => ham değer
     * @param  array{with_policies?:bool, kvkk_consent?:bool}  $options
     */
    public function importRow(array $row, array $options = []): ImportResult
    {
        [$firstName, $lastName] = $this->resolveName($row);
        $tc = ImportNormalizer::tcNo($row['tc_no'] ?? null);
        $phone = ImportNormalizer::phone($row['phone'] ?? null);

        if ($tc === null) {
            return ImportResult::skip('geçersiz veya eksik TC');
        }
        if ($phone === null) {
            return ImportResult::skip('geçersiz veya eksik telefon');
        }
        if ($firstName === null || $lastName === null) {
            return ImportResult::skip('ad/soyad eksik');
        }

        $existing = Customer::query()->where('tc_hash', Pii::hash($tc))->first();

        $attrs = [
            'tc_no' => $tc,
            'phone' => $phone,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];

        $email = ImportNormalizer::email($row['email'] ?? null);
        if ($email !== null) {
            $attrs['email'] = $email;
        }
        $birth = ImportNormalizer::date($row['birth_date'] ?? null);
        if ($birth !== null) {
            $attrs['birth_date'] = $birth;
        }
        $address = trim((string) ($row['address'] ?? ''));
        if ($address !== '') {
            $attrs['address'] = $address;
        }

        $customer = $existing ?? new Customer;
        $customer->fill($attrs);

        // KVKK onayı yalnızca açıkça istendiğinde ve zaten yoksa işaretlenir.
        if (($options['kvkk_consent'] ?? false) && $customer->kvkk_consent_at === null) {
            $customer->kvkk_consent_at = now();
        }
        // Pazarlama onayı asla otomatik atanmaz.

        $customer->save();

        $policyNote = null;
        if ($options['with_policies'] ?? false) {
            $policyNote = $this->importPolicy($customer, $row);
        }

        return $existing
            ? ImportResult::updated($customer, $policyNote)
            : ImportResult::created($customer, $policyNote);
    }

    /**
     * @param  array<string, string|null>  $row
     * @return array{0:?string,1:?string}
     */
    private function resolveName(array $row): array
    {
        $first = ImportNormalizer::name($row['first_name'] ?? null);
        $last = ImportNormalizer::name($row['last_name'] ?? null);

        if ($first !== null && $last !== null) {
            return [$first, $last];
        }

        if (filled($row['full_name'] ?? null)) {
            return ImportNormalizer::splitFullName($row['full_name']);
        }

        return [$first, $last];
    }

    /**
     * @param  array<string, string|null>  $row
     */
    private function importPolicy(Customer $customer, array $row): ?string
    {
        $productRaw = trim((string) ($row['product'] ?? ''));
        if ($productRaw === '') {
            return null; // poliçe sütunu yok — sessiz geç
        }

        $product = $this->resolveProduct($productRaw);
        if (! $product) {
            return "poliçe atlandı: '{$productRaw}' ürünü eşleşmedi";
        }

        $start = ImportNormalizer::date($row['start_date'] ?? null);
        $end = ImportNormalizer::date($row['end_date'] ?? null);
        if ($start === null || $end === null) {
            return "poliçe atlandı: {$product->name} için tarih okunamadı";
        }

        $policyNo = trim((string) ($row['policy_no'] ?? '')) ?: 'IMPORT-'.strtoupper(substr(md5($customer->id.$product->id.$start), 0, 10));
        $premium = $this->parseMoney((string) ($row['premium'] ?? '0'));

        Policy::updateOrCreate(
            ['customer_id' => $customer->id, 'policy_no' => $policyNo],
            [
                'product_type_id' => $product->id,
                'insurer' => $this->resolveInsurer($row['insurer'] ?? null),
                'start_date' => $start,
                'end_date' => $end,
                'premium' => $premium > 0 ? $premium : 0,
                'status' => Carbon::parse($end)->isPast() ? 'suresi_doldu' : 'aktif',
            ],
        );

        return null;
    }

    private function resolveProduct(string $raw): ?ProductType
    {
        $key = mb_strtolower($raw, 'UTF-8');

        if (array_key_exists($key, $this->productCache)) {
            return $this->productCache[$key];
        }

        $resolvedKey = null;
        foreach (self::PRODUCT_HINTS as $hint => $target) {
            if (str_contains($key, $hint)) {
                $resolvedKey = $target;
                break;
            }
        }

        return $this->productCache[$key] = $resolvedKey
            ? ProductType::where('key', $resolvedKey)->first()
            : null;
    }

    /**
     * "15.000,50" (TR) -> 15000.5 ; "15000.50" (EN) -> 15000.5 ; "15000" -> 15000.
     */
    private function parseMoney(string $raw): float
    {
        $raw = trim(str_replace(' ', '', $raw));

        if ($raw === '') {
            return 0.0;
        }

        if (str_contains($raw, ',')) {
            $raw = str_replace('.', '', $raw);   // binlik ayıracı
            $raw = str_replace(',', '.', $raw);  // ondalık
        }

        return (float) $raw;
    }

    private function resolveInsurer(?string $raw): string
    {
        $value = mb_strtolower(trim((string) $raw), 'UTF-8');
        $labels = config('digisure.insurer_labels', []);

        foreach ($labels as $key => $label) {
            if ($value !== '' && (str_contains($value, $key) || str_contains($value, mb_strtolower($label, 'UTF-8')))) {
                return $key;
            }
        }

        return 'sompo'; // export'ta şirket yoksa varsayılan; acente düzeltir
    }
}
