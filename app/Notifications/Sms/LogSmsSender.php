<?php

namespace App\Notifications\Sms;

use App\Support\Pii;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Geliştirme sürücüsü: SMS'i log'a yazar, gerçek gönderim yapmaz.
 */
class LogSmsSender implements SmsSender
{
    public function send(string $phone, string $message): SmsResult
    {
        Log::info('SMS [log]', [
            'phone' => Pii::mask($phone),
            'message' => $message,
        ]);

        return new SmsResult(ok: true, providerId: 'log-'.Str::random(10), raw: 'logged');
    }
}
