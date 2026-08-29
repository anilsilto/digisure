<?php

use App\Models\ProductType;
use App\Models\Quote;
use App\Models\QuoteRequest;
use App\Models\User;
use Database\Seeders\ProductTypeSeeder;

it('shows counts for new requests and quotes awaiting entry', function () {
    $this->seed(ProductTypeSeeder::class);

    $r = QuoteRequest::factory()->for(ProductType::where('key', 'trafik')->first())->create(['status' => 'yeni']);
    Quote::factory()->for($r)->create(['status' => 'beklemede']);

    $this->actingAs(User::factory()->personel()->create(), 'panel')
        ->get('/panel')
        ->assertOk()
        ->assertSee('Yeni Talepler')
        ->assertSee('Teklif Bekleyen');
});
