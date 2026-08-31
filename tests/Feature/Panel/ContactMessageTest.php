<?php

use App\Models\ContactMessage;

it('guards contact messages from guests', function () {
    $this->get('/panel/iletisim-mesajlari')->assertRedirect('/panel/giris');
});

it('lists contact messages and marks one read', function () {
    $msg = ContactMessage::create(['name' => 'Ali Veli', 'phone' => '05551112233', 'message' => 'Merhaba bir sorum var']);

    actingPanel()->get('/panel/iletisim-mesajlari')
        ->assertOk()
        ->assertSee('Ali Veli')
        ->assertSee('Merhaba bir sorum var');

    actingPanel()->post("/panel/iletisim-mesajlari/{$msg->id}/okundu")->assertRedirect();

    expect($msg->fresh()->read_at)->not->toBeNull();
});

it('deletes a contact message', function () {
    $msg = ContactMessage::create(['name' => 'X', 'phone' => '05550000000', 'message' => 'sil beni']);

    actingPanel()->delete("/panel/iletisim-mesajlari/{$msg->id}")->assertRedirect();

    expect(ContactMessage::count())->toBe(0);
});
