<?php

namespace App\Notifications\Sms;

interface SmsSender
{
    public function send(string $phone, string $message): SmsResult;
}
