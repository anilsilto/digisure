<?php

namespace App\Mail;

use App\Models\Policy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Policy $policy)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Poliçe Yenileme Hatırlatması — ' . config('digisure.agency.name'));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.renewal', with: [
            'policy' => $this->policy,
            'insurerLabel' => $this->policy->insurerLabel(),
        ]);
    }
}
