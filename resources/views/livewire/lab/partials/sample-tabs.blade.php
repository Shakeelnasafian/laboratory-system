<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('lab.samples.collection') }}" wire:navigate class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('lab.samples.collection') ? 'app-tab-active' : 'bg-white border text-gray-700' }}">
        Collection Queue
    </a>
    <a href="{{ route('lab.samples.receive') }}" wire:navigate class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('lab.samples.receive') ? 'app-tab-active' : 'bg-white border text-gray-700' }}">
        Sample Receive
    </a>
    <a href="{{ route('lab.samples.rejected') }}" wire:navigate class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('lab.samples.rejected') ? 'app-tab-active' : 'bg-white border text-gray-700' }}">
        Recollect Queue
    </a>
</div>
