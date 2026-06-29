<x-admin.layout
    title="Peserta Event Non-Kompetisi"
    subtitle="Verifikasi bukti pembayaran dan pendaftaran peserta event."
>
    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.event-participants.index') }}" class="mb-6 flex flex-wrap gap-4">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700">Filter Event</label>
                <select name="event_id" onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Event Non-Kompetisi</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" @selected(request('event_id') === $event->id)>{{ $event->title }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Bukti Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($participants as $participant)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $participant->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $participant->email }}</p>
                                <p class="text-xs text-gray-500">{{ $participant->phone_number }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-800">{{ $participant->event_title }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($participant->date_added)->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($participant->payment_proof)
                                    <a href="{{ Storage::url($participant->payment_proof) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Lihat Bukti</a>
                                @else
                                    <span class="text-gray-400 text-sm italic">Belum Upload</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($participant->payment_verification === 'accepted')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Diterima</span>
                                @elseif($participant->payment_verification === 'rejected')
                                    <span class="inline-flex rounded-full bg-rose-100 px-2 py-0.5 text-xs font-semibold text-rose-800">Ditolak</span>
                                @else
                                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <form method="POST" action="{{ route('admin.event-participants.verify') }}" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $participant->user_id }}">
                                    <input type="hidden" name="event_id" value="{{ $participant->event_id }}">
                                    
                                    @if($participant->payment_verification !== 'accepted')
                                        <button type="submit" name="action" value="accept" class="rounded border border-emerald-500 text-emerald-600 px-3 py-1 hover:bg-emerald-50 focus:ring-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-1">
                                            Terima
                                        </button>
                                    @endif
                                    
                                    @if($participant->payment_verification !== 'rejected')
                                        <button type="submit" name="action" value="reject" class="rounded border border-rose-500 text-rose-600 px-3 py-1 hover:bg-rose-50 focus:ring-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-1">
                                            Tolak
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data peserta event non-kompetisi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $participants->links() }}
        </div>
    </section>
</x-admin.layout>
