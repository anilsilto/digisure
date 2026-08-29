@props(['class' => 'h-8'])

@php
    $logo = public_path('img/logo.png');
    $brand = config('digisure.brand');
@endphp

@if (file_exists($logo))
    <img src="{{ asset('img/logo.png') }}" alt="{{ $brand }}" {{ $attributes->merge(['class' => $class]) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-baseline font-extrabold tracking-tight text-navy text-xl']) }}>
        {{ $brand }}<span class="text-zred">.</span>
    </span>
@endif
