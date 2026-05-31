<x-admin.layout
    title="Manajemen Akun Staff"
    subtitle="Khusus Superadmin untuk memetakan akses panitia dan event yang dikelola."
>
    <section class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-950">Akun Staff</h2>
            <p class="mt-1 text-sm text-gray-600">Kerangka ini siap disambungkan ke aksi tambah staff, ubah role, dan assignment event.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Staff</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Event</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($staffAccounts as $staff)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-950">{{ $staff->user?->full_name ?? $staff->email }}</p>
                                <p class="text-sm text-gray-600">{{ $staff->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ str_replace('_', ' ', $staff->role) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $staff->events->pluck('title')->join(', ') ?: 'Belum ditugaskan' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    Kelola
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-600">Belum ada akun staff.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin.layout>
