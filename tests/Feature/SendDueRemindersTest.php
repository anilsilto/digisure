<?php

use App\Jobs\SendSmsJob;
use App\Mail\RenewalReminderMail;
use App\Models\Policy;
use App\Models\RenewalReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

it('queues sms + email for due reminders and marks them sent', function () {
    Queue::fake();
    Mail::fake();

    $p = Policy::factory()->create(['status' => 'aktif', 'end_date' => today()->addDays(7)]);
    RenewalReminder::create(['policy_id' => $p->id, 'remind_on' => today(), 'channel' => 'sms', 'status' => 'bekliyor']);
    RenewalReminder::create(['policy_id' => $p->id, 'remind_on' => today(), 'channel' => 'eposta', 'status' => 'bekliyor']);

    $this->artisan('digisure:send-due-reminders')->assertSuccessful();

    Queue::assertPushed(SendSmsJob::class);
    Mail::assertQueued(RenewalReminderMail::class);
    expect(RenewalReminder::where('status', 'gonderildi')->count())->toBe(2);
});

it('does not resend already-sent reminders', function () {
    Queue::fake();

    $p = Policy::factory()->create(['status' => 'aktif', 'end_date' => today()->addDays(7)]);
    RenewalReminder::create(['policy_id' => $p->id, 'remind_on' => today(), 'channel' => 'sms', 'status' => 'gonderildi']);

    $this->artisan('digisure:send-due-reminders');

    Queue::assertNothingPushed();
});

it('skips reminders whose policy is no longer active', function () {
    Queue::fake();

    $p = Policy::factory()->create(['status' => 'iptal', 'end_date' => today()->addDays(7)]);
    RenewalReminder::create(['policy_id' => $p->id, 'remind_on' => today(), 'channel' => 'sms', 'status' => 'bekliyor']);

    $this->artisan('digisure:send-due-reminders');

    Queue::assertNothingPushed();
    expect(RenewalReminder::where('status', 'bekliyor')->count())->toBe(1);
});
