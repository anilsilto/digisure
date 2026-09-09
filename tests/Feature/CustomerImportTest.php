<?php

use App\Models\Customer;
use App\Models\Policy;
use App\Support\Pii;
use Database\Seeders\ProductTypeSeeder;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    $this->seed(ProductTypeSeeder::class);
    $this->csv = function (string $body): string {
        $path = tempnam(sys_get_temp_dir(), 'imp').'.csv';
        file_put_contents($path, $body);

        return $path;
    };
});

it('imports customers from a semicolon CSV with Turkish headers', function () {
    $file = ($this->csv)(<<<'CSV'
    Ad;Soyad;TC Kimlik No;Cep Telefonu;E-Posta
    ayşe;yılmaz;12345678901;0 (532) 265 23 92;AYSE@X.COM
    Mehmet;Demir;98765432109;532 111 22 33;
    CSV);

    Artisan::call('digisure:import-customers', ['file' => $file]);

    expect(Customer::count())->toBe(2);

    $ayse = Customer::where('tc_hash', Pii::hash('12345678901'))->first();
    expect($ayse->first_name)->toBe('Ayşe')
        ->and($ayse->last_name)->toBe('Yılmaz')
        ->and($ayse->phone)->toBe('05322652392')
        ->and($ayse->email)->toBe('ayse@x.com')
        ->and($ayse->kvkk_consent_at)->toBeNull();
});

it('is idempotent — re-running updates instead of duplicating', function () {
    $file = ($this->csv)("Ad,Soyad,TC,Telefon\nAli,Veli,12345678901,05322652392\n");

    Artisan::call('digisure:import-customers', ['file' => $file]);
    Artisan::call('digisure:import-customers', ['file' => $file]);

    expect(Customer::count())->toBe(1);
});

it('skips rows with an invalid TC or phone and writes a report', function () {
    $report = tempnam(sys_get_temp_dir(), 'rep').'.csv';
    $file = ($this->csv)(<<<'CSV'
    Ad,Soyad,TC,Telefon
    Geçerli,Kayıt,12345678901,05322652392
    Kötü,TC,123,05322652392
    Kötü,Telefon,98765432109,212 555 44 33
    CSV);

    Artisan::call('digisure:import-customers', ['file' => $file, '--report' => $report]);

    expect(Customer::count())->toBe(1);
    $reportBody = file_get_contents($report);
    expect($reportBody)->toContain('geçersiz veya eksik TC')
        ->and($reportBody)->toContain('geçersiz veya eksik telefon');
});

it('writes nothing on --dry-run', function () {
    $file = ($this->csv)("Ad,Soyad,TC,Telefon\nAli,Veli,12345678901,05322652392\n");

    Artisan::call('digisure:import-customers', ['file' => $file, '--dry-run' => true]);

    expect(Customer::count())->toBe(0);
});

it('sets kvkk consent only with the flag', function () {
    $file = ($this->csv)("Ad,Soyad,TC,Telefon\nAli,Veli,12345678901,05322652392\n");

    Artisan::call('digisure:import-customers', ['file' => $file, '--kvkk-consent' => true]);

    expect(Customer::first()->kvkk_consent_at)->not->toBeNull();
});

it('splits a single full-name column', function () {
    $file = ($this->csv)("Ad Soyad,TC,Telefon\nAhmet Can Öztürk,12345678901,05322652392\n");

    Artisan::call('digisure:import-customers', ['file' => $file]);

    expect(Customer::first())
        ->first_name->toBe('Ahmet Can')
        ->last_name->toBe('Öztürk');
});

it('imports policies when --with-policies is set', function () {
    $file = ($this->csv)(<<<'CSV'
    Ad;Soyad;TC;Telefon;Ürün;Şirket;Başlangıç;Bitiş;Prim
    Ali;Veli;12345678901;05322652392;Kasko Sigortası;Quick Sigorta;01.01.2026;01.01.2027;15.000,50
    CSV);

    Artisan::call('digisure:import-customers', ['file' => $file, '--with-policies' => true]);

    $policy = Policy::sole();
    expect($policy->insurer)->toBe('quick')
        ->and($policy->productType->key)->toBe('kasko')
        ->and((float) $policy->premium)->toBe(15000.50)
        ->and($policy->status)->toBe('aktif');
});

it('reports an unmatched product without failing the customer', function () {
    $file = ($this->csv)(<<<'CSV'
    Ad,Soyad,TC,Telefon,Ürün,Başlangıç,Bitiş
    Ali,Veli,12345678901,05322652392,Evcil Hayvan Sigortası,01.01.2026,01.01.2027
    CSV);

    $code = Artisan::call('digisure:import-customers', ['file' => $file, '--with-policies' => true]);

    expect($code)->toBe(0);
    expect(Customer::count())->toBe(1);
    expect(Policy::count())->toBe(0);
    expect(Artisan::output())->toContain('eşleşmedi');
});
