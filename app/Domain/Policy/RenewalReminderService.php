<?php

namespace App\Domain\Policy;

use App\Models\Policy;
use App\Models\RenewalReminder;
use Illuminate\Support\Carbon;

class RenewalReminderService
{
    /**
     * Poliçe bitişinden config('digisure.reminders.days_before') gün önce
     * her kanal (sms + eposta) için bir hatırlatma satırı oluşturur. Idempotent.
     */
    public function scheduleFor(Policy $policy): void
    {
        foreach (config('digisure.reminders.days_before', []) as $daysBefore) {
            $remindOn = $policy->end_date->copy()->subDays($daysBefore);

            if ($remindOn->lt(Carbon::today())) {
                continue;
            }

            foreach (['sms', 'eposta'] as $channel) {
                RenewalReminder::updateOrCreate(
                    [
                        'policy_id' => $policy->id,
                        'remind_on' => $remindOn->copy()->startOfDay(),
                        'channel' => $channel,
                    ],
                    ['status' => 'bekliyor'],
                );
            }
        }
    }
}
