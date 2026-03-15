<div>
    <div class="mb-4 flex justify-end">
        <button wire:click="openCreate" class="app-btn-primary rounded-lg px-4 py-2 text-sm transition">+ Add Staff</button>
    </div>

    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ $editingId ? 'Edit' : 'Add' }} Staff User</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                    <input wire:model="name" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                    <input wire:model="email" type="email" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input wire:model="phone" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Password {{ $editingId ? '(leave blank to keep)' : '*' }}</label>
                    <input wire:model="password" type="password" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Role *</label>
                    <select wire:model="role" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="receptionist">Receptionist</option>
                        <option value="technician">Technician</option>
                        <option value="lab_incharge">Lab Incharge</option>
                        <option value="lab_admin">Lab Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showForm', false)" class="rounded-lg border px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                    <button type="submit" class="app-btn-primary rounded-lg px-6 py-2 text-sm font-medium">
                        {{ $editingId ? 'Update' : 'Create' }} User
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-[860px] w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
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
                        <td class="px-6 py-3 text-gray-500">{{ $user->phone ?? '-' }}</td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <x-status-badge type="role" :status="$user->roles->first()?->name ?? 'receptionist'" :label="str_replace('_', ' ', $user->roles->first()?->name ?? '-')" class="capitalize" />
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <x-status-badge type="signal" :status="$user->is_active ? 'active' : 'inactive'" />
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $user->id }})" class="app-link-primary text-xs">Edit</button>
                                <button wire:click="toggleActive({{ $user->id }})" class="text-xs {{ $user->is_active ? 'text-red-500' : 'text-green-500' }} hover:underline">
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
</div>
