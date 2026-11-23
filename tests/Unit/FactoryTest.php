<?php

namespace Tests\Unit;

use App\Enums\EventType;
use App\Enums\PhaseType;
use App\Enums\ProgramStatus;
use App\Enums\VolunteerStatus;
use App\Models\Batch;
use App\Models\Child;
use App\Models\Event;
use App\Models\Phase;
use App\Models\Program;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_volunteer_factory_creates_record(): void
    {
        $volunteer = Volunteer::factory()->create();

        $this->assertDatabaseHas('volunteers', ['id' => $volunteer->id]);
        $this->assertSame(VolunteerStatus::ACTIVE, $volunteer->status);
        $this->assertNotEmpty($volunteer->nim);
        $this->assertNotEmpty($volunteer->major);
    }

    public function test_child_factory_creates_record(): void
    {
        $child = Child::factory()->create();

        $this->assertDatabaseHas('children', ['id' => $child->id]);
        $this->assertNotEmpty($child->name);
        $this->assertNotEmpty($child->grade);
        $this->assertNotEmpty($child->school);
    }

    public function test_program_factory_creates_record(): void
    {
        $program = Program::factory()->active()->create();

        $this->assertDatabaseHas('programs', ['id' => $program->id]);
        $this->assertSame(ProgramStatus::ACTIVE, $program->status);
    }

    public function test_phase_factory_creates_record(): void
    {
        $phase = Phase::factory()->teaching()->create();

        $this->assertDatabaseHas('phases', ['id' => $phase->id]);
        $this->assertSame(PhaseType::TEACHING, $phase->type);
    }

    public function test_batch_factory_creates_record(): void
    {
        $batch = Batch::factory()->create();

        $this->assertDatabaseHas('batches', ['id' => $batch->id]);
        $this->assertNotEmpty($batch->name);
    }

    public function test_event_factory_creates_record(): void
    {
        $event = Event::factory()->regular()->create();

        $this->assertDatabaseHas('events', ['id' => $event->id]);
        $this->assertSame(EventType::REGULAR, $event->type);
        $this->assertSame('Sekolah Rakyat Kejawan', $event->location);
    }
}
