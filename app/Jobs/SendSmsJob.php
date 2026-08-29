<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use App\Notifications\Sms\SmsSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [60, 300, 900];

    public function __construct(
        public string $phone,
        public string $message,
        public ?int $notificationLogId = null,
    ) {}

    public function handle(SmsSender $sms): void
    {
        $result = $sms->send($this->phone, $this->message);

        if ($this->notificationLogId) {
            NotificationLog::whereKey($this->notificationLogId)->update([
                'status' => $result->ok ? 'gonderildi' : 'hata',
                'provider_response' => $result->raw,
                'sent_at' => now(),
            ]);
        }

        if (! $result->ok) {
            throw new RuntimeException('SMS gönderilemedi.');
        }
    }
}
