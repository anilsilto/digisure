<?php

use App\Models\Customer;

it('guards the portal', function () {
    $this->get('/hesabim')->assertRedirect('/hesabim/giris');
});

it('walks through the OTP login flow', function () {
    $c = Customer::create(['first_name' => 'A', 'last_name' => 'B', 'tc_no' => '12345678901', 'phone' => '05322652392']);

    $this->post('/hesabim/giris', ['tc_no' => '12345678901', 'telefon' => '05322652392'])
        ->assertRedirect('/hesabim/dogrula');

    $code = cache()->pull('__test_last_otp');

    $this->post('/hesabim/dogrula', ['tc_no' => '12345678901', 'telefon' => '05322652392', 'kod' => $code])
        ->assertRedirect('/hesabim');

    $this->assertAuthenticatedAs($c, 'customer');
});

it('rejects a bad code at the verify endpoint', function () {
    Customer::create(['first_name' => 'A', 'last_name' => 'B', 'tc_no' => '12345678901', 'phone' => '05322652392']);

    $this->post('/hesabim/giris', ['tc_no' => '12345678901', 'telefon' => '05322652392']);

    $this->post('/hesabim/dogrula', ['tc_no' => '12345678901', 'telefon' => '05322652392', 'kod' => '000000'])
        ->assertSessionHasErrors('kod');

    $this->assertGuest('customer');
});
