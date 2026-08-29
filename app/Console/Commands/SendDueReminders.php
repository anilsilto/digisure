<?php

namespace App\Console\Commands;

use App\Jobs\SendSmsJob;
use App\Mail\RenewalReminderMail;
use App\Models\NotificationLog;
use App\Models\RenewalReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendDueReminders extends Command
{
    protected $signature = 'digisure:send-due-reminders';

    protected $description = 'Bugüne kadar zamanı gelmiş yenileme hatırlatmalarını SMS/e-posta olarak gönderir.';

    public function handle(): int
    {
        $due = RenewalReminder::where('status', 'bekliyor')
            ->whereDate('remind_on', '<=', Carbon::today())
            ->whereHas('policy', fn ($q) => $q->where('status', 'aktif'))
            ->with(['policy.customer', 'policy.productType'])
            ->get();

        foreach ($due as $reminder) {
            $policy = $reminder->policy;
            $customer = $policy->customer;

            if ($reminder->channel === 'sms') {
                $message = sprintf(
                    'Sayın %s, %s %s poliçeniz %s tarihinde sona eriyor. Yenileme için: %s/hesabim/policeler',
                    $customer->first_name,
                    $policy->insurerLabel(),
                    $policy->productType->name,
                    $policy->end_date->format('d.m.Y'),
                    rtrim((string) config('app.url'), '/'),
                );

                $log = NotificationLog::create([
                    'notifiable_type' => $customer::class,
                    'notifiable_id' => $customer->id,
                    'channel' => 'sms',
                    'template' => 'yenileme_hatirlatma',
                    'payload' => ['message' => $message, 'policy_id' => $policy->id],
                    'status' => 'kuyrukta',
                ]);

                SendSmsJob::dispatch($customer->phone, $message, $log->id);
            } elseif ($customer->email) {
                Mail::to($customer->email)->queue(new RenewalReminderMail($policy));
            }

            $reminder->update(['status' => 'gonderildi', 'sent_at' => now()]);
        }

        $this->info($due->count().' hatırlatma işlendi.');

        return self::SUCCESS;
    }
}
