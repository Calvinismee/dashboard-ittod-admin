<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('timeline.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition duration-200 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Lini Masa') }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Sunting informasi jadwal penting kompetisi.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg shadow-sm text-rose-800">
                    <div class="font-bold text-sm mb-1">Terjadi kesalahan input:</div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Form Sunting Lini Masa</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Ubah rincian kegiatan kompetisi di bawah ini.</p>
                </div>

                <form action="{{ route('timeline.update', $timeline->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Event Dropdown -->
                    <div>
                        <label for="event_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Cabang Event / Lomba</label>
                        <select name="event_id" id="event_id" required class="w-full text-sm px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ (old('event_id', $timeline->event_id) === $event->id) ? 'selected' : '' }}>
                                    {{ $event->title }} ({{ $event->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Nama Agenda Kegiatan</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $timeline->title) }}" required class="w-full text-sm px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal & Waktu Pelaksanaan</label>
                        <input type="datetime-local" name="date" id="date" value="{{ old('date', $timeline->date ? $timeline->date->format('Y-m-d\TH:i') : '') }}" required class="w-full text-sm px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('timeline.index') }}" class="px-5 py-3 border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold rounded-xl text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm">
                            Perbarui Agenda
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
