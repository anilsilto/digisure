@props(['type' => 'trafik', 'class' => 'h-7 w-7'])

@php
    // navy ana çizgi + turkuaz vurgu
    $stroke = 'stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
@endphp

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    @switch($type)
        @case('trafik')
            {{-- Otomobil --}}
            <path {!! $stroke !!} d="M5 20l1.8-5.4A3 3 0 0 1 9.6 12.5h12.8a3 3 0 0 1 2.8 2.1L27 20"/>
            <path {!! $stroke !!} d="M4 20h24v3.2a1.3 1.3 0 0 1-1.3 1.3h-1.4a1.3 1.3 0 0 1-1.3-1.3V22H9v1.2a1.3 1.3 0 0 1-1.3 1.3H6.3A1.3 1.3 0 0 1 5 23.2z"/>
            <path {!! $stroke !!} d="M10 12.5l1-3.2A2 2 0 0 1 13.9 8h4.2a2 2 0 0 1 1.9 1.3l1 3.2"/>
            <circle cx="9.5" cy="20" r="1.4" fill="var(--color-accent)"/>
            <circle cx="22.5" cy="20" r="1.4" fill="var(--color-accent)"/>
            @break

        @case('kasko')
            {{-- Kalkan içinde otomobil (koruma) --}}
            <path {!! $stroke !!} d="M16 3.5l9 3.3v6.7c0 6-3.9 10.2-9 11.5-5.1-1.3-9-5.5-9-11.5V6.8z"/>
            <path {!! $stroke !!} d="M10.5 16.5l1-3a2 2 0 0 1 1.9-1.3h5.2a2 2 0 0 1 1.9 1.3l1 3"/>
            <path {!! $stroke !!} d="M9.5 16.5h13v3a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-.6h-6v.6a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1z"/>
            <circle cx="12.6" cy="16.6" r="1" fill="var(--color-accent)"/>
            <circle cx="19.4" cy="16.6" r="1" fill="var(--color-accent)"/>
            @break

        @case('saglik')
            {{-- Nabız çizgili kalp --}}
            <path {!! $stroke !!} d="M16 27C9.5 22.4 5 18.2 5 12.9A5.9 5.9 0 0 1 16 9.6a5.9 5.9 0 0 1 11 3.3C27 18.2 22.5 22.4 16 27z"/>
            <path stroke="var(--color-accent)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                  d="M8 16.2h4l2-3.4 3 6 2-3.1h3"/>
            @break

        @default
            <circle {!! $stroke !!} cx="16" cy="16" r="10"/>
    @endswitch
</svg>
