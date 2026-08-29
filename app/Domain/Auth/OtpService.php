<?php

namespace App\Domain\Auth;

use App\Models\Customer;
use App\Models\OtpCode;
use App\Notifications\Sms\SmsSender;
use App\Support\Pii;
use Illuminate\Validation\ValidationException;

class OtpService
{
    private const TTL_MINUTES = 3;

    private const MAX_ATTEMPTS = 5;

    public function __construct(private readonly SmsSender $sms) {}

    private function maxSendsPerHour(): int
    {
        return (int) config('digisure.otp.max_sends_per_hour', 3);
    }

    /**
     * TC + telefon eşleşen müşteri için OTP üretir ve SMS gönderir.
     */
    public function start(string $tc, string $phone): void
    {
        $phoneHash = Pii::hash($phone);

        $recentSends = OtpCode::where('phone_hash', $phoneHash)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentSends >= $this->maxSendsPerHour()) {
            throw ValidationException::withMessages([
                'telefon' => 'Çok fazla kod talebi. Lütfen bir süre sonra tekrar deneyin.',
            ]);
        }

        $customer = Customer::whereTc($tc)->wherePhone($phone)->first();

        if (! $customer) {
            throw ValidationException::withMessages([
                'tc_no' => 'Bilgiler eşleşmedi. Lütfen TC ve telefon numaranızı kontrol edin.',
            ]);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone_hash' => $phoneHash,
            'code_hash' => Pii::hash($code),
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
        ]);

        if (app()->environment('testing')) {
            cache()->put('__test_last_otp', $code, 60);
        }

        $this->sms->send($phone, "DigiSure doğrulama kodunuz: {$code}");
    }

    /**
     * Kodu doğrular; başarıysa müşteriyi döndürür.
     */
    public function verify(string $tc, string $phone, string $code): Customer
    {
        $customer = Customer::whereTc($tc)->wherePhone($phone)->first();

        $otp = OtpCode::where('phone_hash', Pii::hash($phone))
            ->whereNull('consumed_at')
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (! $customer || ! $otp || $otp->attempts >= self::MAX_ATTEMPTS) {
            throw ValidationException::withMessages(['kod' => 'Kod geçersiz veya süresi dolmuş.']);
        }

        if (! hash_equals($otp->code_hash, Pii::hash($code))) {
            $otp->increment('attempts');

            throw ValidationException::withMessages(['kod' => 'Kod hatalı.']);
        }

        $otp->update(['consumed_at' => now()]);

        return $customer;
    }
}
