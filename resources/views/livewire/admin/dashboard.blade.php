<div>
    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total Labs</p>
            <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalLabs }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Active Labs</p>
            <p class="text-3xl font-bold text-green-600">{{ $activeLabs }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total Staff</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>
    </div>

    {{-- Recent Labs --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700">
            <h2 class="font-semibold text-gray-800 dark:text-white">Recent Labs</h2>
            <a href="{{ route('admin.labs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">+ Add Lab</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Lab Name</th>
                    <th class="px-6 py-3 text-left">City</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @foreach($recentLabs as $lab)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3 font-medium text-gray-800 dark:text-white">{{ $lab->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $lab->city ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs {{ $lab->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $lab->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $lab->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
