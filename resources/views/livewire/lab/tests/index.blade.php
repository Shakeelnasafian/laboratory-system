<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-3">
            <input wire:model.live="search" type="text" placeholder="Search test name or code..." class="border rounded-lg px-4 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white w-64">
            <select wire:model.live="category_id" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="openCreate" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">+ Add Test</button>
    </div>

    {{-- Test Form Modal --}}
    @if($showForm)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ $editingId ? 'Edit Test' : 'Add New Test' }}</h3>
            <form wire:submit="save" class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Name *</label>
                    <input wire:model="name" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Short Name</label>
                    <input wire:model="short_name" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code</label>
                    <input wire:model="code" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price (Rs.) *</label>
                    <input wire:model="price" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit</label>
                    <input wire:model="unit" type="text" placeholder="mg/dL, g/L..." class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Normal Range</label>
                    <input wire:model="normal_range" type="text" placeholder="70-100 or Negative" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Normal Range (Male)</label>
                    <input wire:model="normal_range_male" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Normal Range (Female)</label>
                    <input wire:model="normal_range_female" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sample Type</label>
                    <input wire:model="sample_type" type="text" placeholder="Blood, Urine, Stool..." class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Turnaround (hours)</label>
                    <input wire:model="turnaround_hours" type="number" min="1" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                    <select wire:model="form_category_id" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 flex gap-3 justify-end">
                    <button type="button" wire:click="$set('showForm', false)" class="border px-5 py-2 rounded-lg text-sm text-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                        <span wire:loading.remove>{{ $editingId ? 'Update' : 'Save' }} Test</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Tests Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Test Name</th>
                    <th class="px-6 py-3 text-left">Code</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-left">Price</th>
                    <th class="px-6 py-3 text-left">Normal Range</th>
                    <th class="px-6 py-3 text-left">Sample</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($tests as $test)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $test->name }}</p>
                        @if($test->short_name) <p class="text-xs text-gray-400">{{ $test->short_name }}</p> @endif
                    </td>
                    <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $test->code ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $test->category?->name ?? '—' }}</td>
                    <td class="px-6 py-3 font-medium text-gray-700 dark:text-gray-200">Rs. {{ number_format($test->price) }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">
                        {{ $test->normal_range ?? '—' }}
                        @if($test->unit) <span class="text-gray-400">({{ $test->unit }})</span> @endif
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $test->sample_type ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <button wire:click="toggleActive({{ $test->id }})"
                            class="px-2 py-0.5 rounded-full text-xs {{ $test->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $test->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="px-6 py-3">
                        <button wire:click="edit({{ $test->id }})" class="text-blue-600 hover:underline text-xs">Edit</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No tests found. Add one above.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $tests->links() }}</div>
    </div>
