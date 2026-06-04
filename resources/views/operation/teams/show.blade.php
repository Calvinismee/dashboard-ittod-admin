<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('operation.teams.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition duration-200 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        Detail Tim: {{ $team->team_name }}
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">ID Tim: {{ $team->id }}</p>
                </div>
            </div>
            <span class="px-3.5 py-1 bg-indigo-50 text-indigo-700 font-semibold text-xs rounded-full border border-indigo-100 shadow-sm">
                {{ $team->event->title ?? $team->competition_id }}
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Column Left & Middle: Documents Review -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Bukti Pembayaran -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800 text-base">Bukti Pembayaran Pendaftaran</h3>
                            <span class="text-xs font-semibold text-gray-400">Media ID: {{ $team->payment_proof_id ?? 'Belum Diunggah' }}</span>
                        </div>
                        <div class="p-8 flex flex-col items-center justify-center bg-gray-50/50">
                            @if($team->paymentProof)
                                @if($team->paymentProof->type === 'image' || in_array(pathinfo($team->paymentProof->url, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                    <div class="relative group rounded-xl overflow-hidden shadow-md max-w-md bg-white p-2 border border-gray-200">
                                        <img src="{{ $team->paymentProof->url }}" alt="Bukti Pembayaran" class="max-h-[350px] object-contain mx-auto rounded-lg">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-300 gap-3">
                                            <a href="{{ $team->paymentProof->url }}" target="_blank" class="px-4 py-2 bg-white text-gray-800 font-semibold text-xs rounded-lg shadow hover:bg-gray-100 transition">
                                                Buka Penuh
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm max-w-sm w-full flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-red-50 text-red-500 flex items-center justify-center font-bold text-sm">
                                            PDF
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-sm text-gray-800 truncate">{{ $team->paymentProof->name }}</p>
                                            <p class="text-xs text-gray-400">Berkas Dokumen PDF</p>
                                        </div>
                                        <a href="{{ $team->paymentProof->url }}" target="_blank" class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                                            Unduh
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm font-semibold">Bukti transfer belum diunggah</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumen Identitas Anggota -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-800 px-1">Berkas Identitas Anggota (KTM / Twibbon)</h3>
                        
                        @foreach($team->members as $member)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="px-8 py-4 bg-gray-50/50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        @if($member->role === 'leader')
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded uppercase">Ketua</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-semibold rounded uppercase">Anggota</span>
                                        @endif
                                        <h4 class="font-bold text-gray-800 text-base">{{ $member->user->full_name }}</h4>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $member->user->email }}</span>
                                </div>

                                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Document Preview -->
                                    <div class="flex flex-col justify-center items-center bg-gray-50 rounded-xl p-4 border border-gray-100">
                                        <p class="text-xs font-semibold text-gray-400 mb-3 uppercase tracking-wider">Kartu Identitas / KTM</p>
                                        @if($member->kartu)
                                            @if($member->kartu->type === 'image' || in_array(pathinfo($member->kartu->url, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                                <img src="{{ $member->kartu->url }}" alt="KTM {{ $member->user->full_name }}" class="max-h-[180px] object-contain rounded-lg border border-gray-200 shadow-sm bg-white p-1">
                                                <a href="{{ $member->kartu->url }}" target="_blank" class="mt-3 text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                                                    <span>Lihat Gambar Penuh</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg w-full max-w-xs shadow-inner">
                                                    <div class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center font-bold text-xs">PDF</div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-bold text-gray-700 truncate">{{ $member->kartu->name }}</p>
                                                    </div>
                                                    <a href="{{ $member->kartu->url }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Buka</a>
                                                </div>
                                            @endif
                                        @else
                                            <p class="text-xs text-gray-400 py-6">KTM belum diunggah</p>
                                        @endif
                                    </div>

                                    <!-- Member Verification Status / Form -->
                                    <div class="flex flex-col justify-between">
                                        <div>
                                            <div class="mb-4">
                                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Status Berkas Anggota</span>
                                                @if(empty($member->verification_error))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Berkas Valid
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Ada Kesalahan
                                                    </span>
                                                    <div class="mt-2 p-2 bg-rose-50/50 rounded-lg border border-rose-100 text-xs text-rose-800 font-medium">
                                                        Catatan: {{ $member->verification_error }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <form action="{{ route('operation.teams.verifyMember', ['teamId' => $team->id, 'userId' => $member->user_id]) }}" method="POST" class="mt-4 pt-4 border-t border-gray-100">
                                            @csrf
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">Beri Catatan Kesalahan (Kosongkan jika berkas valid):</label>
                                            <div class="flex gap-2">
                                                <input type="text" name="verification_error" value="{{ $member->verification_error }}" placeholder="Contoh: KTM buram/tidak terbaca..." class="flex-1 text-xs px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold rounded-xl shadow transition">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Column Right: Team Verification Panel -->
                <div class="space-y-8">
                    <!-- Team Information Summary -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-5">
                        <h3 class="font-bold text-gray-800 text-lg border-b border-gray-100 pb-3">Informasi Tim</h3>
                        
                        <div class="grid grid-cols-2 gap-y-4 text-sm">
                            <div class="text-gray-400 font-medium">Nama Tim:</div>
                            <div class="text-gray-800 font-bold text-right">{{ $team->team_name }}</div>

                            <div class="text-gray-400 font-medium">Kode Lomba:</div>
                            <div class="text-right"><span class="px-2 py-0.5 bg-gray-100 text-gray-700 font-mono text-xs rounded font-medium">{{ $team->team_code }}</span></div>

                            <div class="text-gray-400 font-medium">Cabang Lomba:</div>
                            <div class="text-gray-800 font-semibold text-right">{{ $team->event->title ?? $team->competition_id }}</div>

                            <div class="text-gray-400 font-medium">Kapasitas:</div>
                            <div class="text-gray-800 font-semibold text-right">{{ $team->members->count() }} / {{ $team->max_member }} Anggota</div>

                            <div class="text-gray-400 font-medium">Status Penguncian:</div>
                            <div class="text-right">
                                @php
                                    $isTeamVerified = (bool) $team->is_verified;
                                    $hasTeamErr = !empty($team->verification_error);
                                    $hasMemErr = $team->members->contains(fn($m) => !empty($m->verification_error));
                                    $isUnderReview = !$isTeamVerified && !$hasTeamErr && !$hasMemErr;
                                @endphp
                                @if($isTeamVerified || $isUnderReview)
                                    <span class="inline-flex items-center text-xs font-bold text-amber-600">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg> Terkunci (Frozen)
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-bold text-emerald-600">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                        </svg> Terbuka (Revisi)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Verification Form Card -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                        <h3 class="font-bold text-gray-800 text-lg border-b border-gray-100 pb-3">Keputusan Verifikasi</h3>

                        <form action="{{ route('operation.teams.verify', $team->id) }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Validasi Berkas Pendaftaran:</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50/10 cursor-pointer transition">
                                        <input type="radio" name="is_verified" value="1" {{ $team->is_verified ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 mb-2">
                                        <span class="text-sm font-bold text-emerald-700">Setujui (Valid)</span>
                                    </label>
                                    <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50/10 cursor-pointer transition">
                                        <input type="radio" name="is_verified" value="0" {{ !$team->is_verified ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500 mb-2">
                                        <span class="text-sm font-bold text-rose-700">Tolak (Revisi)</span>
                                    </label>
                                </div>
                            </div>

                            <div id="error-input-container" class="{{ $team->is_verified ? 'hidden' : '' }}">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alasan Penolakan / Catatan Kesalahan:</label>
                                <textarea name="verification_error" placeholder="Sebutkan kesalahan pada data atau berkas tim..." class="w-full text-sm px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition h-28 resize-none">{{ $team->verification_error }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm">
                                Simpan Keputusan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Toggle Error Input Visibility based on Selection -->
    <script>
        document.querySelectorAll('input[name="is_verified"]').forEach((elem) => {
            elem.addEventListener("change", function(event) {
                var container = document.getElementById("error-input-container");
                if (event.target.value === "0") {
                    container.classList.remove("hidden");
                } else {
                    container.classList.add("hidden");
                }
            });
        });
    </script>
</x-app-layout>
