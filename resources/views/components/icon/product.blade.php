@props(['type' => 'trafik', 'class' => 'h-8 w-8'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    @switch($type)
        @case('trafik')
            {{-- Lucide: car --}}
            <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.6-1.6-1.6H18l-1.5-4.3A2 2 0 0 0 14.6 5H9.4a2 2 0 0 0-1.9 1.3L6 11H3.6C2.7 11 2 11.7 2 12.6V16c0 .6.4 1 1 1h2"/>
            <path d="M9 17h6"/>
            <circle cx="7" cy="17" r="2"/>
            <circle cx="17" cy="17" r="2"/>
            @break

        @case('kasko')
            {{-- Kalkan + üstünde otomobil --}}
            <path d="M20 13c0 5-3.5 7.5-7.7 8.9a1 1 0 0 1-.6 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.2-2.7a1 1 0 0 1 1.6 0C15.5 3.8 18 5 20 5a1 1 0 0 1 1 1z"/>
            <path d="M8.4 13.6l.9-2.5a1.4 1.4 0 0 1 1.3-.9h2.8a1.4 1.4 0 0 1 1.3.9l.9 2.5"/>
            <path d="M7.7 13.6h8.6v1.5a.7.7 0 0 1-.7.7h-.5a.7.7 0 0 1-.7-.7v-.3H9.6v.3a.7.7 0 0 1-.7.7h-.5a.7.7 0 0 1-.7-.7z"/>
            <circle cx="10" cy="13.6" r=".65" fill="currentColor"/>
            <circle cx="14" cy="13.6" r=".65" fill="currentColor"/>
            @break

        @case('saglik')
            {{-- Lucide: heart-pulse --}}
            <path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7z"/>
            <path d="M3.2 12h5l.7-1.4 2 4.5 2.2-6.4L16.4 12H21"/>
            @break

        @case('konut')
            {{-- Ev + koruma --}}
            <path d="M3 10.5 12 3l9 7.5"/>
            <path d="M5 9.5V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5"/>
            <path d="M10 21v-5a2 2 0 0 1 4 0v5"/>
            @break

        @case('seyahat')
            {{-- Uçak --}}
            <path d="M17.8 19.2 16 11l3.5-3.5a2.1 2.1 0 0 0-3-3L13 8 4.8 6.2a.5.5 0 0 0-.5.8L8.5 11l-2.5 3H4l-1 1.5L6 17l1.5 3 1.5-1v-2l3-2.5 4.2 4.2a.5.5 0 0 0 .8-.5z"/>
            @break

        @case('isyeri')
            {{-- Dükkân / mağaza --}}
            <path d="M4 9h16l-1-4H5z"/>
            <path d="M4 9v2a3 3 0 0 0 6 0 3 3 0 0 0 4 0 3 3 0 0 0 6 0V9"/>
            <path d="M5 13v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-7"/>
            <path d="M10 21v-4h4v4"/>
            @break

        @case('ferdi-kaza')
            {{-- Kalkan içinde kişi --}}
            <path d="M12 3l8 3v6c0 5-3.4 8.4-8 10-4.6-1.6-8-5-8-10V6z"/>
            <circle cx="12" cy="10.5" r="2"/>
            <path d="M8.5 16.5a3.7 3.7 0 0 1 7 0"/>
            @break

        @default
            <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
