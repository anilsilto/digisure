<?php

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

it('accepts a contact message and mails the agency', function () {
    Mail::fake();

    $this->post('/iletisim', ['name' => 'Ali', 'phone' => '05322652392', 'message' => 'Merhaba'])
        ->assertRedirect();

    expect(ContactMessage::count())->toBe(1);
    Mail::assertQueued(ContactMessageMail::class);
});

it('rejects contact spam via honeypot', function () {
    Mail::fake();

    $this->post('/iletisim', ['name' => 'Ali', 'phone' => '05322652392', 'message' => 'x', 'website' => 'http://spam'])
        ->assertRedirect();

    expect(ContactMessage::count())->toBe(0);
    Mail::assertNothingQueued();
});
