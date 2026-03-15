<aside
    class="fixed inset-y-0 left-0 z-40 flex h-dvh max-h-dvh min-h-0 flex-col overflow-hidden bg-gray-900 text-white shadow-2xl transition-all duration-300 ease-out lg:static lg:h-full lg:max-h-full lg:translate-x-0 lg:shadow-none"
    :class="[
        $store.adminSidebar.open || $store.adminSidebar.isDesktop ? 'translate-x-0' : '-translate-x-full',
        $store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'w-[5.5rem]' : 'w-72'
    ]"
>
    <div
        class="border-b border-gray-800"
        :class="$store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'px-3 py-5' : 'px-6 py-5'"
    >
        <div class="flex items-start justify-between gap-3">
            <div
                class="flex min-w-0 items-center"
                :class="$store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'justify-center w-full' : 'gap-3'"
            >
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-500 text-base font-bold">S</div>
                <div x-show="!$store.adminSidebar.mini || !$store.adminSidebar.isDesktop" x-transition.opacity.duration.150ms>
                    <p class="text-sm font-semibold">Lab System</p>
                    <p class="text-xs text-gray-400">Super Admin</p>
                </div>
            </div>

            <button
                type="button"
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-gray-700 text-gray-200 transition hover:bg-gray-800 hover:text-white lg:hidden"
                x-show="!$store.adminSidebar.mini || !$store.adminSidebar.isDesktop"
                @click="$store.adminSidebar.closeMenu()"
            >
                <span class="sr-only">Close menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-y-contain px-3 py-4">
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

    <div class="shrink-0 border-t border-gray-800 px-4 py-3">
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
