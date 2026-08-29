<?php

use App\Models\User;

it('blocks the panel for guests', function () {
    $this->get('/panel')->assertRedirect('/panel/giris');
});

it('logs a staff user in and shows the dashboard', function () {
    $user = User::factory()->personel()->create(['password' => bcrypt('secret12')]);

    $this->post('/panel/giris', ['email' => $user->email, 'password' => 'secret12'])
        ->assertRedirect('/panel');

    $this->actingAs($user, 'panel')->get('/panel')->assertOk()->assertSee('Dashboard');
});

it('rejects a wrong password', function () {
    $user = User::factory()->personel()->create(['password' => bcrypt('secret12')]);

    $this->post('/panel/giris', ['email' => $user->email, 'password' => 'wrong'])
        ->assertSessionHasErrors('email');

    $this->assertGuest('panel');
});

it('hides admin nav from personel', function () {
    $this->actingAs(User::factory()->personel()->create(), 'panel')
        ->get('/panel')
        ->assertDontSee('Kullanıcılar')
        ->assertDontSee('Ayarlar');
});

it('shows admin nav to admin', function () {
    $this->actingAs(User::factory()->admin()->create(), 'panel')
        ->get('/panel')
        ->assertSee('Kullanıcılar');
});
