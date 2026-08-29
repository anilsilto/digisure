<?php

namespace Database\Seeders;

use App\Domain\Quote\QuoteRequestService;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@digisure.test'],
            ['name' => 'Demo Admin', 'password' => Hash::make('parola123'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'personel@digisure.test'],
            ['name' => 'Demo Personel', 'password' => Hash::make('parola123'), 'role' => 'personel']
        );

        $service = app(QuoteRequestService::class);

        foreach (['trafik', 'kasko', 'saglik'] as $i => $key) {
            $product = ProductType::where('key', $key)->first();

            $request = $service->create(
                product: $product,
                fields: collect($product->field_schema)->mapWithKeys(fn ($f) => [$f['name'] => 'ÖRNEK'])->all(),
                customerAttrs: [
                    'tc_no' => str_pad((string) (10000000000 + $i), 11, '0', STR_PAD_LEFT),
                    'first_name' => 'Demo',
                    'last_name' => 'Müşteri '.($i + 1),
                    'phone' => '0555000000'.$i,
                    'email' => "demo{$i}@digisure.test",
                ],
                source: 'site',
                kvkk: true,
            );

            // İlk talebe teklifler girilmiş gibi
            if ($i === 0) {
                $request->quotes()->where('insurer', 'sompo')->update(['status' => 'verildi', 'premium' => 3200, 'policy_period_months' => 12]);
                $request->quotes()->where('insurer', 'quick')->update(['status' => 'verildi', 'premium' => 2950, 'policy_period_months' => 12]);
                $request->update(['status' => 'teklifler_hazir']);
            }
        }

        // Yaklaşan yenilemesi olan bir poliçe
        $customer = Customer::first();
        Policy::updateOrCreate(
            ['policy_no' => 'DEMO-2026-1'],
            [
                'customer_id' => $customer->id,
                'product_type_id' => ProductType::where('key', 'trafik')->value('id'),
                'insurer' => 'doga',
                'start_date' => now()->subMonths(11)->toDateString(),
                'end_date' => now()->addDays(12)->toDateString(),
                'premium' => 3100,
                'status' => 'aktif',
            ]
        );
    }
}
