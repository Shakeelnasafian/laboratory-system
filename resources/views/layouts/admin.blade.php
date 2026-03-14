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

    @persist('admin-sidebar')
    <aside
        class="fixed inset-y-0 left-0 z-40 flex flex-col bg-gray-900 text-white shadow-2xl transition-all duration-300 ease-out lg:static lg:translate-x-0 lg:shadow-none"
        :class="[
            $store.adminSidebar.open || $store.adminSidebar.isDesktop ? 'translate-x-0' : '-translate-x-full',
            $store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'w-[5.5rem]' : 'w-72'
        ]"
    >
        <div
            class="flex items-center border-b border-gray-800"
            :class="$store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'justify-center px-3 py-5' : 'gap-3 px-6 py-5'"
        >
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500 text-base font-bold">S</div>
            <div x-show="!$store.adminSidebar.mini || !$store.adminSidebar.isDesktop" x-transition.opacity.duration.150ms>
                <p class="text-sm font-semibold">Lab System</p>
                <p class="text-xs text-gray-400">Super Admin</p>
            </div>
        </div>

        <div class="flex items-center justify-between border-b border-gray-800 px-3 py-2" x-show="!$store.adminSidebar.mini || !$store.adminSidebar.isDesktop">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Navigation</p>
            <button
                type="button"
                class="rounded-lg border border-gray-700 px-2 py-1 text-xs text-gray-300 transition hover:bg-gray-800 hover:text-white lg:hidden"
                @click="$store.adminSidebar.closeMenu()"
            >
                Close
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <x-admin-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <x-slot name="icon"><x-sidebar-icon name="dashboard" /></x-slot> Dashboard
            </x-admin-nav-link>
            <x-admin-nav-link :href="route('admin.labs.index')" :active="request()->routeIs('admin.labs*')">
                <x-slot name="icon"><x-sidebar-icon name="labs" /></x-slot> Manage Labs
            </x-admin-nav-link>
            <x-admin-nav-link :href="route('admin.changelog')" :active="request()->routeIs('admin.changelog')">
                <x-slot name="icon"><x-sidebar-icon name="changelog" /></x-slot> Change Log
            </x-admin-nav-link>
        </nav>

        <div class="border-t border-gray-800 px-4 py-3">
            <div class="flex items-center gap-3" :class="$store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'justify-center' : 'justify-between'">
                <div x-show="!$store.adminSidebar.mini || !$store.adminSidebar.isDesktop" x-transition.opacity.duration.150ms>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">Super Admin</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-xs text-gray-400 transition hover:text-white" title="Logout">Logout</button>
                </form>
            </div>
        </div>
    </aside>
    @endpersist

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
