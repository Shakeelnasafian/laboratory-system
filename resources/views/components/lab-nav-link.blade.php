@props(['href', 'active' => false])

<a href="{{ $href }}" wire:navigate
   wire:current="bg-blue-800 text-white"
   :title="$store.labSidebar.mini && $store.labSidebar.isDesktop ? @js(trim((string) $slot)) : ''"
   @click="if (!$store.labSidebar.isDesktop) $store.labSidebar.closeMenu()"
   class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-blue-200 transition-colors duration-150 hover:bg-blue-800 hover:text-white"
   :class="$store.labSidebar.mini && $store.labSidebar.isDesktop ? 'justify-center' : 'gap-3'">
    @isset($icon)
        <span class="flex h-5 w-5 shrink-0 items-center justify-center">{{ $icon }}</span>
    @endisset
    <span x-show="!$store.labSidebar.mini || !$store.labSidebar.isDesktop" x-transition.opacity.duration.150ms>{{ $slot }}</span>
</a>
