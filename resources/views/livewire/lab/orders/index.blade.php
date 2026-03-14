<div>
    <div class="mb-6 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
        <div class="grid gap-3 sm:grid-cols-2 xl:flex xl:flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Order # or patient name..." class="w-full rounded-lg border px-4 py-2 text-sm sm:min-w-80 xl:w-80">
            <select wire:model.live="status" class="w-full rounded-lg border px-3 py-2 text-sm sm:w-auto sm:min-w-48">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="sample_collected">Sample Collected</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <input wire:model.live="date" type="date" class="w-full rounded-lg border px-3 py-2 text-sm sm:w-auto sm:min-w-48">
        </div>
        <a href="{{ route('lab.orders.create') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm text-white transition hover:bg-blue-700 sm:w-fit">New Order</a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-[1100px] w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Order</th>
                        <th class="px-6 py-3 text-left">Patient</th>
                        <th class="px-6 py-3 text-left">Tests</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                        <th class="px-6 py-3 text-left">Payment</th>
                        <th class="px-6 py-3 text-left">Order Status</th>
                        <th class="px-6 py-3 text-left">Release</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 align-top">
                                <a href="{{ route('lab.orders.show', $order) }}" wire:navigate class="font-medium text-blue-600 hover:underline">{{ $order->order_number }}</a>
                                @if($order->is_urgent)
                                    <div class="text-xs font-semibold text-red-600">URGENT</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-medium text-gray-800">{{ $order->patient->name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->patient->phone ?: 'No phone' }}</div>
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap text-gray-500">{{ $order->items->count() }}</td>
                            <td class="px-6 py-4 align-top whitespace-nowrap font-medium text-gray-700">Rs. {{ number_format($order->net_amount) }}</td>
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                @if($order->invoice)
                                    @php($paymentColors = ['paid' => 'green', 'partial' => 'yellow', 'unpaid' => 'red'])
                                    @php($paymentColor = $paymentColors[$order->invoice->payment_status] ?? 'gray')
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs bg-{{ $paymentColor }}-100 text-{{ $paymentColor }}-700">{{ ucfirst($order->invoice->payment_status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                @php($orderColors = ['pending' => 'yellow', 'sample_collected' => 'blue', 'processing' => 'indigo', 'completed' => 'green', 'cancelled' => 'red'])
                                @php($orderColor = $orderColors[$order->status] ?? 'gray')
                                <span class="inline-flex rounded-full px-2 py-1 text-xs bg-{{ $orderColor }}-100 text-{{ $orderColor }}-700">{{ \App\Models\Order::STATUSES[$order->status] }}</span>
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                @if($order->canPrintReport())
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Released</span>
                                @elseif($order->canReleaseReport())
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-700">Ready</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">In progress</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 align-top whitespace-nowrap">
                                <a href="{{ route('lab.orders.show', $order) }}" wire:navigate class="text-xs text-blue-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-8 text-center text-gray-400">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t px-6 py-4">{{ $orders->links() }}</div>
    </div>
</div>
