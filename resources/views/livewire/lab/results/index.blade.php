<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-3">
            <input wire:model.live="search" type="text" placeholder="Search order # or patient..."
                   class="border rounded-lg px-4 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white w-64">
            <select wire:model.live="status" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Results</option>
                <option value="pending">Pending Only</option>
                <option value="completed">Completed Only</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Order #</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Test</th>
                    <th class="px-6 py-3 text-left">Normal Range</th>
                    <th class="px-6 py-3 text-left">Result</th>
                    <th class="px-6 py-3 text-left">Flag</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3">
                        <a href="{{ route('lab.orders.show', $item->order) }}" class="text-blue-600 hover:underline text-xs font-mono">{{ $item->order->order_number }}</a>
                    </td>
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $item->order->patient->name }}</p>
                        <p class="text-xs text-gray-400">{{ $item->order->patient->age ? $item->order->patient->age.' '.$item->order->patient->age_unit : '' }} {{ ucfirst($item->order->patient->gender) }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-700 dark:text-gray-200">{{ $item->test->name }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $item->test->normal_range ?? '—' }} {{ $item->test->unit ? '('.$item->test->unit.')' : '' }}</td>
                    <td class="px-6 py-3">
                        @if($item->result)
                            <span class="{{ $item->result->is_abnormal ? 'text-red-600 font-bold' : 'text-gray-800 dark:text-white' }}">
                                {{ $item->result->value }} {{ $item->result->unit }}
                            </span>
                            @if($item->result->is_verified) <span class="text-green-500 text-xs ml-1">Verified</span> @endif
                        @else
                            <span class="text-gray-400 italic">Not entered</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        @if($item->result)
                            @php $flagColors = ['normal' => 'green', 'high' => 'red', 'low' => 'blue', 'critical' => 'red']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $flagColors[$item->result->flag] ?? 'gray' }}-100 text-{{ $flagColors[$item->result->flag] ?? 'gray' }}-700">
                                {{ ucfirst($item->result->flag) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex gap-2">
                            <button wire:click="openResultEntry({{ $item->id }})" class="text-blue-600 hover:underline text-xs">
                                {{ $item->result ? 'Edit' : 'Enter' }} Result
                            </button>
                            @if($item->result && !$item->result->is_verified)
                                <button wire:click="verify({{ $item->id }})" class="text-green-600 hover:underline text-xs">Verify</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No test items found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $items->links() }}</div>
    </div>

    {{-- Result Entry Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Enter Test Result</h3>
            <form wire:submit="saveResult" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Result Value *</label>
                    <input wire:model="value" type="text" autofocus class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit</label>
                        <input wire:model="unit" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Flag</label>
                        <select wire:model="flag" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Normal Range</label>
                    <input wire:model="normal_range" type="text" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remarks</label>
                    <textarea wire:model="remarks" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="$set('showModal', false)" class="border px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">Save Result</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
