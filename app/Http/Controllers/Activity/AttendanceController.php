<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    /**
     * Halaman daftar aktivitas/pertemuan dan ringkasan presensi.
     */
    public function index(Request $request): View
    {
        $events = Event::with(['batch.phase.program', 'attendances', 'batch.volunteers'])
            ->orderBy('event_date', 'asc')
            ->paginate(10);

        return view('activity.index', compact('events'));
    }

    /**
     * Halaman lembar presensi untuk satu pertemuan.
     */
    public function show(Event $event): View
    {
        $data = $this->attendanceService->getEventAttendanceData($event);

        // Cek apakah user yang sedang login adalah volunteer di batch ini
        $currentVolunteer = auth()->user()->volunteer;
        $isAssignedVolunteer = false;
        $myAttendance = null;

        if ($currentVolunteer) {
            $isAssignedVolunteer = $data['volunteers']->contains('id', $currentVolunteer->id);
            $myAttendance = $data['attendances']->get($currentVolunteer->id);
        }

        return view('activity.attendance', array_merge($data, [
            'isAssignedVolunteer' => $isAssignedVolunteer,
            'myAttendance' => $myAttendance,
            'currentVolunteer' => $currentVolunteer,
        ]));
    }

    /**
     * Simpan presensi massal oleh Pengurus/Admin.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|string|in:present,late,excused,absent',
            'attendances.*.notes' => 'nullable|string|max:255',
        ]);

        $this->attendanceService->saveBatchAttendance($event, $validated['attendances']);

        return redirect()
            ->route('activities.attendance', $event)
            ->with('success', 'Data presensi berhasil disimpan!');
    }

    /**
     * Self Check-in mandiri oleh relawan.
     */
    public function selfCheckIn(Request $request, Event $event): RedirectResponse
    {
        $volunteer = auth()->user()->volunteer;

        if (! $volunteer) {
            return back()->with('error', 'Profil relawan Anda tidak ditemukan.');
        }

        // Cek apakah volunteer tergabung di batch pertemuan ini
        $isAssigned = $event->batch?->volunteers()->where('volunteers.id', $volunteer->id)->exists();

        if (! $isAssigned) {
            return back()->with('error', 'Anda tidak terdaftar sebagai relawan di batch pertemuan ini.');
        }

        $this->attendanceService->selfCheckIn($event, $volunteer);

        return back()->with('success', 'Presensi mandiri berhasil! Terima kasih telah hadir mengajar.');
    }
}
