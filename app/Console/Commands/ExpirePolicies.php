<?php

namespace App\Console\Commands;

use App\Models\Policy;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpirePolicies extends Command
{
    protected $signature = 'digisure:expire-policies';

    protected $description = 'Bitiş tarihi geçmiş aktif poliçeleri "suresi_doldu" yapar.';

    public function handle(): int
    {
        $count = Policy::where('status', 'aktif')
            ->whereDate('end_date', '<', Carbon::today())
            ->update(['status' => 'suresi_doldu']);

        $this->info("{$count} poliçe süresi doldu olarak işaretlendi.");

        return self::SUCCESS;
    }
}
