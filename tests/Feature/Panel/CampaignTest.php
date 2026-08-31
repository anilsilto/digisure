<?php

use App\Models\CampaignProfile;
use App\Models\Customer;

it('guards the campaign screen from guests', function () {
    $this->get('/panel/kampanya')->assertRedirect('/panel/giris');
});

it('saves campaign branches and shows the analysis report', function () {
    $customer = Customer::factory()->create(['first_name' => 'Ahmet', 'last_name' => 'Yılmaz']);

    actingPanel()->put("/panel/kampanya/{$customer->id}", ['branches' => ['trafik', 'kasko']])
        ->assertRedirect();

    expect($customer->campaignProfile->branches)->toBe(['trafik', 'kasko']);

    actingPanel()->get("/panel/kampanya/{$customer->id}")
        ->assertOk()
        ->assertSee('HAK KAZANDI')
        ->assertSee('Satış Söylemi');
});

it('lets the panel assign a reward once the customer qualifies', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create(['branches' => ['kasko']]);

    actingPanel()->post("/panel/kampanya/{$customer->id}/odul", ['reward' => 'lastik_vip'])
        ->assertRedirect();

    $profile = $customer->campaignProfile->fresh();
    expect($profile->selected_reward)->toBe('lastik_vip')
        ->and($profile->reward_selected_by)->toBe('panel')
        ->and($profile->reward_selected_at)->not->toBeNull();
});

it('rejects a reward assignment when the customer is below the threshold', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create(['branches' => ['trafik', 'dask']]);

    actingPanel()->post("/panel/kampanya/{$customer->id}/odul", ['reward' => 'lastik_vip'])
        ->assertStatus(422);

    expect($customer->campaignProfile->fresh()->selected_reward)->toBeNull();
});

it('clears the selected reward when branches drop below the threshold', function () {
    $customer = Customer::factory()->create();
    $customer->campaignProfile()->create([
        'branches' => ['kasko'],
        'selected_reward' => 'oto_bakim',
        'reward_selected_at' => now(),
        'reward_selected_by' => 'panel',
    ]);

    actingPanel()->put("/panel/kampanya/{$customer->id}", ['branches' => ['trafik']])
        ->assertRedirect();

    expect($customer->campaignProfile->fresh())
        ->branches->toBe(['trafik'])
        ->selected_reward->toBeNull();
});

it('rejects unknown branch keys', function () {
    $customer = Customer::factory()->create();

    actingPanel()->put("/panel/kampanya/{$customer->id}", ['branches' => ['kasko', 'roket']])
        ->assertSessionHasErrors('branches.1');

    expect(CampaignProfile::count())->toBe(0);
});
