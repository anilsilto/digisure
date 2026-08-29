@props(['class' => 'h-9'])

@php
    $brand = config('digisure.brand');
    // İlk bulunan dosyayı kullan (png/jpg/webp/svg)
    $found = collect(['svg', 'png', 'webp', 'jpg', 'jpeg'])
        ->map(fn ($ext) => "img/logo.$ext")
        ->first(fn ($rel) => file_exists(public_path($rel)));
@endphp

@if ($found)
    <img src="{{ asset($found) }}" alt="{{ $brand }}"
         {{ $attributes->merge(['class' => $class . ' w-auto max-w-[200px] object-contain']) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-baseline font-extrabold tracking-tight text-navy text-xl']) }}>
        {{ $brand }}<span class="text-accent">.</span>
    </span>
@endif
