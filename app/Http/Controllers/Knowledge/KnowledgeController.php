<?php

namespace App\Http\Controllers\Knowledge;

use App\Enums\EvaluationType;
use App\Enums\HandoverType;
use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Handover;
use App\Models\Program;
use App\Models\TeachingNote;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KnowledgeController extends Controller
{
    /**
     * Halaman utama Knowledge Hub (Menampilkan Teaching Notes, Handover, dan Evaluasi).
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'notes');

        $teachingNotes = TeachingNote::with(['volunteer.user', 'event.batch.phase.program'])
            ->latest()
            ->paginate(10, ['*'], 'notes_page');

        $handovers = Handover::with(['creator', 'program'])
            ->latest()
            ->paginate(10, ['*'], 'handovers_page');

        $evaluations = Evaluation::with(['evaluator.user', 'evaluated.user', 'event.batch'])
            ->latest()
            ->paginate(10, ['*'], 'evaluations_page');

        return view('knowledge.index', compact('tab', 'teachingNotes', 'handovers', 'evaluations'));
    }

    /* =========================================================================
     * TEACHING NOTES (Catatan Mengajar)
     * ========================================================================= */

    /**
     * Form buat Catatan Mengajar baru.
     */
    public function createTeachingNote(): View
    {
        $events = Event::with('batch.phase.program')->orderBy('event_date', 'desc')->get();
        $volunteers = Volunteer::with('user')->get();

        return view('knowledge.notes.create', compact('events', 'volunteers'));
    }

    /**
     * Simpan Catatan Mengajar baru.
     */
    public function storeTeachingNote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'title' => 'required|string|max:255',
            'materials_used' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        // Jika user yang login adalah relawan, gunakan ID relawannya. Jika pengurus, bisa memilih atau otomatis dirinya
        $volunteerId = $validated['volunteer_id'] ?? auth()->user()->volunteer?->id;

        if (!$volunteerId) {
            $volunteerId = Volunteer::first()?->id;
        }

        TeachingNote::create([
            'event_id' => $validated['event_id'],
            'volunteer_id' => $volunteerId,
            'title' => $validated['title'],
            'materials_used' => $validated['materials_used'],
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('knowledge.index', ['tab' => 'notes'])
            ->with('success', 'Catatan mengajar berhasil dipublikasikan!');
    }

    /**
     * Detail Catatan Mengajar.
     */
    public function showTeachingNote(TeachingNote $teachingNote): View
    {
        $teachingNote->load(['volunteer.user', 'event.batch.phase.program']);

        return view('knowledge.notes.show', compact('teachingNote'));
    }

    /**
     * Hapus Catatan Mengajar.
     */
    public function destroyTeachingNote(TeachingNote $teachingNote): RedirectResponse
    {
        $teachingNote->delete();

        return redirect()
            ->route('knowledge.index', ['tab' => 'notes'])
            ->with('success', 'Catatan mengajar berhasil dihapus.');
    }

    /* =========================================================================
     * HANDOVERS (Dokumen Serah Terima Jabatan & LPJ)
     * ========================================================================= */

    /**
     * Form buat Dokumen Handover baru.
     */
    public function createHandover(): View
    {
        $programs = Program::orderBy('year', 'desc')->get();
        $types = HandoverType::cases();

        return view('knowledge.handovers.create', compact('programs', 'types'));
    }

    /**
     * Simpan Dokumen Handover.
     */
    public function storeHandover(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'type' => 'required|string|in:ketua,sekretaris,koordinator',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Handover::create([
            'program_id' => $validated['program_id'],
            'created_by' => auth()->id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('knowledge.index', ['tab' => 'handovers'])
            ->with('success', 'Dokumen handover serah terima jabatan berhasil diarsipkan!');
    }

    /**
     * Detail Dokumen Handover.
     */
    public function showHandover(Handover $handover): View
    {
        $handover->load(['creator', 'program']);

        return view('knowledge.handovers.show', compact('handover'));
    }

    /**
     * Hapus Dokumen Handover.
     */
    public function destroyHandover(Handover $handover): RedirectResponse
    {
        $handover->delete();

        return redirect()
            ->route('knowledge.index', ['tab' => 'handovers'])
            ->with('success', 'Dokumen handover berhasil dihapus.');
    }

    /* =========================================================================
     * EVALUATIONS (Evaluasi Pertemuan Pengajaran)
     * ========================================================================= */

    /**
     * Form buat Evaluasi Pertemuan.
     */
    public function createEvaluation(): View
    {
        $events = Event::with('batch')->orderBy('event_date', 'desc')->get();
        $volunteers = Volunteer::with('user')->get();
        $types = EvaluationType::cases();

        return view('knowledge.evaluations.create', compact('events', 'volunteers', 'types'));
    }

    /**
     * Simpan Evaluasi Pertemuan.
     */
    public function storeEvaluation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'evaluator_volunteer_id' => 'nullable|exists:volunteers,id',
            'evaluated_volunteer_id' => 'nullable|exists:volunteers,id',
            'type' => 'required|string|in:individual,group',
            'content' => 'required|string',
        ]);

        $evaluatorId = $validated['evaluator_volunteer_id'] ?? auth()->user()->volunteer?->id;
        if (!$evaluatorId) {
            $evaluatorId = Volunteer::first()?->id;
        }

        Evaluation::create([
            'event_id' => $validated['event_id'],
            'evaluator_volunteer_id' => $evaluatorId,
            'evaluated_volunteer_id' => $validated['type'] === 'individual' ? $validated['evaluated_volunteer_id'] : null,
            'type' => $validated['type'],
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('knowledge.index', ['tab' => 'evaluations'])
            ->with('success', 'Catatan evaluasi pertemuan berhasil dicatat!');
    }

    /**
     * Hapus Evaluasi.
     */
    public function destroyEvaluation(Evaluation $evaluation): RedirectResponse
    {
        $evaluation->delete();

        return redirect()
            ->route('knowledge.index', ['tab' => 'evaluations'])
            ->with('success', 'Evaluasi berhasil dihapus.');
    }
}
