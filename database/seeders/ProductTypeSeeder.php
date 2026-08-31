<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'key' => 'trafik',
                'name' => 'Trafik Sigortası',
                'icon' => 'car',
                'sort' => 1,
                'field_schema' => [
                    ['name' => 'plaka', 'label' => 'Plaka', 'type' => 'text', 'required' => true],
                    ['name' => 'tc_kimlik', 'label' => 'TC Kimlik No', 'type' => 'text', 'required' => true],
                    ['name' => 'ruhsat_belge_no', 'label' => 'Ruhsat Belge No', 'type' => 'text', 'required' => true],
                    ['name' => 'tescil_tarihi', 'label' => 'Tescil Tarihi', 'type' => 'date', 'required' => true],
                    ['name' => 'kullanim_tarzi', 'label' => 'Kullanım Tarzı', 'type' => 'select', 'required' => true,
                        'options' => ['Hususi', 'Ticari', 'Kamu']],
                ],
            ],
            [
                'key' => 'kasko',
                'name' => 'Kasko Sigortası',
                'icon' => 'shield',
                'sort' => 2,
                'field_schema' => [
                    ['name' => 'plaka', 'label' => 'Plaka', 'type' => 'text', 'required' => true],
                    ['name' => 'tc_kimlik', 'label' => 'TC Kimlik No', 'type' => 'text', 'required' => true],
                    ['name' => 'marka', 'label' => 'Marka', 'type' => 'text', 'required' => true],
                    ['name' => 'model_yili', 'label' => 'Model Yılı', 'type' => 'number', 'required' => true],
                    ['name' => 'ruhsat_belge_no', 'label' => 'Ruhsat Belge No', 'type' => 'text', 'required' => true],
                    ['name' => 'km', 'label' => 'Kilometre', 'type' => 'number', 'required' => false],
                ],
            ],
            [
                'key' => 'saglik',
                'name' => 'Sağlık Sigortası',
                'icon' => 'heart',
                'sort' => 3,
                'field_schema' => [
                    ['name' => 'dogum_tarihi', 'label' => 'Doğum Tarihi', 'type' => 'date', 'required' => true],
                    ['name' => 'boy_cm', 'label' => 'Boy (cm)', 'type' => 'number', 'required' => true],
                    ['name' => 'kilo_kg', 'label' => 'Kilo (kg)', 'type' => 'number', 'required' => true],
                    ['name' => 'sehir', 'label' => 'Şehir', 'type' => 'text', 'required' => true],
                    ['name' => 'mevcut_hastalik', 'label' => 'Mevcut Hastalık', 'type' => 'select', 'required' => true,
                        'options' => ['Yok', 'Var']],
                    ['name' => 'plan_tipi', 'label' => 'Plan Tipi', 'type' => 'select', 'required' => true,
                        'options' => ['Tamamlayıcı', 'Özel']],
                ],
            ],
            [
                'key' => 'konut',
                'name' => 'Konut & DASK',
                'icon' => 'home',
                'sort' => 4,
                'field_schema' => [
                    ['name' => 'il', 'label' => 'İl', 'type' => 'text', 'required' => true],
                    ['name' => 'ilce', 'label' => 'İlçe', 'type' => 'text', 'required' => true],
                    ['name' => 'bina_yasi', 'label' => 'Bina Yaşı', 'type' => 'number', 'required' => true],
                    ['name' => 'brut_m2', 'label' => 'Brüt m²', 'type' => 'number', 'required' => true],
                    ['name' => 'yapi_tarzi', 'label' => 'Yapı Tarzı', 'type' => 'select', 'required' => true,
                        'options' => ['Betonarme', 'Yığma', 'Çelik', 'Ahşap / Diğer']],
                    ['name' => 'kat_no', 'label' => 'Bulunduğu Kat', 'type' => 'number', 'required' => false],
                    ['name' => 'sahiplik', 'label' => 'Sahiplik Durumu', 'type' => 'select', 'required' => true,
                        'options' => ['Ev sahibi', 'Kiracı']],
                    ['name' => 'dask_var_mi', 'label' => 'Geçerli DASK poliçesi var mı?', 'type' => 'select', 'required' => true,
                        'options' => ['Var', 'Yok']],
                ],
            ],
            [
                'key' => 'seyahat',
                'name' => 'Seyahat Sağlık Sigortası',
                'icon' => 'plane',
                'sort' => 5,
                'field_schema' => [
                    ['name' => 'gidilecek_bolge', 'label' => 'Gidilecek Bölge', 'type' => 'select', 'required' => true,
                        'options' => ['Schengen (Avrupa)', 'Avrupa (Schengen dışı)', 'Dünya', 'Yurt içi']],
                    ['name' => 'gidis_tarihi', 'label' => 'Gidiş Tarihi', 'type' => 'date', 'required' => true],
                    ['name' => 'donus_tarihi', 'label' => 'Dönüş Tarihi', 'type' => 'date', 'required' => true],
                    ['name' => 'yolcu_sayisi', 'label' => 'Yolcu Sayısı', 'type' => 'number', 'required' => true],
                    ['name' => 'en_yasli_dogum_tarihi', 'label' => 'En Yaşlı Yolcunun Doğum Tarihi', 'type' => 'date', 'required' => true],
                    ['name' => 'seyahat_amaci', 'label' => 'Seyahat Amacı', 'type' => 'select', 'required' => true,
                        'options' => ['Turistik', 'İş', 'Eğitim', 'Vize başvurusu']],
                ],
            ],
            [
                'key' => 'isyeri',
                'name' => 'İşyeri Sigortası',
                'icon' => 'store',
                'sort' => 6,
                'field_schema' => [
                    ['name' => 'il', 'label' => 'İl', 'type' => 'text', 'required' => true],
                    ['name' => 'ilce', 'label' => 'İlçe', 'type' => 'text', 'required' => true],
                    ['name' => 'faaliyet_konusu', 'label' => 'Faaliyet Konusu', 'type' => 'text', 'required' => true],
                    ['name' => 'brut_m2', 'label' => 'İşyeri Alanı (m²)', 'type' => 'number', 'required' => true],
                    ['name' => 'bina_sahiplik', 'label' => 'Bina Durumu', 'type' => 'select', 'required' => true,
                        'options' => ['Mal sahibi', 'Kiracı']],
                    ['name' => 'demirbas_bedeli', 'label' => 'Demirbaş Bedeli (TL)', 'type' => 'number', 'required' => true],
                    ['name' => 'emtia_bedeli', 'label' => 'Emtia / Stok Bedeli (TL)', 'type' => 'number', 'required' => false],
                ],
            ],
            [
                'key' => 'ferdi-kaza',
                'name' => 'Ferdi Kaza Sigortası',
                'icon' => 'user-shield',
                'sort' => 7,
                'field_schema' => [
                    ['name' => 'dogum_tarihi', 'label' => 'Doğum Tarihi', 'type' => 'date', 'required' => true],
                    ['name' => 'meslek', 'label' => 'Meslek', 'type' => 'text', 'required' => true],
                    ['name' => 'teminat_tutari', 'label' => 'İstenen Teminat Tutarı', 'type' => 'select', 'required' => true,
                        'options' => ['100.000 TL', '250.000 TL', '500.000 TL', '1.000.000 TL']],
                    ['name' => 'ek_teminat', 'label' => 'Ek Teminat', 'type' => 'select', 'required' => false,
                        'options' => ['Yok', 'Tedavi masrafları', 'Gündelik tazminat', 'Her ikisi']],
                ],
            ],
        ];

        foreach ($products as $product) {
            ProductType::updateOrCreate(['key' => $product['key']], $product);
        }
    }
}
