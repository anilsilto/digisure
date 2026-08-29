@props(['class' => 'w-72 h-72'])

{{-- Kalkan içinde baykuş — logo (kalkan) + maskot (baykuş) motifini birleştiren amblem. --}}
<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg"
     {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    <defs>
        <linearGradient id="hm-shield" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#2CB8DA"/>
            <stop offset="1" stop-color="#1C93B4"/>
        </linearGradient>
    </defs>

    {{-- kalkan --}}
    <path d="M100 12 L176 40 V104 C176 148 142 178 100 190 C58 178 24 148 24 104 V40 Z"
          stroke="url(#hm-shield)" stroke-width="5" fill="#ffffff" fill-opacity="0.04"/>

    {{-- baykuş kulak tüyleri --}}
    <path d="M60 74 L64 50 L82 62 M140 74 L136 50 L118 62"
          stroke="#2CB8DA" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>

    {{-- baykuş baş hattı --}}
    <path d="M100 58 C126 58 142 74 142 98 C142 124 124 140 100 140 C76 140 58 124 58 98 C58 74 74 58 100 58 Z"
          stroke="#EAF7FB" stroke-width="4" fill="none"/>

    {{-- gözler --}}
    <circle cx="82" cy="92" r="15" stroke="#2CB8DA" stroke-width="5"/>
    <circle cx="118" cy="92" r="15" stroke="#2CB8DA" stroke-width="5"/>
    <circle cx="82" cy="92" r="5" fill="#2CB8DA"/>
    <circle cx="118" cy="92" r="5" fill="#2CB8DA"/>

    {{-- gaga --}}
    <path d="M100 104 L94 116 H106 Z" fill="#2CB8DA"/>

    {{-- logodaki üç nokta motifi --}}
    <circle cx="150" cy="150" r="4" fill="#2CB8DA"/>
    <circle cx="163" cy="141" r="3.5" fill="#2CB8DA" fill-opacity="0.7"/>
    <circle cx="173" cy="134" r="3" fill="#2CB8DA" fill-opacity="0.45"/>
</svg>
