<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('digisure:expire-policies')->dailyAt('02:00');
Schedule::command('digisure:generate-renewal-reminders')->dailyAt('02:05');
Schedule::command('digisure:send-due-reminders')->dailyAt('08:00');
// digisure:anonymize-old-requests — opsiyonel, DEPLOY.md'de belgelenir, varsayılan olarak zamanlanmaz.
