<?php

use App\Domain\Policy\RenewalReminderService;
use App\Models\Policy;
use App\Models\RenewalReminder;

it('schedules sms+eposta reminders at 30/15/7 days before end', function () {
    $p = Policy::factory()->create(['status' => 'aktif', 'end_date' => today()->addDays(40)]);

    app(RenewalReminderService::class)->scheduleFor($p);

    expect(RenewalReminder::where('policy_id', $p->id)->count())->toBe(6);
    expect(RenewalReminder::where('remind_on', today()->addDays(10))->where('channel', 'sms')->exists())->toBeTrue();
});

it('is idempotent and skips past dates', function () {
    $p = Policy::factory()->create(['status' => 'aktif', 'end_date' => today()->addDays(10)]);
    $svc = app(RenewalReminderService::class);

    $svc->scheduleFor($p);
    $svc->scheduleFor($p);

    // 10-30 ve 10-15 geçmişte; sadece 10-7 = +3 gün ileride → 1 tarih × 2 kanal
    expect(RenewalReminder::where('policy_id', $p->id)->count())->toBe(2);
});

it('generates reminders for all active policies via the command', function () {
    Policy::factory()->count(2)->create(['status' => 'aktif', 'end_date' => today()->addDays(20)]);

    $this->artisan('digisure:generate-renewal-reminders')->assertSuccessful();

    expect(RenewalReminder::count())->toBeGreaterThan(0);
});
