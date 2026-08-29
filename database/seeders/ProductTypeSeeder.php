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
        ];

        foreach ($products as $product) {
            ProductType::updateOrCreate(['key' => $product['key']], $product);
        }
    }
}
