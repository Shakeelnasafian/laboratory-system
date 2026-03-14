<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Super Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 font-sans antialiased" x-data>
<div class="relative flex h-full overflow-hidden">
    <div
        x-cloak
        x-show="$store.adminSidebar.open && !$store.adminSidebar.isDesktop"
        x-transition.opacity
        class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden"
        @click="$store.adminSidebar.closeMenu()"
    ></div>

    @if (app()->runningUnitTests())
        @include('layouts.partials.admin-sidebar')
    @else
        @persist('admin-sidebar')
            @include('layouts.partials.admin-sidebar')
        @endpersist
    @endif

    <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
        <header class="border-b border-gray-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-100 lg:hidden"
                        @click="$store.adminSidebar.openMenu()"
                    >
                        <span class="sr-only">Open menu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="hidden h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-100 lg:inline-flex"
                        @click="$store.adminSidebar.toggleMini()"
                    >
                        <span class="sr-only">Toggle sidebar width</span>
                        <svg x-show="!$store.adminSidebar.mini" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
                        </svg>
                        <svg x-show="$store.adminSidebar.mini" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <h1 class="text-lg font-semibold text-gray-800 sm:text-2xl">{{ $title ?? 'Admin' }}</h1>
                </div>

                <div class="text-xs text-gray-500 sm:text-sm">{{ now()->format('D, d M Y') }}</div>
            </div>
        </header>

        <div class="px-4 pt-4 sm:px-6">
            @if(session('success'))
                <div class="mb-2 rounded-xl border border-green-300 bg-green-100 px-4 py-2 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <main class="min-w-0 flex-1 overflow-y-auto p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
