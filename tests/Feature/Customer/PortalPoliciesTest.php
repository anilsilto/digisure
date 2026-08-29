<?php

use App\Models\Customer;
use App\Models\Policy;

it('shows a customer only their policies', function () {
    $foreign = Policy::factory()->for(Customer::factory())->create();
    $mine = Policy::factory()->for($c = Customer::factory()->create())->create();

    $this->actingAs($c, 'customer')
        ->get('/hesabim/policeler')
        ->assertSee($mine->policy_no)
        ->assertDontSee($foreign->policy_no);
});
