<div>
    <div class="mb-6 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
        <div class="grid gap-3 sm:grid-cols-2 xl:flex xl:flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Search test name or code..." class="w-full rounded-lg border px-4 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:min-w-80 xl:w-80">
            <select wire:model.live="category_id" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:w-auto sm:min-w-44">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="openCreate" class="app-btn-primary rounded-lg px-4 py-2 text-sm transition sm:w-fit">+ Add Test</button>
    </div>

    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-xl bg-white p-6 shadow-xl dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ $editingId ? 'Edit Test' : 'Add New Test' }}</h3>
            <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Test Name *</label>
                    <input wire:model="name" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Short Name</label>
                    <input wire:model="short_name" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Code</label>
                    <input wire:model="code" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Price (Rs.) *</label>
                    <input wire:model="price" type="number" step="0.01" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Unit</label>
                    <input wire:model="unit" type="text" placeholder="mg/dL, g/L..." class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Normal Range</label>
                    <input wire:model="normal_range" type="text" placeholder="70-100 or Negative" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Normal Range (Male)</label>
                    <input wire:model="normal_range_male" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Normal Range (Female)</label>
                    <input wire:model="normal_range_female" type="text" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Sample Type</label>
                    <input wire:model="sample_type" type="text" placeholder="Blood, Urine, Stool..." class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Turnaround (hours)</label>
                    <input wire:model="turnaround_hours" type="number" min="1" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select wire:model="form_category_id" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3 sm:col-span-2">
                    <button type="button" wire:click="$set('showForm', false)" class="rounded-lg border px-5 py-2 text-sm text-gray-600">Cancel</button>
                    <button type="submit" class="app-btn-primary rounded-lg px-6 py-2 text-sm font-medium">
                        <span wire:loading.remove>{{ $editingId ? 'Update' : 'Save' }} Test</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-[1100px] w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
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
                        <td class="px-6 py-3 whitespace-nowrap font-mono text-xs text-gray-500">{{ $test->code ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $test->category?->name ?? '-' }}</td>
                        <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-700 dark:text-gray-200">Rs. {{ number_format($test->price) }}</td>
                        <td class="px-6 py-3 text-xs text-gray-500">
                            {{ $test->normal_range ?? '-' }}
                            @if($test->unit) <span class="text-gray-400">({{ $test->unit }})</span> @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $test->sample_type ?? '-' }}</td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <button wire:click="toggleActive({{ $test->id }})">
                                <x-status-badge type="signal" :status="$test->is_active ? 'active' : 'inactive'" />
                            </button>
                        </td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <button wire:click="edit({{ $test->id }})" class="app-link-primary text-xs">Edit</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No tests found. Add one above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t px-6 py-4 dark:border-gray-700">{{ $tests->links() }}</div>
    </div>
</div>
