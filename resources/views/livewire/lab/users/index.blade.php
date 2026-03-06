<div>
    <div class="flex justify-end mb-4">
        <button wire:click="openCreate" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">+ Add Staff</button>
    </div>

    @if($showForm)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ $editingId ? 'Edit' : 'Add' }} Staff User</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                    <input wire:model="name" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                    <input wire:model="email" type="email" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input wire:model="phone" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password {{ $editingId ? '(leave blank to keep)' : '*' }}</label>
                    <input wire:model="password" type="password" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role *</label>
                    <select wire:model="role" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="receptionist">Receptionist</option>
                        <option value="technician">Technician</option>
                        <option value="lab_incharge">Lab Incharge</option>
                        <option value="lab_admin">Lab Admin</option>
                    </select>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="$set('showForm', false)" class="border px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                        {{ $editingId ? 'Update' : 'Create' }} User
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Phone</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $user->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $user->phone ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 capitalize">
                            {{ str_replace('_', ' ', $user->roles->first()?->name ?? '—') }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:underline text-xs">Edit</button>
                            <button wire:click="toggleActive({{ $user->id }})"
                                class="text-xs {{ $user->is_active ? 'text-red-500' : 'text-green-500' }} hover:underline">
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No staff users. Add one above.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
