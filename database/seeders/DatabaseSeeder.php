<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // WithoutModelEvents KULLANMA: Customer'ın saving hook'u tc_hash/phone_hash'i doldurur.
        $this->call(ProductTypeSeeder::class);

        if (app()->environment('local')) {
            $this->call(DemoSeeder::class);
        }
    }
}
