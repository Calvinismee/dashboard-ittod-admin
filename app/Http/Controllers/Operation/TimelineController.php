<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use App\Models\EventTimeline;
use App\Models\Event;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    // Menampilkan daftar lini masa kompetisi (REQ-10)
    public function index()
    {
        $timelines = EventTimeline::with('event')->orderBy('date', 'asc')->get();
        return view('operation.timeline.index', compact('timelines'));
    }

    // Menampilkan form tambah lini masa (REQ-10)
    public function create()
    {
        $events = Event::all();
        return view('operation.timeline.create', compact('events'));
    }

    // Menyimpan lini masa baru (REQ-10)
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:event,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        EventTimeline::create([
            'event_id' => $request->event_id,
            'title' => $request->title,
            'date' => $request->date,
        ]);

        return redirect()->route('timeline.index')->with('success', 'Lini masa berhasil ditambahkan!');
    }

    // Menampilkan form edit lini masa (REQ-10)
    public function edit(string $id)
    {
        $timeline = EventTimeline::findOrFail($id);
        $events = Event::all();
        return view('operation.timeline.edit', compact('timeline', 'events'));
    }

    // Memperbarui lini masa (REQ-10)
    public function update(Request $request, string $id)
    {
        $timeline = EventTimeline::findOrFail($id);

        $request->validate([
            'event_id' => 'required|exists:event,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $timeline->update([
            'event_id' => $request->event_id,
            'title' => $request->title,
            'date' => $request->date,
        ]);

        return redirect()->route('timeline.index')->with('success', 'Lini masa berhasil diperbarui!');
    }

    // Menghapus lini masa (REQ-10)
    public function destroy(string $id)
    {
        $timeline = EventTimeline::findOrFail($id);
        $timeline->delete();

        return redirect()->route('timeline.index')->with('success', 'Lini masa berhasil dihapus!');
    }
}
