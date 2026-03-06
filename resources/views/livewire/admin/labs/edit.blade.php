<div class="max-w-3xl mx-auto">
    <form wire:submit="updateLab" class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Edit Lab</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lab Name *</label>
                    <input wire:model="name" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Owner Name</label>
                    <input wire:model="owner_name" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input wire:model="email" type="email" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input wire:model="phone" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                    <input wire:model="city" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">License Number</label>
                    <input wire:model="license_number" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <input wire:model="address" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Report Header</label>
                    <input wire:model="header_text" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Report Footer</label>
                    <input wire:model="footer_text" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="sm:col-span-2 flex items-center gap-2">
                    <input wire:model="is_active" type="checkbox" id="is_active" class="rounded">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Lab is Active</label>
                </div>
            </div>
        </div>
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.labs.index') }}" class="border px-5 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100">Cancel</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                <span wire:loading.remove>Update Lab</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
