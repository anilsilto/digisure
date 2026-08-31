<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('panel.contact-messages.index', [
            'mesajlar' => ContactMessage::latest()->paginate(20),
            'okunmamis' => ContactMessage::unread()->count(),
        ]);
    }

    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['read_at' => $contactMessage->read_at ?? now()]);

        return back()->with('status', 'Mesaj okundu olarak işaretlendi.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return back()->with('status', 'Mesaj silindi.');
    }
}
