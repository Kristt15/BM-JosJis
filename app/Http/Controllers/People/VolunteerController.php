<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\StoreVolunteerRequest;
use App\Http\Requests\People\UpdateVolunteerRequest;
use App\Models\Volunteer;
use App\Services\VolunteerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function __construct(
        private readonly VolunteerService $volunteerService
    ) {}

    public function index(Request $request): View
    {
        $volunteers = $this->volunteerService->getAll($request->only(['search', 'status']));

        return view('people.volunteers.index', compact('volunteers'));
    }

    public function create(): View
    {
        return view('people.volunteers.create');
    }

    public function store(StoreVolunteerRequest $request): RedirectResponse
    {
        $this->volunteerService->create($request->validated());

        return redirect()->route('volunteers.index')
            ->with('success', 'Volunteer berhasil ditambahkan!');
    }

    public function show(Volunteer $volunteer): View
    {
        $volunteer->load(['user', 'batches.phase.program']);

        return view('people.volunteers.show', compact('volunteer'));
    }

    public function edit(Volunteer $volunteer): View
    {
        $volunteer->load('user');

        return view('people.volunteers.edit', compact('volunteer'));
    }

    public function update(UpdateVolunteerRequest $request, Volunteer $volunteer): RedirectResponse
    {
        $this->volunteerService->update($volunteer, $request->validated());

        return redirect()->route('volunteers.show', $volunteer)
            ->with('success', 'Data volunteer berhasil diperbarui!');
    }

    public function destroy(Volunteer $volunteer): RedirectResponse
    {
        $this->volunteerService->delete($volunteer);

        return redirect()->route('volunteers.index')
            ->with('success', 'Volunteer berhasil dihapus.');
    }
}
