<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Super Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 font-sans antialiased">

<div class="flex h-full">

    {{-- ── Admin Sidebar ─────────────────────────────────────────── --}}
    <aside class="w-64 flex-shrink-0 bg-gray-900 text-white flex flex-col">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center font-bold text-sm">S</div>
            <div>
                <p class="font-semibold text-sm">Lab System</p>
                <p class="text-gray-400 text-xs">Super Admin</p>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1">
            <x-admin-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <x-slot name="icon">📊</x-slot> Dashboard
            </x-admin-nav-link>
            <x-admin-nav-link :href="route('admin.labs.index')" :active="request()->routeIs('admin.labs*')">
                <x-slot name="icon">🏥</x-slot> Manage Labs
            </x-admin-nav-link>
        </nav>

        <div class="border-t border-gray-700 px-4 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">Super Admin</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-gray-400 hover:text-white text-xs transition" title="Logout">⏻</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white dark:bg-gray-800 shadow-sm px-6 py-3 flex items-center justify-between">
            <h1 class="text-gray-800 dark:text-white font-semibold text-lg">{{ $title ?? 'Admin' }}</h1>
            <span class="text-xs text-gray-500">{{ now()->format('D, d M Y') }}</span>
        </header>

        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded text-sm mb-2">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
