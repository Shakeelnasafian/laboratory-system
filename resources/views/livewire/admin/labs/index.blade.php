<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-3">
            <input wire:model.live="search" type="text" placeholder="Search labs..." class="border rounded-lg px-4 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white w-64">
            <select wire:model.live="status" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <a href="{{ route('admin.labs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">+ New Lab</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Lab</th>
                    <th class="px-6 py-3 text-left">Contact</th>
                    <th class="px-6 py-3 text-left">City</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($labs as $lab)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $lab->name }}</p>
                        <p class="text-xs text-gray-400">{{ $lab->owner_name }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        <p>{{ $lab->email ?? '—' }}</p>
                        <p>{{ $lab->phone ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $lab->city ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs {{ $lab->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $lab->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.labs.edit', $lab) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                            <button wire:click="toggleStatus({{ $lab->id }})"
                                wire:confirm="Toggle this lab's status?"
                                class="text-xs {{ $lab->is_active ? 'text-red-500' : 'text-green-500' }} hover:underline">
                                {{ $lab->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No labs found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $labs->links() }}</div>
    </div>
</div>
