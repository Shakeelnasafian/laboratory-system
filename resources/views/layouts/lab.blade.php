<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ auth()->user()->lab->name ?? 'Lab' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 font-sans antialiased">

<div class="flex h-full" x-data="{ sidebarOpen: false }">

    {{-- ── Sidebar ─────────────────────────────────────────────── --}}
    <aside class="w-64 flex-shrink-0 bg-blue-900 text-white flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-800">
            <div class="w-8 h-8 bg-blue-400 rounded-lg flex items-center justify-center font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->lab->name ?? 'L', 0, 1)) }}
            </div>
            <div class="text-sm">
                <p class="font-semibold leading-tight">{{ auth()->user()->lab->name ?? 'Lab' }}</p>
                <p class="text-blue-300 text-xs capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <x-lab-nav-link :href="route('lab.dashboard')" :active="request()->routeIs('lab.dashboard')">
                <x-slot name="icon">📊</x-slot> Dashboard
            </x-lab-nav-link>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Patients</p>
            <x-lab-nav-link :href="route('lab.patients.index')" :active="request()->routeIs('lab.patients*')">
                <x-slot name="icon">👤</x-slot> Patients
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.orders.create')" :active="request()->routeIs('lab.orders.create')">
                <x-slot name="icon">➕</x-slot> New Order
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.orders.index')" :active="request()->routeIs('lab.orders.index')">
                <x-slot name="icon">📋</x-slot> Orders
            </x-lab-nav-link>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Lab Work</p>
            <x-lab-nav-link :href="route('lab.results.index')" :active="request()->routeIs('lab.results*')">
                <x-slot name="icon">🔬</x-slot> Results
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.invoices.index')" :active="request()->routeIs('lab.invoices*')">
                <x-slot name="icon">💰</x-slot> Billing
            </x-lab-nav-link>

            @role('lab_admin')
            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Setup</p>
            <x-lab-nav-link :href="route('lab.test-categories.index')" :active="request()->routeIs('lab.test-categories*')">
                <x-slot name="icon">🗂️</x-slot> Test Categories
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.tests.index')" :active="request()->routeIs('lab.tests*')">
                <x-slot name="icon">🧪</x-slot> Test Catalog
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.users.index')" :active="request()->routeIs('lab.users*')">
                <x-slot name="icon">👥</x-slot> Staff Users
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.settings')" :active="request()->routeIs('lab.settings')">
                <x-slot name="icon">⚙️</x-slot> Settings
            </x-lab-nav-link>
            @endrole
        </nav>

        {{-- Bottom user info --}}
        <div class="border-t border-blue-800 px-4 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-300">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-blue-300 hover:text-white text-xs transition" title="Logout">⏻</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white dark:bg-gray-800 shadow-sm px-6 py-3 flex items-center justify-between">
            <h1 class="text-gray-800 dark:text-white font-semibold text-lg">
                {{ $title ?? 'Dashboard' }}
            </h1>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ now()->format('D, d M Y') }}
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded text-sm mb-2">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded text-sm mb-2">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
