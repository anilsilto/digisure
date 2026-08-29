<?php

namespace App\Domain\Quote;

use App\Models\Quote;
use App\Models\QuoteRequest;
use App\Notifications\QuotesReadyNotification;
use App\Support\ActivityLogger;
use DomainException;
use Illuminate\Support\Facades\Schema;

/**
 * quote_requests.status durum makinesi.
 *
 *   yeni ──► teklifler_hazir ──► kabul ──► police
 *     └────────────► kabul (müşteri erken seçerse)
 *   her durum ──► iptal
 */
class QuoteRequestWorkflow
{
    /** @var array<string, list<string>> */
    private const TRANSITIONS = [
        'yeni' => ['teklifler_hazir', 'kabul', 'iptal'],
        'teklifler_hazir' => ['kabul', 'iptal'],
        'kabul' => ['police', 'iptal'],
        'police' => [],
        'iptal' => [],
    ];

    public static function canTransition(string $from, string $to): bool
    {
        return in_array($to, self::TRANSITIONS[$from] ?? [], true);
    }

    public static function markQuotesReady(QuoteRequest $request): void
    {
        self::guard($request->status, 'teklifler_hazir');

        $request->update(['status' => 'teklifler_hazir']);

        app(QuotesReadyNotification::class)->send($request);

        self::logActivity($request, 'quote_request.teklifler_hazir');
    }

    public static function accept(QuoteRequest $request, Quote $quote): void
    {
        if ($quote->quote_request_id !== $request->id || $quote->status !== 'verildi') {
            throw new DomainException('Bu teklif kabul edilemez.');
        }

        self::guard($request->status, 'kabul');

        $request->update([
            'status' => 'kabul',
            'accepted_quote_id' => $quote->id,
        ]);

        self::logActivity($request, 'quote_request.kabul');
    }

    public static function cancel(QuoteRequest $request): void
    {
        self::guard($request->status, 'iptal');

        $request->update(['status' => 'iptal']);

        self::logActivity($request, 'quote_request.iptal');
    }

    private static function guard(string $from, string $to): void
    {
        if (! self::canTransition($from, $to)) {
            throw new DomainException("Geçersiz durum geçişi: {$from} → {$to}");
        }
    }

    private static function logActivity(QuoteRequest $request, string $action): void
    {
        if (Schema::hasTable('activity_logs') && class_exists(ActivityLogger::class)) {
            ActivityLogger::log($action, $request);
        }
    }
}
