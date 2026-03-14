@props(['name'])

@switch($name)
    @case('dashboard')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 19.5h15" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 16.5V10.5" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16.5V6.75" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 16.5v-3.75" />
        </svg>
        @break

    @case('patients')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.5v-1.125A3.375 3.375 0 0 0 11.625 15H7.5a3.375 3.375 0 0 0-3.375 3.375V19.5M13.5 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.5 12v-1.125A3.375 3.375 0 0 0 17.625 15H16.5m1.5-7.5a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
        </svg>
        @break

    @case('new-order')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m6-6H6" />
        </svg>
        @break

    @case('orders')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 4.5h9A2.25 2.25 0 0 1 18.75 6.75v12A.75.75 0 0 1 18 19.5H6a.75.75 0 0 1-.75-.75v-12A2.25 2.25 0 0 1 7.5 4.5Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3.75h6v2.5H9z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.625 9.75h6.75M8.625 13.125h6.75M8.625 16.5h4.5" />
        </svg>
        @break

    @case('samples')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 3.75h3m-4.125 0h5.25m-5.25 0v3.375l-4.5 7.313a4.5 4.5 0 0 0 3.833 6.812h6.584a4.5 4.5 0 0 0 3.833-6.812l-4.5-7.313V3.75" />
        </svg>
        @break

    @case('worklists')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 6.75h11.25M9 12h11.25M9 17.25h11.25M4.5 6.75h.75v.75H4.5zm0 5.25h.75v.75H4.5zm0 5.25h.75v.75H4.5z" />
        </svg>
        @break

    @case('results')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 16.5 9 11.25l3.75 3.75L20.25 7.5" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.25 12V7.5h-4.5" />
        </svg>
        @break

    @case('release')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        @break

    @case('billing')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m3.75-9.75h-5.625a2.625 2.625 0 1 0 0 5.25h3.75a2.625 2.625 0 1 1 0 5.25H8.25" />
        </svg>
        @break

    @case('categories')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 5.25A1.5 1.5 0 0 1 5.25 3.75h4.5a1.5 1.5 0 0 1 1.5 1.5v4.5a1.5 1.5 0 0 1-1.5 1.5h-4.5a1.5 1.5 0 0 1-1.5-1.5zm9 0a1.5 1.5 0 0 1 1.5-1.5h4.5a1.5 1.5 0 0 1 1.5 1.5v4.5a1.5 1.5 0 0 1-1.5 1.5h-4.5a1.5 1.5 0 0 1-1.5-1.5zm-9 9a1.5 1.5 0 0 1 1.5-1.5h4.5a1.5 1.5 0 0 1 1.5 1.5v4.5a1.5 1.5 0 0 1-1.5 1.5h-4.5a1.5 1.5 0 0 1-1.5-1.5zm9 0a1.5 1.5 0 0 1 1.5-1.5h4.5a1.5 1.5 0 0 1 1.5 1.5v4.5a1.5 1.5 0 0 1-1.5 1.5h-4.5a1.5 1.5 0 0 1-1.5-1.5z" />
        </svg>
        @break

    @case('catalog')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 18V6.75A2.25 2.25 0 0 0 17.25 4.5H6.75A2.25 2.25 0 0 0 4.5 6.75V18m15 0v.75A.75.75 0 0 1 18.75 19.5H5.25a.75.75 0 0 1-.75-.75V18m15 0H4.5m4.5-9h6m-6 3h6" />
        </svg>
        @break

    @case('users')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        @break

    @case('settings')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 6h3m-7.53 3.47 2.12-2.12m9.88 9.88 2.12 2.12M6 13.5v-3m12 3v-3m-2.47 7.53-2.12-2.12m-6.82 0-2.12 2.12M12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />
        </svg>
        @break

    @case('labs')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 21h16.5M5.25 21V7.5l6.75-3 6.75 3V21M9 21v-4.5h6V21M9 10.5h.008v.008H9zm6 0h.008v.008H15z" />
        </svg>
        @break

    @case('changelog')
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v12A2.25 2.25 0 0 1 16.5 20.25h-9A2.25 2.25 0 0 1 5.25 18V6A2.25 2.25 0 0 1 7.5 3.75Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 8.25h7.5M8.25 12h7.5M8.25 15.75h4.5" />
        </svg>
        @break

    @default
        <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m6-6H6" />
        </svg>
@endswitch
