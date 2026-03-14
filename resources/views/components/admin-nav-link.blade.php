@props(['href', 'active' => false])

<a href="{{ $href }}" wire:navigate
   wire:current="bg-gray-700 text-white"
   :title="$store.adminSidebar.mini && $store.adminSidebar.isDesktop ? @js(trim((string) $slot)) : ''"
   @click="if (!$store.adminSidebar.isDesktop) $store.adminSidebar.closeMenu()"
   class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-300 transition-colors duration-150 hover:bg-gray-700 hover:text-white"
   :class="$store.adminSidebar.mini && $store.adminSidebar.isDesktop ? 'justify-center' : 'gap-3'">
    @isset($icon)
        <span class="flex h-5 w-5 shrink-0 items-center justify-center">{{ $icon }}</span>
    @endisset
    <span x-show="!$store.adminSidebar.mini || !$store.adminSidebar.isDesktop" x-transition.opacity.duration.150ms>{{ $slot }}</span>
</a>
