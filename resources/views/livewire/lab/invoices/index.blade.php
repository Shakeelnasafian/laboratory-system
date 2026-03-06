<div>
    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Today's Billing</p>
            <p class="text-xl font-bold text-gray-800 dark:text-white">Rs. {{ number_format($todayTotal) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Collected Today</p>
            <p class="text-xl font-bold text-green-600">Rs. {{ number_format($todayCollected) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500">Outstanding</p>
            <p class="text-xl font-bold text-red-500">Rs. {{ number_format($outstanding) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-3">
            <input wire:model.live="search" type="text" placeholder="Search invoice # or patient..."
                   class="border rounded-lg px-4 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white w-64">
            <select wire:model.live="payment_status" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All</option>
                <option value="paid">Paid</option>
                <option value="partial">Partial</option>
                <option value="unpaid">Unpaid</option>
            </select>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Invoice #</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Order</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3 text-right">Paid</th>
                    <th class="px-6 py-3 text-right">Balance</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $inv->invoice_number }}</td>
                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $inv->order->patient->name ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('lab.orders.show', $inv->order_id) }}" class="text-blue-600 hover:underline text-xs">{{ $inv->order->order_number }}</a>
                    </td>
                    <td class="px-6 py-3 text-right text-gray-700 dark:text-gray-200">Rs. {{ number_format($inv->total) }}</td>
                    <td class="px-6 py-3 text-right text-green-600">Rs. {{ number_format($inv->paid_amount) }}</td>
                    <td class="px-6 py-3 text-right {{ $inv->balance > 0 ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                        Rs. {{ number_format($inv->balance) }}
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $inv->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($inv->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($inv->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $inv->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        @if($inv->payment_status !== 'paid')
                            <button wire:click="markPaid({{ $inv->id }})" wire:confirm="Mark this invoice as fully paid?"
                                class="text-green-600 hover:underline text-xs">Mark Paid</button>
                        @else
                            <span class="text-gray-400 text-xs">Done</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-8 text-center text-gray-400">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $invoices->links() }}</div>
    </div>
</div>
