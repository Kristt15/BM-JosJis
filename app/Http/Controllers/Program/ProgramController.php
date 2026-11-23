<?php

namespace App\Http\Controllers\Program;

use App\Models\Program;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        $programs = Program::withCount(['phases'])
            ->with('phases.batches')
            ->latest()
            ->paginate(10);

        return view('program.index', compact('programs'));
    }

    public function show(Program $program): View
    {
        $program->load([
            'phases.batches.volunteers.user',
            'phases.batches.children',
            'phases.batches.events',
        ]);

        $totalVolunteers = $program->phases
            ->flatMap(fn($phase) => $phase->batches)
            ->flatMap(fn($batch) => $batch->volunteers)
            ->unique('id')
            ->count();

        $totalChildren = $program->phases
            ->flatMap(fn($phase) => $phase->batches)
            ->flatMap(fn($batch) => $batch->children)
            ->unique('id')
            ->count();

        $totalBatches = $program->phases
            ->flatMap(fn($phase) => $phase->batches)
            ->count();

        $totalEvents = $program->phases
            ->flatMap(fn($phase) => $phase->batches)
            ->flatMap(fn($batch) => $batch->events)
            ->count();

        return view('program.show', compact(
            'program',
            'totalVolunteers',
            'totalChildren',
            'totalBatches',
            'totalEvents'
        ));
    }
}
