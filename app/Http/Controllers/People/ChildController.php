<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\StoreChildRequest;
use App\Http\Requests\People\UpdateChildRequest;
use App\Models\Child;
use App\Services\ChildService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChildController extends Controller
{
    public function __construct(
        private readonly ChildService $childService
    ) {}

    public function index(Request $request): View
    {
        $children = $this->childService->getAll($request->only(['search']));

        return view('people.children.index', compact('children'));
    }

    public function create(): View
    {
        return view('people.children.create');
    }

    public function store(StoreChildRequest $request): RedirectResponse
    {
        $this->childService->create($request->validated());

        return redirect()->route('children.index')
            ->with('success', 'Data anak SRK berhasil ditambahkan!');
    }

    public function show(Child $child): View
    {
        $child->load(['batches.phase.program', 'mentorAssignments.volunteer']);

        return view('people.children.show', compact('child'));
    }

    public function edit(Child $child): View
    {
        return view('people.children.edit', compact('child'));
    }

    public function update(UpdateChildRequest $request, Child $child): RedirectResponse
    {
        $this->childService->update($child, $request->validated());

        return redirect()->route('children.show', $child)
            ->with('success', 'Data anak SRK berhasil diperbarui!');
    }

    public function destroy(Child $child): RedirectResponse
    {
        $this->childService->delete($child);

        return redirect()->route('children.index')
            ->with('success', 'Data anak SRK berhasil dihapus.');
    }
}
