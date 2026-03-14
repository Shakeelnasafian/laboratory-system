<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ auth()->user()->lab->name ?? 'Lab' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 font-sans antialiased" x-data>
<div class="relative flex h-full overflow-hidden">
    <div
        x-cloak
        x-show="$store.labSidebar.open && !$store.labSidebar.isDesktop"
        x-transition.opacity
        class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden"
        @click="$store.labSidebar.closeMenu()"
    ></div>

    @persist('lab-sidebar')
    <aside
        class="fixed inset-y-0 left-0 z-40 flex flex-col bg-blue-900 text-white shadow-2xl transition-all duration-300 ease-out lg:static lg:translate-x-0 lg:shadow-none"
        :class="[
            $store.labSidebar.open || $store.labSidebar.isDesktop ? 'translate-x-0' : '-translate-x-full',
            $store.labSidebar.mini && $store.labSidebar.isDesktop ? 'w-[5.5rem]' : 'w-72'
        ]"
    >
        <div
            class="flex items-center border-b border-blue-800"
            :class="$store.labSidebar.mini && $store.labSidebar.isDesktop ? 'justify-center px-3 py-5' : 'gap-3 px-6 py-5'"
        >
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-400 text-base font-bold">
                {{ strtoupper(substr(auth()->user()->lab->name ?? 'L', 0, 1)) }}
            </div>

            <div class="min-w-0 text-sm" x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop" x-transition.opacity.duration.150ms>
                <p class="truncate font-semibold leading-tight">{{ auth()->user()->lab->name ?? 'Lab' }}</p>
                <p class="truncate text-xs capitalize text-blue-300">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between border-b border-blue-800 px-3 py-2" x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-300">Navigation</p>
            <button
                type="button"
                class="rounded-lg border border-blue-700 px-2 py-1 text-xs text-blue-200 transition hover:bg-blue-800 hover:text-white lg:hidden"
                @click="$store.labSidebar.closeMenu()"
            >
                Close
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <x-lab-nav-link :href="route('lab.dashboard')" :active="request()->routeIs('lab.dashboard')">
                <x-slot name="icon"><x-sidebar-icon name="dashboard" /></x-slot> Dashboard
            </x-lab-nav-link>

            <p
                class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-blue-400"
                x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop"
                x-transition.opacity.duration.150ms
            >
                Patients
            </p>
            <x-lab-nav-link :href="route('lab.patients.index')" :active="request()->routeIs('lab.patients*')">
                <x-slot name="icon"><x-sidebar-icon name="patients" /></x-slot> Patients
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.orders.create')" :active="request()->routeIs('lab.orders.create')">
                <x-slot name="icon"><x-sidebar-icon name="new-order" /></x-slot> New Order
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.orders.index')" :active="request()->routeIs('lab.orders.index') || request()->routeIs('lab.orders.show')">
                <x-slot name="icon"><x-sidebar-icon name="orders" /></x-slot> Orders
            </x-lab-nav-link>

            <p
                class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-blue-400"
                x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop"
                x-transition.opacity.duration.150ms
            >
                Lab Work
            </p>
            <x-lab-nav-link :href="route('lab.samples.collection')" :active="request()->routeIs('lab.samples.*')">
                <x-slot name="icon"><x-sidebar-icon name="samples" /></x-slot> Samples
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.worklists.index')" :active="request()->routeIs('lab.worklists.*')">
                <x-slot name="icon"><x-sidebar-icon name="worklists" /></x-slot> Worklists
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.results.index')" :active="request()->routeIs('lab.results.index')">
                <x-slot name="icon"><x-sidebar-icon name="results" /></x-slot> Results
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.results.release')" :active="request()->routeIs('lab.results.release')">
                <x-slot name="icon"><x-sidebar-icon name="release" /></x-slot> Result Release
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.invoices.index')" :active="request()->routeIs('lab.invoices*')">
                <x-slot name="icon"><x-sidebar-icon name="billing" /></x-slot> Billing
            </x-lab-nav-link>
            <x-lab-nav-link :href="route('lab.changelog')" :active="request()->routeIs('lab.changelog')">
                <x-slot name="icon"><x-sidebar-icon name="changelog" /></x-slot> Change Log
            </x-lab-nav-link>

            @role('lab_admin')
                <p
                    class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-wider text-blue-400"
                    x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop"
                    x-transition.opacity.duration.150ms
                >
                    Setup
                </p>
                <x-lab-nav-link :href="route('lab.test-categories.index')" :active="request()->routeIs('lab.test-categories*')">
                    <x-slot name="icon"><x-sidebar-icon name="categories" /></x-slot> Test Categories
                </x-lab-nav-link>
                <x-lab-nav-link :href="route('lab.tests.index')" :active="request()->routeIs('lab.tests*')">
                    <x-slot name="icon"><x-sidebar-icon name="catalog" /></x-slot> Test Catalog
                </x-lab-nav-link>
                <x-lab-nav-link :href="route('lab.users.index')" :active="request()->routeIs('lab.users*')">
                    <x-slot name="icon"><x-sidebar-icon name="users" /></x-slot> Staff Users
                </x-lab-nav-link>
                <x-lab-nav-link :href="route('lab.settings')" :active="request()->routeIs('lab.settings')">
                    <x-slot name="icon"><x-sidebar-icon name="settings" /></x-slot> Settings
                </x-lab-nav-link>
            @endrole
        </nav>

        <div class="border-t border-blue-800 px-4 py-3">
            <div class="flex items-center gap-3" :class="$store.labSidebar.mini && $store.labSidebar.isDesktop ? 'justify-center' : 'justify-between'">
                <div class="min-w-0" x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop" x-transition.opacity.duration.150ms>
                    <p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-blue-300">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-xs text-blue-300 transition hover:text-white" title="Logout">Logout</button>
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
                        @click="$store.labSidebar.openMenu()"
                    >
                        <span class="sr-only">Open menu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        class="hidden h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-100 lg:inline-flex"
                        @click="$store.labSidebar.toggleMini()"
                    >
                        <span class="sr-only">Toggle sidebar width</span>
                        <svg x-show="!$store.labSidebar.mini" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
                        </svg>
                        <svg x-show="$store.labSidebar.mini" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg font-semibold text-gray-800 sm:text-2xl">{{ $title ?? 'Dashboard' }}</h1>
                        <p class="text-xs text-gray-500 sm:hidden">{{ auth()->user()->lab->name ?? 'Lab' }}</p>
                    </div>
                </div>

                <div class="text-xs text-gray-500 sm:text-sm">{{ now()->format('D, d M Y') }}</div>
            </div>
        </header>

        <div class="px-4 pt-4 sm:px-6">
            @if(session('success'))
                <div class="mb-2 rounded-xl border border-green-300 bg-green-100 px-4 py-2 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-2 rounded-xl border border-red-300 bg-red-100 px-4 py-2 text-sm text-red-800">{{ session('error') }}</div>
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
