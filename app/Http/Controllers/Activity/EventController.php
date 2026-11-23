<?php

namespace App\Http\Controllers\Activity;

use App\Enums\EventType;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Form tambah kegiatan pertemuan baru.
     */
    public function create(): View
    {
        $batches = Batch::with('phase.program')->get();
        $types = EventType::cases();

        return view('activity.events.create', compact('batches', 'types'));
    }

    /**
     * Simpan pertemuan baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:regular,special,aksi',
            'event_date' => 'required|date',
            'event_time' => 'required|string',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $fullDateTime = Carbon::parse($validated['event_date'] . ' ' . $validated['event_time']);

        Event::create([
            'batch_id' => $validated['batch_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'event_date' => $fullDateTime,
            'location' => $validated['location'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Kegiatan pertemuan baru berhasil ditambahkan!');
    }

    /**
     * Form edit kegiatan pertemuan.
     */
    public function edit(Event $event): View
    {
        $batches = Batch::with('phase.program')->get();
        $types = EventType::cases();

        return view('activity.events.edit', compact('event', 'batches', 'types'));
    }

    /**
     * Update data kegiatan pertemuan.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:regular,special,aksi',
            'event_date' => 'required|date',
            'event_time' => 'required|string',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $fullDateTime = Carbon::parse($validated['event_date'] . ' ' . $validated['event_time']);

        $event->update([
            'batch_id' => $validated['batch_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'event_date' => $fullDateTime,
            'location' => $validated['location'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Data kegiatan pertemuan berhasil diperbarui!');
    }

    /**
     * Hapus jadwal kegiatan pertemuan.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $eventName = $event->name;
        $event->delete();

        return redirect()
            ->route('activities.index')
            ->with('success', "Kegiatan \"{$eventName}\" berhasil dihapus!");
    }
}
