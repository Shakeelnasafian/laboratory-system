@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="{{ $active
       ? 'bg-blue-800 text-white'
       : 'text-blue-200 hover:bg-blue-800 hover:text-white'
   }} flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
    @isset($icon)
        <span class="w-5 text-center">{{ $icon }}</span>
    @endisset
    <span>{{ $slot }}</span>
</a>
