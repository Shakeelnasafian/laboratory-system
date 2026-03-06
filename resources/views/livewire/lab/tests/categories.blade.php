<div>
    <div class="flex justify-end mb-4">
        <button wire:click="openCreate" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">+ Add Category</button>
    </div>

    @if($showForm)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <form wire:submit="save" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
                <input wire:model="name" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <input wire:model="description" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                {{ $editingId ? 'Update' : 'Save' }}
            </button>
            <button type="button" wire:click="$set('showForm', false)" class="border px-4 py-2 rounded-lg text-sm text-gray-600">Cancel</button>
        </form>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-left">Tests</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $cat->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $cat->description ?? '—' }}</td>
                    <td class="px-6 py-3"><span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $cat->tests_count }}</span></td>
                    <td class="px-6 py-3">
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $cat->id }})" class="text-blue-600 hover:underline text-xs">Edit</button>
                            <button wire:click="delete({{ $cat->id }})" wire:confirm="Delete this category?" class="text-red-500 hover:underline text-xs">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No categories yet. Add one above.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
