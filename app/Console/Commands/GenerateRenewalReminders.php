<?php

namespace App\Console\Commands;

use App\Domain\Policy\RenewalReminderService;
use App\Models\Policy;
use Illuminate\Console\Command;

class GenerateRenewalReminders extends Command
{
    protected $signature = 'digisure:generate-renewal-reminders';

    protected $description = 'Aktif poliçeler için eksik yenileme hatırlatmalarını üretir (güvenlik ağı).';

    public function handle(RenewalReminderService $service): int
    {
        $count = 0;

        Policy::where('status', 'aktif')->chunkById(200, function ($policies) use ($service, &$count) {
            foreach ($policies as $policy) {
                $service->scheduleFor($policy);
                $count++;
            }
        });

        $this->info("{$count} aktif poliçe için hatırlatmalar tazelendi.");

        return self::SUCCESS;
    }
}
