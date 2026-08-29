<x-mail::message>
# Poliçe Yenileme Hatırlatması

Sayın {{ $policy->customer->first_name }} {{ $policy->customer->last_name }},

**{{ $insurerLabel }}** {{ $policy->productType->name }} poliçeniz
**{{ $policy->end_date->format('d.m.Y') }}** tarihinde sona eriyor.

- Poliçe No: {{ $policy->policy_no }}
- Bitiş Tarihi: {{ $policy->end_date->format('d.m.Y') }}

Yenileme için hesabınıza giriş yapabilir veya bizimle iletişime geçebilirsiniz.

<x-mail::button :url="rtrim(config('app.url'), '/') . '/hesabim/policeler'">
Poliçelerim
</x-mail::button>

{{ config('digisure.agency.name') }}
{{ config('digisure.agency.phone') }}
</x-mail::message>
