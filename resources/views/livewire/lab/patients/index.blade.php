<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-3">
            <input wire:model.live="search" type="text" placeholder="Search by name, CNIC, phone..." class="border rounded-lg px-4 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white w-72">
            <select wire:model.live="gender" class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">All Genders</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <a href="{{ route('lab.patients.create') }}" wire:navigate class="app-btn-primary rounded-lg px-4 py-2 text-sm transition">+ New Patient</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">CNIC / Phone</th>
                    <th class="px-6 py-3 text-left">Age / Gender</th>
                    <th class="px-6 py-3 text-left">Orders</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $patient->patient_id }}</td>
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800 dark:text-white">{{ $patient->name }}</p>
                        <p class="text-xs text-gray-400">{{ $patient->referred_by ?? '' }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        <p>{{ $patient->cnic ?? '—' }}</p>
                        <p>{{ $patient->phone ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-500">
                        {{ $patient->age ? $patient->age.' '.$patient->age_unit : '—' }} / {{ ucfirst($patient->gender) }}
                    </td>
                    <td class="px-6 py-3">
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $patient->orders_count }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('lab.orders.create') }}?patient={{ $patient->id }}" wire:navigate class="app-link-primary text-xs">+ Order</a>
                            <a href="{{ route('lab.patients.edit', $patient) }}" wire:navigate class="text-gray-600 hover:underline text-xs">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No patients found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $patients->links() }}</div>
    </div>
</div>
