<?php

use App\Models\Customer;

it('guards the customer campaign page from guests', function () {
    $this->get('/hesabim/kampanya')->assertRedirect('/hesabim/giris');
});

it('shows the customer their own points and status', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create(['branches' => ['trafik', 'imm']]); // 2 puan

    $this->actingAs($customer, 'customer')
        ->get('/hesabim/kampanya')
        ->assertOk()
        ->assertSee('Zafir Güvence Karma')
        ->assertSee('puan daha'); // baraj altı mesajı
});

it('lets a qualifying customer pick a reward that persists', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create(['branches' => ['tss']]); // 5 puan

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/kampanya/odul', ['reward' => 'mobilite'])
        ->assertRedirect();

    $profile = $customer->campaignProfile->fresh();
    expect($profile->selected_reward)->toBe('mobilite')
        ->and($profile->reward_selected_by)->toBe('customer');
});

it('blocks reward selection for a customer below the threshold', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create(['branches' => ['trafik']]);

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/kampanya/odul', ['reward' => 'mobilite'])
        ->assertStatus(422);
});

it('blocks reward selection when the customer has no campaign profile', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->post('/hesabim/kampanya/odul', ['reward' => 'mobilite'])
        ->assertStatus(422);
});
