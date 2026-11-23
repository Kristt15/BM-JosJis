<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\People\VolunteerController;
use App\Http\Controllers\People\ChildController;
use App\Http\Controllers\Program\ProgramController;
use App\Http\Controllers\Activity\AttendanceController;
use App\Http\Controllers\Activity\EventController;
use App\Http\Controllers\Knowledge\KnowledgeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/settings', function () {
            return response()->json(['message' => 'Admin Settings Area']);
        })->name('settings');
    });

    // Admin & Pengurus
    Route::middleware('role:admin,pengurus')->group(function () {
        // People Hub
        Route::get('/people', function () {
            return view('people.index');
        })->name('people.index');

        // Volunteer (Full CRUD)
        Route::resource('people/volunteers', VolunteerController::class)
            ->names('volunteers');

        // Children (Full CRUD)
        Route::resource('people/children', ChildController::class)
            ->names('children');

        // Program (Read only)
        Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
        Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
    });

    // All Authenticated (Admin, Pengurus, Volunteer)
    Route::middleware('role:admin,pengurus,volunteer')->group(function () {
        Route::get('/dashboard', function () {
            $totalVolunteers = \App\Models\Volunteer::count();
            $totalChildren = \App\Models\Child::count();
            $totalPrograms = \App\Models\Program::count();
            $activeProgram = \App\Models\Program::where('status', \App\Enums\ProgramStatus::ACTIVE)->first();

            return view('dashboard.index', compact('totalVolunteers', 'totalChildren', 'totalPrograms', 'activeProgram'));
        })->name('dashboard');

        // Aktivitas & Presensi
        Route::get('/activities', [AttendanceController::class, 'index'])->name('activities.index');
        Route::get('/activities/events/{event}/attendance', [AttendanceController::class, 'show'])->name('activities.attendance');
        Route::post('/activities/events/{event}/self-checkin', [AttendanceController::class, 'selfCheckIn'])->name('activities.self-checkin');

        // Simpan Presensi & Manajemen Pertemuan (Admin & Pengurus)
        Route::middleware('role:admin,pengurus')->group(function () {
            Route::post('/activities/events/{event}/attendance', [AttendanceController::class, 'store'])->name('activities.attendance.store');
            Route::get('/activities/events/create', [EventController::class, 'create'])->name('activities.events.create');
            Route::post('/activities/events', [EventController::class, 'store'])->name('activities.events.store');
            Route::get('/activities/events/{event}/edit', [EventController::class, 'edit'])->name('activities.events.edit');
            Route::put('/activities/events/{event}', [EventController::class, 'update'])->name('activities.events.update');
            Route::delete('/activities/events/{event}', [EventController::class, 'destroy'])->name('activities.events.destroy');
        });

        // Knowledge Hub (Semua Role Bisa Mengakses)
        Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');

        // Teaching Notes
        Route::get('/knowledge/notes/create', [KnowledgeController::class, 'createTeachingNote'])->name('knowledge.notes.create');
        Route::post('/knowledge/notes', [KnowledgeController::class, 'storeTeachingNote'])->name('knowledge.notes.store');
        Route::get('/knowledge/notes/{teachingNote}', [KnowledgeController::class, 'showTeachingNote'])->name('knowledge.notes.show');
        Route::delete('/knowledge/notes/{teachingNote}', [KnowledgeController::class, 'destroyTeachingNote'])->name('knowledge.notes.destroy');

        // Evaluations
        Route::get('/knowledge/evaluations/create', [KnowledgeController::class, 'createEvaluation'])->name('knowledge.evaluations.create');
        Route::post('/knowledge/evaluations', [KnowledgeController::class, 'storeEvaluation'])->name('knowledge.evaluations.store');
        Route::delete('/knowledge/evaluations/{evaluation}', [KnowledgeController::class, 'destroyEvaluation'])->name('knowledge.evaluations.destroy');

        // Handovers
        Route::middleware('role:admin,pengurus')->group(function () {
            Route::get('/knowledge/handovers/create', [KnowledgeController::class, 'createHandover'])->name('knowledge.handovers.create');
            Route::post('/knowledge/handovers', [KnowledgeController::class, 'storeHandover'])->name('knowledge.handovers.store');
            Route::delete('/knowledge/handovers/{handover}', [KnowledgeController::class, 'destroyHandover'])->name('knowledge.handovers.destroy');
        });
        Route::get('/knowledge/handovers/{handover}', [KnowledgeController::class, 'showHandover'])->name('knowledge.handovers.show');
    });
});
