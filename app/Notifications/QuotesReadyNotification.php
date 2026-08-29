<?php

namespace App\Notifications;

use App\Jobs\SendSmsJob;
use App\Models\NotificationLog;
use App\Models\QuoteRequest;

/**
 * "Teklifleriniz hazır" bilgilendirmesi.
 *
 * v1: sadece notification_logs'a 'kuyrukta' kaydı düşer. Task 12 bunu
 * gerçek SMS gönderimine (SendSmsJob) bağlar.
 */
class QuotesReadyNotification
{
    public function send(QuoteRequest $request): void
    {
        $customer = $request->customer;

        $message = sprintf(
            'Sayın %s, %s teklifleriniz hazır. Karşılaştırmak için: %s/hesabim (Ref: %s)',
            $customer->first_name,
            $request->productType->name,
            rtrim((string) config('app.url'), '/'),
            $request->reference_no,
        );

        $log = NotificationLog::create([
            'notifiable_type' => $customer::class,
            'notifiable_id' => $customer->id,
            'channel' => 'sms',
            'template' => 'teklifler_hazir',
            'payload' => [
                'message' => $message,
                'phone_hash' => $customer->phone_hash,
                'quote_request_id' => $request->id,
            ],
            'status' => 'kuyrukta',
        ]);

        SendSmsJob::dispatch($customer->phone, $message, $log->id);
    }
}
