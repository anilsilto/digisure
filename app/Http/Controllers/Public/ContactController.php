<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    public function send(Request $request): RedirectResponse
    {
        // Honeypot: botlar "website" alanını doldurur; sessizce başarı gibi dön.
        if (filled($request->input('website'))) {
            return back()->with('status', 'Mesajınız alındı.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contactMessage = ContactMessage::create([
            ...$data,
            'ip' => $request->ip(),
        ]);

        Mail::to(config('digisure.agency.email'))->queue(new ContactMessageMail($contactMessage));

        return back()->with('status', 'Mesajınız alındı, en kısa sürede dönüş yapacağız.');
    }
}
