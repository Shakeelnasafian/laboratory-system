<div class="max-w-4xl mx-auto space-y-6">

    {{-- Order Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Order #{{ $order->order_number }}
                    @if($order->is_urgent) <span class="text-red-500 text-sm font-bold ml-2">URGENT</span> @endif
                </h2>
                <p class="text-sm text-gray-500 mt-1">Placed {{ $order->created_at->format('d M Y, h:i A') }} by {{ $order->createdBy->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('lab.orders.report', $order) }}" target="_blank"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-1">
                    🖨️ Print Report
                </a>
                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                    @php $next = match($order->status) { 'pending' => 'sample_collected', 'sample_collected' => 'processing', 'processing' => 'completed', default => null }; @endphp
                    @if($next)
                        <button wire:click="updateStatus('{{ $next }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                            Mark: {{ \App\Models\Order::STATUSES[$next] }}
                        </button>
                    @endif
                @endif
            </div>
        </div>

        {{-- Status Badge --}}
        @php
            $colors = ['pending' => 'yellow', 'sample_collected' => 'blue', 'processing' => 'indigo', 'completed' => 'green', 'cancelled' => 'red'];
            $c = $colors[$order->status] ?? 'gray';
        @endphp
        <div class="mt-4">
            <span class="px-3 py-1 rounded-full text-sm bg-{{ $c }}-100 text-{{ $c }}-700 font-medium">
                {{ \App\Models\Order::STATUSES[$order->status] }}
            </span>
        </div>
    </div>

    {{-- Patient Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Patient Information</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Name</p>
                <p class="font-medium text-gray-800 dark:text-white">{{ $order->patient->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">ID</p>
                <p class="font-mono text-gray-700 dark:text-gray-300">{{ $order->patient->patient_id }}</p>
            </div>
            <div>
                <p class="text-gray-500">Age / Gender</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $order->patient->age ?? '—' }} {{ $order->patient->age_unit }} / {{ ucfirst($order->patient->gender) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Phone</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $order->patient->phone ?? '—' }}</p>
            </div>
            @if($order->referred_by)
            <div>
                <p class="text-gray-500">Referred By</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $order->referred_by }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Test Items --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-white">Tests & Results</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Test</th>
                    <th class="px-6 py-3 text-left">Result</th>
                    <th class="px-6 py-3 text-left">Normal Range</th>
                    <th class="px-6 py-3 text-left">Flag</th>
                    <th class="px-6 py-3 text-right">Price</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $item->test->name }}</td>
                    <td class="px-6 py-3">
                        @if($item->result)
                            <span class="{{ $item->result->is_abnormal ? 'text-red-600 font-bold' : 'text-gray-700 dark:text-gray-200' }}">
                                {{ $item->result->value }} {{ $item->result->unit }}
                            </span>
                            @if($item->result->is_verified) <span class="text-green-500 text-xs ml-1">Verified</span> @endif
                        @else
                            <span class="text-gray-400">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $item->test->normal_range ?? '—' }} {{ $item->test->unit ? '('.$item->test->unit.')' : '' }}</td>
                    <td class="px-6 py-3">
                        @if($item->result)
                            @php $flagColors = ['normal' => 'green', 'high' => 'red', 'low' => 'blue', 'critical' => 'red']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $flagColors[$item->result->flag] ?? 'gray' }}-100 text-{{ $flagColors[$item->result->flag] ?? 'gray' }}-700">
                                {{ ucfirst($item->result->flag) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-right text-gray-700 dark:text-gray-200">Rs. {{ number_format($item->price) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium">
                <tr>
                    <td colspan="4" class="px-6 py-2 text-right text-gray-600 dark:text-gray-300">Subtotal:</td>
                    <td class="px-6 py-2 text-right text-gray-800 dark:text-white">Rs. {{ number_format($order->total_amount) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td colspan="4" class="px-6 py-2 text-right text-gray-600 dark:text-gray-300">Discount:</td>
                    <td class="px-6 py-2 text-right text-red-500">-Rs. {{ number_format($order->discount) }}</td>
                </tr>
                @endif
                <tr class="text-lg">
                    <td colspan="4" class="px-6 py-2 text-right font-bold text-gray-800 dark:text-white">Net Total:</td>
                    <td class="px-6 py-2 text-right font-bold text-green-600">Rs. {{ number_format($order->net_amount) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Payment Info --}}
    @if($order->invoice)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Payment</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Invoice #</p>
                <p class="font-mono text-gray-700 dark:text-gray-300">{{ $order->invoice->invoice_number }}</p>
            </div>
            <div>
                <p class="text-gray-500">Total</p>
                <p class="font-medium text-gray-800 dark:text-white">Rs. {{ number_format($order->invoice->total) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Paid</p>
                <p class="font-medium text-green-600">Rs. {{ number_format($order->invoice->paid_amount) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Balance</p>
                <p class="font-medium {{ $order->invoice->balance > 0 ? 'text-red-600' : 'text-gray-500' }}">Rs. {{ number_format($order->invoice->balance) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status</p>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $order->invoice->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->invoice->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($order->invoice->payment_status) }}
                </span>
            </div>
        </div>
    </div>
    @endif

    <div class="flex justify-start">
        <a href="{{ route('lab.orders.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Back to Orders</a>
    </div>
</div>
