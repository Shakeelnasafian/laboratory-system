<div>
    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Today's Orders</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $todayOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Today's Patients</p>
            <p class="text-2xl font-bold text-blue-600">{{ $todayPatients }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Completed Today</p>
            <p class="text-2xl font-bold text-green-600">{{ $completedToday }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Today's Revenue</p>
            <p class="text-2xl font-bold text-emerald-600">Rs. {{ number_format($todayRevenue) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Patients</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalPatients }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex gap-3 mb-6">
        <a href="{{ route('lab.orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">➕ New Order</a>
        <a href="{{ route('lab.patients.create') }}" class="bg-white dark:bg-gray-800 border dark:border-gray-600 hover:bg-gray-50 text-gray-700 dark:text-gray-200 px-5 py-2 rounded-lg text-sm font-medium transition">👤 New Patient</a>
        <a href="{{ route('lab.results.index') }}" class="bg-white dark:bg-gray-800 border dark:border-gray-600 hover:bg-gray-50 text-gray-700 dark:text-gray-200 px-5 py-2 rounded-lg text-sm font-medium transition">🔬 Enter Results</a>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">Recent Orders</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Order #</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Tests</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3">
                        <a href="{{ route('lab.orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                            {{ $order->order_number }}
                            @if($order->is_urgent) <span class="text-red-500 text-xs">URGENT</span> @endif
                        </a>
                    </td>
                    <td class="px-6 py-3 text-gray-700 dark:text-gray-300">{{ $order->patient->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $order->items->count() }} test(s)</td>
                    <td class="px-6 py-3">
                        @php
                            $colors = ['pending' => 'yellow', 'sample_collected' => 'blue', 'processing' => 'indigo', 'completed' => 'green', 'cancelled' => 'red'];
                            $c = $colors[$order->status] ?? 'gray';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs bg-{{ $c }}-100 text-{{ $c }}-700">
                            {{ \App\Models\Order::STATUSES[$order->status] }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No orders today.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
