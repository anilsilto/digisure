<?php

namespace App\Console\Commands;

use App\Models\QuoteRequest;
use App\Models\QuoteRequestField;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * KVKK veri saklama: 2 yıldan eski teklif taleplerinin form alanı değerlerini
 * boşaltır (aktif poliçesi olan müşterilere dokunmaz). Idempotent — varsayılan
 * olarak zamanlanmaz, DEPLOY.md'de opsiyonel cron olarak belgelenir.
 */
class AnonymizeOldRequests extends Command
{
    protected $signature = 'digisure:anonymize-old-requests {--years=2}';

    protected $description = '2 yıldan eski teklif taleplerinin kişisel form verilerini anonimleştirir.';

    public function handle(): int
    {
        $cutoff = Carbon::today()->subYears((int) $this->option('years'));

        $requestIds = QuoteRequest::where('created_at', '<', $cutoff)
            ->whereDoesntHave('customer.policies', fn ($q) => $q->where('status', 'aktif'))
            ->pluck('id');

        $affected = QuoteRequestField::whereIn('quote_request_id', $requestIds)
            ->whereNotNull('value')
            ->update(['value' => null]);

        $this->info("{$affected} form alanı anonimleştirildi.");

        return self::SUCCESS;
    }
}
