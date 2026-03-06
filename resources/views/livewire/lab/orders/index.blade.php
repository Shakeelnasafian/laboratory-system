<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-3 flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Order # or patient name..." class="border rounded-lg px-4 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white w-64">
            <select wire:model.live="status" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="sample_collected">Sample Collected</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <input wire:model.live="date" type="date" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <a href="{{ route('lab.orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">+ New Order</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Order #</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Tests</th>
                    <th class="px-6 py-3 text-left">Amount</th>
                    <th class="px-6 py-3 text-left">Payment</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3">
                        <a href="{{ route('lab.orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">{{ $order->order_number }}</a>
                        @if($order->is_urgent) <span class="text-red-500 text-xs font-bold">URGENT</span> @endif
                    </td>
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $order->patient->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->patient->phone ?? '' }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $order->items->count() }}</td>
                    <td class="px-6 py-3 font-medium text-gray-700 dark:text-gray-200">Rs. {{ number_format($order->net_amount) }}</td>
                    <td class="px-6 py-3">
                        @if($order->invoice)
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $order->invoice->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->invoice->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($order->invoice->payment_status) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        @php
                            $colors = ['pending' => 'yellow', 'sample_collected' => 'blue', 'processing' => 'indigo', 'completed' => 'green', 'cancelled' => 'red'];
                            $c = $colors[$order->status] ?? 'gray';
                        @endphp
                        <select wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                            class="border rounded px-2 py-1 text-xs bg-{{ $c }}-50 border-{{ $c }}-200 text-{{ $c }}-700 dark:bg-gray-700 dark:border-gray-600">
                            @foreach(\App\Models\Order::STATUSES as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('lab.orders.show', $order) }}" class="text-blue-600 hover:underline text-xs">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $orders->links() }}</div>
    </div>
</div>
