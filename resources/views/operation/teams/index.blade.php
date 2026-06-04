<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Manajemen Verifikasi Tim') }}
            </h2>
            <span class="px-4 py-1.5 bg-indigo-50 text-indigo-700 font-semibold text-sm rounded-full border border-indigo-100 shadow-sm">
                Modul Operasional Panitia
            </span>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm text-emerald-800 flex items-center gap-3 transition duration-300">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Teams -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Tim</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $teams->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Verified Teams -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Terverifikasi</p>
                        <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">
                            {{ $teams->where('is_verified', true)->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Pending Review -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Sedang Diperiksa</p>
                        <h3 class="text-3xl font-extrabold text-amber-500 mt-1">
                            {{ $teams->filter(fn($t) => !$t->is_verified && empty($t->verification_error) && !$t->members->contains(fn($m) => !empty($m->verification_error)))->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center shadow-inner relative">
                        <span class="absolute top-2.5 right-2.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                        </span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Rejected / Revision Needed -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Butuh Revisi</p>
                        <h3 class="text-3xl font-extrabold text-rose-600 mt-1">
                            {{ $teams->filter(fn($t) => !$t->is_verified && (!empty($t->verification_error) || $t->members->contains(fn($m) => !empty($m->verification_error))))->count() }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Teams Table Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Tim Terdaftar</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Kelola kelengkapan data, bukti transfer, dan verifikasi identitas.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-400 border-b border-gray-100">
                                <th class="px-8 py-4">Nama Tim / Kode</th>
                                <th class="px-6 py-4">Cabang Lomba</th>
                                <th class="px-6 py-4">Ketua & Anggota</th>
                                <th class="px-6 py-4">Status Verifikasi</th>
                                <th class="px-8 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($teams as $team)
                                @php
                                    $isTeamVerified = (bool) $team->is_verified;
                                    $hasTeamErr = !empty($team->verification_error);
                                    $hasMemErr = $team->members->contains(fn($m) => !empty($m->verification_error));
                                    
                                    if ($isTeamVerified) {
                                        $statusLabel = 'Terverifikasi';
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                    } elseif ($hasTeamErr || $hasMemErr) {
                                        $statusLabel = 'Butuh Revisi';
                                        $statusClass = 'bg-rose-50 text-rose-700 border-rose-100';
                                    } else {
                                        $statusLabel = 'Sedang Diperiksa';
                                        $statusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition duration-150">
                                    <td class="px-8 py-5">
                                        <div class="font-semibold text-gray-800 text-base">{{ $team->team_name }}</div>
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span class="text-xs text-gray-400">Kode:</span>
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-mono font-medium">{{ $team->team_code }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                                            {{ $team->event->title ?? $team->competition_id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="space-y-1.5">
                                            @foreach($team->members as $member)
                                                <div class="flex items-center gap-2">
                                                    @if($member->role === 'leader')
                                                        <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded uppercase">Ketua</span>
                                                    @else
                                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-semibold rounded uppercase">Anggota</span>
                                                    @endif
                                                    <span class="font-medium text-gray-700">{{ $member->user->full_name ?? 'Peserta' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                            @if($statusLabel === 'Sedang Diperiksa')
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5 animate-pulse"></span>
                                            @endif
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('operation.teams.show', $team->id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm hover:shadow-md transition duration-200">
                                            <span>Periksa Berkas</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-12 h-12 text-gray-300 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2M9 5h6m-6 4h6m-6 4h6"></path>
                                            </svg>
                                            <p class="font-medium text-base">Tidak ada tim yang terdaftar saat ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
