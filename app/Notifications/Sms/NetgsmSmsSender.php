<?php

namespace App\Notifications\Sms;

use Illuminate\Support\Facades\Http;

/**
 * Netgsm HTTP API sürücüsü.
 * Başarılı yanıt "00 <mesajid>" veya "01 <mesajid>" ile başlar.
 */
class NetgsmSmsSender implements SmsSender
{
    public function send(string $phone, string $message): SmsResult
    {
        $config = config('digisure.sms.netgsm');

        $response = Http::timeout(15)->get('https://api.netgsm.com.tr/sms/send/get', [
            'usercode' => $config['usercode'],
            'password' => $config['password'],
            'gsmno' => preg_replace('/\D/', '', $phone),
            'message' => $message,
            'msgheader' => $config['header'],
        ]);

        $body = trim($response->body());
        $parts = preg_split('/\s+/', $body) ?: [];
        $code = $parts[0] ?? '';

        return new SmsResult(
            ok: in_array($code, ['00', '01'], true),
            providerId: $parts[1] ?? null,
            raw: $body,
        );
    }
}
