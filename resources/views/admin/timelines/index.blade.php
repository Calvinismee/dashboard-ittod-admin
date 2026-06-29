@php
    $isSinglePanitia = !$canManageCompetitions && $events->count() === 1;
    $title = Auth::user()->role === 'superadmin' ? "Kelola Event dan Kompetisi" : "Kelola Kompetisi";
    $subtitle = $isSinglePanitia ? "Detail kompetisi " . $events->first()->title : "Kelola fase pendaftaran, submission, penjurian, final, serta link guidebook khusus event/lomba.";
@endphp

<x-admin.layout
    :title="$title"
    :subtitle="$subtitle"
>
    @if ($isSinglePanitia)
        @php $singleEvent = $events->first(); @endphp
        
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $singleEvent->title }}</h2>
            <div class="flex items-center gap-2 mb-6">
                <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold uppercase text-blue-700">
                    ID: {{ $singleEvent->id }}
                </span>
                @if ($singleEvent->requires_submission)
                    <span class="inline-flex rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-bold uppercase text-indigo-700">Submission Required</span>
                @endif
                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-bold uppercase {{ $singleEvent->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $singleEvent->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Description Section -->
                <div class="rounded border border-gray-200 p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Deskripsi</h3>
                    <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $singleEvent->description }}</p>
                    <button type="button" x-data x-on:click="$dispatch('open-modal', 'edit-panitia-description-{{ $singleEvent->id }}')" class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-800">Edit Deskripsi</button>
                </div>

                <!-- Guidebook Section -->
                <div class="rounded border border-gray-200 p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Guidebook</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ $singleEvent->guide_book_url }}" target="_blank" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 shadow-sm transition-all duration-150">
                            Lihat Guidebook
                        </a>
                        <button type="button" x-data x-on:click="$dispatch('open-modal', 'edit-panitia-guidebook-{{ $singleEvent->id }}')" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-150">
                            Edit Guidebook
                        </button>
                    </div>
                </div>

                <!-- Timeline Section -->
                <div class="rounded border border-gray-200 p-4 sm:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Timeline / Agenda</h3>
                        <a href="{{ route('admin.timelines.agenda', $singleEvent) }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-150">
                            Kelola Agenda
                        </a>
                    </div>
                    <p class="text-sm text-gray-600">Terdapat {{ $singleEvent->timelines_count }} agenda dalam kompetisi ini.</p>
                </div>

                <!-- Submissions Section -->
                @if ($singleEvent->requires_submission)
                <div class="rounded border border-gray-200 p-4 sm:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Submissions (Karya/Berkas Lomba)</h3>
                    </div>
                    @if ($singleEvent->submissions->isEmpty())
                        <p class="text-sm text-gray-600">Belum ada tim yang mengumpulkan submission.</p>
                    @else
                        <div class="overflow-x-auto rounded border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Tim</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Waktu Pengumpulan</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Detail Submission</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($singleEvent->submissions as $submission)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-950">{{ $submission->team->team_name ?? 'Tim Tidak Diketahui' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $submission->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                @if (is_array($submission->submission_object))
                                                    <ul class="list-disc pl-4 space-y-1">
                                                        @foreach ($submission->submission_object as $key => $value)
                                                            <li>
                                                                <span class="font-medium">{{ Str::title(str_replace('_', ' ', $key)) }}:</span> 
                                                                @if (filter_var($value, FILTER_VALIDATE_URL))
                                                                    <a href="{{ $value }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">Buka Tautan</a>
                                                                @else
                                                                    <span>{{ $value }}</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-gray-500 italic">Format tidak didukung</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <x-modal name="edit-panitia-description-{{ $singleEvent->id }}" maxWidth="lg" focusable>
            <form method="POST" action="{{ route('admin.competitions.panitia-details', $singleEvent) }}" class="p-6">
                @csrf
                @method('PATCH')
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-950">Edit Deskripsi Kompetisi</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $singleEvent->title }}</p>
                </div>
                <div class="mt-5 grid gap-4">
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">Deskripsi</span>
                        <textarea name="description" required rows="4" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $singleEvent->description) }}</textarea>
                    </label>
                    <input type="hidden" name="guide_book_url" value="{{ $singleEvent->guide_book_url }}">
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-panitia-description-{{ $singleEvent->id }}')" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="rounded-md bg-blue-700 px-4 py-2 text-sm font-bold text-white hover:bg-blue-800">Simpan Deskripsi</button>
                </div>
            </form>
        </x-modal>

        <x-modal name="edit-panitia-guidebook-{{ $singleEvent->id }}" maxWidth="lg" focusable>
            <form method="POST" action="{{ route('admin.competitions.panitia-details', $singleEvent) }}" class="p-6">
                @csrf
                @method('PATCH')
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-950">Edit Guidebook</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $singleEvent->title }}</p>
                </div>
                <div class="mt-5 grid gap-4">
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">URL Guide Book</span>
                        <input type="url" name="guide_book_url" value="{{ old('guide_book_url', $singleEvent->guide_book_url) }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <input type="hidden" name="description" value="{{ $singleEvent->description }}">
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-panitia-guidebook-{{ $singleEvent->id }}')" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="rounded-md bg-blue-700 px-4 py-2 text-sm font-bold text-white hover:bg-blue-800">Simpan Guidebook</button>
                </div>
            </form>
        </x-modal>

    @else
        @if ($canManageCompetitions)
            <div class="mb-6 flex justify-end">
                <button
                    type="button"
                    x-data
                    x-on:click="$dispatch('open-modal', 'create-competition')"
                    class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800"
                >
                    Tambah Event
                </button>
            </div>
        @endif

        @if (($canManageCompetitions || $canManageTimelines) && $errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Timeline belum bisa disimpan.</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section x-data="{ search: '' }" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-gray-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-xl font-semibold text-gray-950">Direktori Timeline Event</h2>
                        <span class="rounded border border-emerald-200 bg-emerald-50 px-2 py-1 text-[10px] font-bold uppercase text-emerald-700">
                            Event Records
                        </span>
                    </div>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-gray-700">Event, agenda, tim, dan status publikasi</p>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $events->count() }} records detected</p>
            </div>

            <div class="border-b border-gray-200 px-6 py-4">
                <label class="relative block">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
                        </svg>
                    </span>
                    <input
                        type="search"
                        x-model="search"
                        placeholder="Search event, status, tim, atau jumlah agenda..."
                        class="w-full rounded-md border-gray-300 pl-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </label>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Agenda</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Tim</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Selengkapnya</th>
                            @if ($canManageCompetitions || $canManageTimelines)
                                <th class="px-6 py-3 text-right text-xs font-bold uppercase text-gray-600">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($events as $event)
                            <tr
                                x-show="$el.dataset.search.includes(search.toLowerCase())"
                                data-search="{{ Str::lower($event->title . ' ' . ($event->is_active ? 'aktif' : 'nonaktif') . ' ' . $event->teams_count . ' tim ' . $event->timelines_count . ' agenda') }}"
                                class="align-top hover:bg-gray-50"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-950">{{ $event->title }}</p>
                                        @if ($event->type === 'competition')
                                            <span class="inline-flex rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-bold uppercase text-orange-700">Kompetisi</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-bold uppercase text-purple-700">Non-Kompetisi</span>
                                        @endif
                                        @if ($event->requires_submission)
                                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold uppercase text-blue-700">Submission</span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">ID: {{ $event->id }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded border border-indigo-100 bg-indigo-50 px-2 py-1 text-[11px] font-bold uppercase text-indigo-700">
                                        {{ $event->timelines_count }} agenda
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-700">{{ $event->teams_count }} tim</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded border px-2 py-1 text-[11px] font-bold uppercase {{ $event->is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-gray-200 bg-gray-50 text-gray-500' }}">
                                        {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.timelines.agenda', $event) }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        Lihat Detail
                                    </a>
                                </td>
                                @if ($canManageCompetitions || $canManageTimelines)
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col items-end gap-2">
                                            <button
                                                type="button"
                                                x-data
                                                x-on:click="$dispatch('open-modal', 'edit-panitia-details-{{ $event->id }}')"
                                                class="rounded-md border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100"
                                            >
                                                Edit Detail
                                            </button>
                                            @if ($canManageCompetitions)
                                                <button
                                                    type="button"
                                                    x-data
                                                    x-on:click="$dispatch('open-modal', 'edit-competition-{{ $event->id }}')"
                                                    class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100"
                                                >
                                                    Edit Kompetisi
                                                </button>
                                                @if ($event->teams_count > 0)
                                                    <form method="POST" action="{{ route('admin.competitions.status', $event) }}" onsubmit="return confirm('{{ $event->is_active ? 'Nonaktifkan' : 'Aktifkan kembali' }} kompetisi ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button
                                                            type="submit"
                                                            class="rounded-md border px-3 py-2 text-sm font-semibold {{ $event->is_active ? 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                                        >
                                                            {{ $event->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.competitions.destroy', $event) }}" onsubmit="return confirm('Hapus kompetisi ini? Timeline kompetisi juga akan terhapus.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100"
                                                        >
                                                            Hapus Kompetisi
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($canManageCompetitions || $canManageTimelines) ? 6 : 5 }}" class="px-6 py-10 text-center text-sm text-gray-600">Belum ada event kompetisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if ($canManageCompetitions)
    <x-modal name="create-competition" maxWidth="2xl" focusable>
        <form method="POST" action="{{ route('admin.competitions.store') }}" class="p-6">
            @csrf
            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-950">Tambah Event</h3>
                <p class="mt-1 text-sm text-gray-600">Buat event baru untuk IT Today.</p>
            </div>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <label class="block sm:col-span-2">
                    <span class="text-sm font-semibold text-gray-700">Tipe Event</span>
                    <select name="type" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="competition" @selected(old('type') === 'competition')>Kompetisi</option>
                        <option value="non_competition" @selected(old('type') === 'non_competition')>Non-Kompetisi</option>
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">Nama Event</span>
                    <input name="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">Biaya Pendaftaran</span>
                    <x-admin.currency-input name="price" :value="old('price', 0)" required />
                </label>
                <label class="block sm:col-span-2">
                    <span class="text-sm font-semibold text-gray-700">Deskripsi</span>
                    <textarea name="description" required rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description') }}</textarea>
                </label>
                <label class="flex items-center gap-3 rounded-md border border-gray-200 px-3 py-3 sm:col-span-2">
                    <input type="hidden" name="requires_submission" value="0">
                    <input type="checkbox" name="requires_submission" value="1" @checked(old('requires_submission')) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-sm font-semibold text-gray-700">Membutuhkan Submission (Karya/Berkas Lomba)</span>
                </label>
                <label class="block sm:col-span-2">
                    <span class="text-sm font-semibold text-gray-700">URL Guide Book</span>
                    <input type="url" name="guide_book_url" value="{{ old('guide_book_url') }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">Contact Person 1</span>
                    <input name="contact_person1" value="{{ old('contact_person1') }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">Contact Person 2</span>
                    <input name="contact_person2" value="{{ old('contact_person2') }}" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </label>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'create-competition')" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-800">Simpan Event</button>
            </div>
        </form>
    </x-modal>

    @foreach ($events as $event)
        <x-modal name="edit-competition-{{ $event->id }}" maxWidth="2xl" focusable>
            <form method="POST" action="{{ route('admin.competitions.update', $event) }}" class="p-6">
                @csrf
                @method('PATCH')
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-950">Edit Event</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $event->title }}</p>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label class="block sm:col-span-2">
                        <span class="text-sm font-semibold text-gray-700">Tipe Event</span>
                        <select name="type" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="competition" @selected(old('type', $event->type) === 'competition')>Kompetisi</option>
                            <option value="non_competition" @selected(old('type', $event->type) === 'non_competition')>Non-Kompetisi</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">Nama Event</span>
                        <input name="title" value="{{ old('title', $event->title) }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">Biaya Pendaftaran</span>
                        <x-admin.currency-input name="price" :value="old('price', $event->price)" required />
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-gray-200 px-3 py-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $event->is_active)) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-semibold text-gray-700">Event aktif</span>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-sm font-semibold text-gray-700">Deskripsi</span>
                        <textarea name="description" required rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description', $event->description) }}</textarea>
                    </label>
                    <label class="flex items-center gap-3 rounded-md border border-gray-200 px-3 py-3 sm:col-span-2">
                        <input type="hidden" name="requires_submission" value="0">
                        <input type="checkbox" name="requires_submission" value="1" @checked(old('requires_submission', $event->requires_submission)) class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-semibold text-gray-700">Membutuhkan Submission (Karya/Berkas Lomba)</span>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="text-sm font-semibold text-gray-700">URL Guide Book</span>
                        <input type="url" name="guide_book_url" value="{{ old('guide_book_url', $event->guide_book_url) }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">Contact Person 1</span>
                        <input name="contact_person1" value="{{ old('contact_person1', $event->contact_person1) }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">Contact Person 2</span>
                        <input name="contact_person2" value="{{ old('contact_person2', $event->contact_person2) }}" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </label>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-competition-{{ $event->id }}')" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-800">Simpan Perubahan</button>
                </div>
            </form>
        </x-modal>
    @endforeach
    @endif

    @if ($canManageCompetitions || $canManageTimelines)
    @foreach ($events as $event)
        <x-modal name="edit-panitia-details-{{ $event->id }}" maxWidth="lg" focusable>
            <form method="POST" action="{{ route('admin.competitions.panitia-details', $event) }}" class="p-6">
                @csrf
                @method('PATCH')
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-950">Edit Detail Kompetisi</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ $event->title }}</p>
                </div>
                <div class="mt-5 grid gap-4">
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">Deskripsi</span>
                        <textarea name="description" required rows="4" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $event->description) }}</textarea>
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-gray-700">URL Guide Book</span>
                        <input type="url" name="guide_book_url" value="{{ old('guide_book_url', $event->guide_book_url) }}" required class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-panitia-details-{{ $event->id }}')" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="rounded-md bg-blue-700 px-4 py-2 text-sm font-bold text-white hover:bg-blue-800">Simpan Perubahan</button>
                </div>
            </form>
        </x-modal>
    @endforeach
    @endif
</x-admin.layout>
