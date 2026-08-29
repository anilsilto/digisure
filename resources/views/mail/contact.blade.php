<x-mail::message>
# Yeni İletişim Formu Mesajı

**Ad Soyad:** {{ $contactMessage->name }}
**Telefon:** {{ $contactMessage->phone }}
@if ($contactMessage->email)
**E-posta:** {{ $contactMessage->email }}
@endif

**Mesaj:**

{{ $contactMessage->message }}

<x-mail::subcopy>
Gönderim: {{ $contactMessage->created_at?->format('d.m.Y H:i') }} — IP: {{ $contactMessage->ip }}
</x-mail::subcopy>
</x-mail::message>
