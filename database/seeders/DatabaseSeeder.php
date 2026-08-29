<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // WithoutModelEvents KULLANMA: Customer'ın saving hook'u tc_hash/phone_hash'i doldurur.
        $this->call(ProductTypeSeeder::class);
        $this->call(BlogSeeder::class);   // gerçek SEO içeriği — üretimde de yüklenir (idempotent)

        if (app()->environment('local')) {
            $this->call(DemoSeeder::class);
        }
    }
}
