<?php

namespace Tests\Unit;

use App\Enums\AttendanceStatus;
use App\Enums\BatchVolunteerRole;
use App\Enums\DocumentationType;
use App\Enums\EvaluationType;
use App\Enums\EventType;
use App\Enums\HandoverType;
use App\Enums\PhaseType;
use App\Enums\ProgramStatus;
use App\Enums\SwapStatus;
use App\Enums\UserRole;
use App\Enums\VolunteerStatus;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Child;
use App\Models\ChildAttendance;
use App\Models\Documentation;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Handover;
use App\Models\MentorAssignment;
use App\Models\Phase;
use App\Models\Program;
use App\Models\ScheduleSwap;
use App\Models\TeachingNote;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_program_hierarchy_relationships(): void
    {
        $program = Program::create([
            'name' => 'BM 2026',
            'year' => 2026,
            'status' => ProgramStatus::ACTIVE,
            'description' => 'Bidikmisi Mengajar 2026',
        ]);

        $phase = Phase::create([
            'program_id' => $program->id,
            'name' => 'Teaching Phase',
            'type' => PhaseType::TEACHING,
            'sequence' => 1,
        ]);

        $batch = Batch::create([
            'phase_id' => $phase->id,
            'name' => 'Batch 1',
        ]);

        $event = Event::create([
            'batch_id' => $batch->id,
            'name' => 'Pertemuan 1',
            'type' => EventType::REGULAR,
            'event_date' => now()->addDays(2),
        ]);

        // Program <-> Phase
        $this->assertTrue($program->phases->contains($phase));
        $this->assertSame($program->id, $phase->program->id);

        // Phase <-> Batch
        $this->assertTrue($phase->batches->contains($batch));
        $this->assertSame($phase->id, $batch->phase->id);

        // Batch <-> Event
        $this->assertTrue($batch->events->contains($event));
        $this->assertSame($batch->id, $event->batch->id);
    }

    public function test_user_and_volunteer_relationships(): void
    {
        $user = User::factory()->volunteer()->create();

        $volunteer = Volunteer::create([
            'user_id' => $user->id,
            'nim' => '1110241001',
            'name' => 'Ahmad Fauzi',
            'major' => 'D4 Teknik Informatika',
            'generation' => '2024',
            'phone' => '081234567890',
            'status' => VolunteerStatus::ACTIVE,
        ]);

        // User <-> Volunteer
        $this->assertSame($volunteer->id, $user->volunteer->id);
        $this->assertSame($user->id, $volunteer->user->id);
    }

    public function test_batch_volunteer_and_batch_child_pivot_relationships(): void
    {
        $program = Program::create(['name' => 'BM 2026', 'year' => 2026]);
        $phase = Phase::create(['program_id' => $program->id, 'name' => 'Phase 1']);
        $batch = Batch::create(['phase_id' => $phase->id, 'name' => 'Batch 1']);

        $volunteer = Volunteer::create([
            'nim' => '1110241002',
            'name' => 'Siti Nurhaliza',
            'major' => 'D3 Teknik Elektro Industri',
            'generation' => '2024',
        ]);

        $child = Child::create([
            'name' => 'Doni Saputra',
            'grade' => '4 SD',
            'school' => 'SDN Kejawan Putih',
        ]);

        // Attach with pivot
        $batch->volunteers()->attach($volunteer->id, ['role' => BatchVolunteerRole::MENTOR->value]);
        $batch->children()->attach($child->id);

        // Batch <-> Volunteer
        $this->assertTrue($batch->volunteers->contains($volunteer));
        $this->assertTrue($volunteer->batches->contains($batch));
        $this->assertSame(BatchVolunteerRole::MENTOR->value, $batch->volunteers->first()->pivot->role);

        // Batch <-> Child
        $this->assertTrue($batch->children->contains($child));
        $this->assertTrue($child->batches->contains($batch));
    }

    public function test_mentor_assignment_bidirectional_relationships(): void
    {
        $program = Program::create(['name' => 'BM 2026', 'year' => 2026]);
        $phase = Phase::create(['program_id' => $program->id, 'name' => 'Phase 1']);
        $batch = Batch::create(['phase_id' => $phase->id, 'name' => 'Batch 1']);

        $volunteer = Volunteer::create([
            'nim' => '1110241003',
            'name' => 'Rian Hidayat',
            'major' => 'D4 Sistem Pembangkit Energi',
            'generation' => '2024',
        ]);

        $child = Child::create([
            'name' => 'Ani Lestari',
            'grade' => '5 SD',
        ]);

        $assignment = MentorAssignment::create([
            'batch_id' => $batch->id,
            'volunteer_id' => $volunteer->id,
            'child_id' => $child->id,
        ]);

        // Batch <-> MentorAssignment
        $this->assertTrue($batch->mentorAssignments->contains($assignment));
        $this->assertSame($batch->id, $assignment->batch->id);

        // Volunteer <-> MentorAssignment
        $this->assertTrue($volunteer->mentorAssignments->contains($assignment));
        $this->assertSame($volunteer->id, $assignment->volunteer->id);

        // Child <-> MentorAssignment
        $this->assertTrue($child->mentorAssignments->contains($assignment));
        $this->assertSame($child->id, $assignment->child->id);
    }

    public function test_attendance_and_child_attendance_relationships(): void
    {
        $program = Program::create(['name' => 'BM 2026', 'year' => 2026]);
        $phase = Phase::create(['program_id' => $program->id, 'name' => 'Phase 1']);
        $batch = Batch::create(['phase_id' => $phase->id, 'name' => 'Batch 1']);
        $event = Event::create([
            'batch_id' => $batch->id,
            'name' => 'Pertemuan 1',
            'type' => EventType::REGULAR,
            'event_date' => now(),
        ]);

        $volunteer = Volunteer::create([
            'nim' => '1110241004',
            'name' => 'Eko Prasetyo',
            'major' => 'D3 Teknik Komputer',
            'generation' => '2024',
        ]);

        $child = Child::create([
            'name' => 'Rizky Ramadan',
        ]);

        $volunteerAttendance = Attendance::create([
            'event_id' => $event->id,
            'volunteer_id' => $volunteer->id,
            'status' => AttendanceStatus::PRESENT,
        ]);

        $childAttendance = ChildAttendance::create([
            'event_id' => $event->id,
            'child_id' => $child->id,
            'status' => AttendanceStatus::PRESENT,
        ]);

        // Event <-> Attendance
        $this->assertTrue($event->attendances->contains($volunteerAttendance));
        $this->assertSame($event->id, $volunteerAttendance->event->id);

        // Volunteer <-> Attendance
        $this->assertTrue($volunteer->attendances->contains($volunteerAttendance));
        $this->assertSame($volunteer->id, $volunteerAttendance->volunteer->id);

        // Event <-> ChildAttendance
        $this->assertTrue($event->childAttendances->contains($childAttendance));
        $this->assertSame($event->id, $childAttendance->event->id);

        // Child <-> ChildAttendance
        $this->assertTrue($child->attendances->contains($childAttendance));
        $this->assertSame($child->id, $childAttendance->child->id);
    }

    public function test_schedule_swap_bidirectional_relationships(): void
    {
        $approver = User::factory()->admin()->create();

        $program = Program::create(['name' => 'BM 2026', 'year' => 2026]);
        $phase = Phase::create(['program_id' => $program->id, 'name' => 'Phase 1']);
        $batch = Batch::create(['phase_id' => $phase->id, 'name' => 'Batch 1']);

        $event1 = Event::create(['batch_id' => $batch->id, 'name' => 'Minggu 1', 'type' => EventType::REGULAR, 'event_date' => now()]);
        $event2 = Event::create(['batch_id' => $batch->id, 'name' => 'Minggu 2', 'type' => EventType::REGULAR, 'event_date' => now()->addWeek()]);

        $volunteer1 = Volunteer::create(['nim' => '1110241005', 'name' => 'Relawan A', 'major' => 'TI', 'generation' => '2024']);
        $volunteer2 = Volunteer::create(['nim' => '1110241006', 'name' => 'Relawan B', 'major' => 'TE', 'generation' => '2024']);

        $swap = ScheduleSwap::create([
            'requester_volunteer_id' => $volunteer1->id,
            'target_volunteer_id' => $volunteer2->id,
            'requester_event_id' => $event1->id,
            'target_event_id' => $event2->id,
            'approved_by' => $approver->id,
            'reason' => 'Ada praktikum pengganti',
            'status' => SwapStatus::APPROVED,
        ]);

        // Volunteers <-> Swap
        $this->assertTrue($volunteer1->requestedSwaps->contains($swap));
        $this->assertTrue($volunteer2->targetSwaps->contains($swap));
        $this->assertSame($volunteer1->id, $swap->requesterVolunteer->id);
        $this->assertSame($volunteer2->id, $swap->targetVolunteer->id);

        // Events <-> Swap
        $this->assertTrue($event1->requestedSwaps->contains($swap));
        $this->assertTrue($event2->targetSwaps->contains($swap));
        $this->assertSame($event1->id, $swap->requesterEvent->id);
        $this->assertSame($event2->id, $swap->targetEvent->id);

        // User <-> Swap
        $this->assertTrue($approver->approvedSwaps->contains($swap));
        $this->assertSame($approver->id, $swap->approver->id);
    }

    public function test_teaching_notes_evaluations_handovers_and_documentations(): void
    {
        $user = User::factory()->pengurus()->create();

        $program = Program::create(['name' => 'BM 2026', 'year' => 2026]);
        $phase = Phase::create(['program_id' => $program->id, 'name' => 'Phase 1']);
        $batch = Batch::create(['phase_id' => $phase->id, 'name' => 'Batch 1']);
        $event = Event::create(['batch_id' => $batch->id, 'name' => 'Pertemuan 1', 'type' => EventType::REGULAR, 'event_date' => now()]);

        $evaluator = Volunteer::create(['nim' => '1110241007', 'name' => 'Evaluator', 'major' => 'TI', 'generation' => '2023']);
        $evaluated = Volunteer::create(['nim' => '1110241008', 'name' => 'Evaluated', 'major' => 'TE', 'generation' => '2024']);

        // Teaching Note
        $note = TeachingNote::create([
            'event_id' => $event->id,
            'volunteer_id' => $evaluator->id,
            'title' => 'Catatan Matematika',
            'content' => 'Materi perkalian pecahan',
        ]);
        $this->assertTrue($event->teachingNotes->contains($note));
        $this->assertTrue($evaluator->teachingNotes->contains($note));
        $this->assertSame($event->id, $note->event->id);
        $this->assertSame($evaluator->id, $note->volunteer->id);

        // Evaluation
        $eval = Evaluation::create([
            'event_id' => $event->id,
            'evaluator_volunteer_id' => $evaluator->id,
            'evaluated_volunteer_id' => $evaluated->id,
            'type' => EvaluationType::INDIVIDUAL,
            'content' => 'Sangat komunikatif dan sabar saat mengajar.',
        ]);
        $this->assertTrue($event->evaluations->contains($eval));
        $this->assertTrue($evaluator->givenEvaluations->contains($eval));
        $this->assertTrue($evaluated->receivedEvaluations->contains($eval));
        $this->assertSame($evaluator->id, $eval->evaluator->id);
        $this->assertSame($evaluated->id, $eval->evaluated->id);

        // Handover
        $handover = Handover::create([
            'program_id' => $program->id,
            'created_by' => $user->id,
            'type' => HandoverType::KETUA,
            'title' => 'LPJ Akhir Kepengurusan',
            'content' => 'Seluruh kegiatan BM 2026 terlaksana sukses.',
        ]);
        $this->assertTrue($program->handovers->contains($handover));
        $this->assertTrue($user->createdHandovers->contains($handover));
        $this->assertSame($program->id, $handover->program->id);
        $this->assertSame($user->id, $handover->creator->id);

        // Documentation
        $doc = Documentation::create([
            'event_id' => $event->id,
            'uploaded_by' => $user->id,
            'type' => DocumentationType::PHOTO,
            'file_path' => 'photos/pertemuan-1.jpg',
            'caption' => 'Foto bersama adik-adik SRK',
        ]);
        $this->assertTrue($event->documentations->contains($doc));
        $this->assertTrue($user->uploadedDocumentations->contains($doc));
        $this->assertSame($event->id, $doc->event->id);
        $this->assertSame($user->id, $doc->uploader->id);
    }

    public function test_soft_deletes_on_volunteer_and_child(): void
    {
        $volunteer = Volunteer::create([
            'nim' => '1110241099',
            'name' => 'Soft Delete Volunteer',
            'major' => 'MMB',
            'generation' => '2024',
        ]);

        $child = Child::create([
            'name' => 'Soft Delete Child',
        ]);

        $volunteer->delete();
        $child->delete();

        $this->assertSoftDeleted('volunteers', ['id' => $volunteer->id]);
        $this->assertSoftDeleted('children', ['id' => $child->id]);

        $this->assertNull(Volunteer::find($volunteer->id));
        $this->assertNotNull(Volunteer::withTrashed()->find($volunteer->id));

        $this->assertNull(Child::find($child->id));
        $this->assertNotNull(Child::withTrashed()->find($child->id));
    }
}
