<div>
    <div class="mb-6 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
        <div class="grid gap-3 sm:grid-cols-2 xl:flex xl:flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Search order or patient..." class="w-full rounded-lg border px-4 py-2 text-sm sm:min-w-80 xl:w-80">
            <select wire:model.live="queue" class="w-full rounded-lg border px-3 py-2 text-sm sm:w-auto sm:min-w-40">
                <option value="unassigned">Unassigned</option>
                <option value="mine">Mine</option>
                <option value="urgent">Urgent</option>
                <option value="overdue">Overdue</option>
            </select>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <button wire:click="assignSelectedToMe" class="rounded-lg border px-4 py-2 text-sm">Assign Selected</button>
            <button wire:click="startSelectedProcessing" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Start Selected</button>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-[1340px] w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Pick</th>
                        <th class="px-6 py-3 text-left">Order</th>
                        <th class="px-6 py-3 text-left">Patient / Test</th>
                        <th class="px-6 py-3 text-left">Accession</th>
                        <th class="px-6 py-3 text-left">Assigned</th>
                        <th class="px-6 py-3 text-left">Due</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Notes</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        <tr>
                            <td class="px-4 py-4 align-top">
                                <input type="checkbox" value="{{ $item->id }}" wire:model="selectedItems" class="mt-1 rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4 align-top">
                                <a href="{{ route('lab.orders.show', $item->order) }}" wire:navigate class="font-medium text-blue-600 hover:underline">{{ $item->order->order_number }}</a>
                                @if($item->order->is_urgent)
                                    <div class="mt-1 text-xs font-semibold text-red-600">URGENT</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-medium text-gray-800">{{ $item->order->patient->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->test->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->test->category?->name ?: 'Uncategorized' }}</div>
                            </td>
                            <td class="px-6 py-4 align-top font-mono text-gray-700">{{ $item->sample?->accession_number ?: 'Pending' }}</td>
                            <td class="px-6 py-4 align-top">
                                <div class="whitespace-nowrap text-sm text-gray-700">{{ $item->assignedTo?->name ?: 'Unassigned' }}</div>
                                @if($item->started_at)
                                    <div class="whitespace-nowrap text-xs text-gray-500">Started {{ $item->started_at->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="whitespace-nowrap text-sm {{ $item->isOverdue() ? 'font-medium text-red-600' : 'text-gray-700' }}">{{ optional($item->due_at)->format('d M h:i A') ?: 'Not set' }}</div>
                                @if($item->isOverdue())
                                    <div class="text-xs text-red-500">Overdue</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                @php($colors = ['sample_collected' => 'blue', 'processing' => 'indigo', 'completed' => 'green', 'pending' => 'yellow'])
                                @php($color = $colors[$item->status] ?? 'gray')
                                <span class="inline-flex rounded-full px-2 py-1 text-xs bg-{{ $color }}-100 text-{{ $color }}-700">{{ str_replace('_', ' ', ucfirst($item->status)) }}</span>
                            </td>
                            <td class="w-[260px] min-w-[260px] px-6 py-4 align-top">
                                <textarea wire:model.defer="notes.{{ $item->id }}" rows="2" class="w-full rounded-lg border px-3 py-2 text-sm" placeholder="Bench notes..."></textarea>
                                <button wire:click="saveNotes({{ $item->id }})" class="mt-2 text-xs text-blue-600 hover:underline">Save note</button>
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap text-right">
                                <div class="flex flex-col items-end gap-2">
                                    <button wire:click="assignToMe({{ $item->id }})" class="rounded-lg border px-3 py-2 text-sm">Assign to me</button>
                                    @if($item->status !== 'processing')
                                        <button wire:click="startProcessing({{ $item->id }})" class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700">Start</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-400">No worklist items match the current queue.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t px-6 py-4">{{ $items->links() }}</div>
    </div>
</div>
