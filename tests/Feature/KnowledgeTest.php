<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Handover;
use App\Models\Program;
use App\Models\TeachingNote;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeTest extends TestCase
{
    use RefreshDatabase;

    private User $pengurus;
    private User $volunteerUser;
    private Event $event;
    private Program $program;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->pengurus = User::where('role', 'pengurus')->first();
        $this->volunteerUser = User::where('role', 'volunteer')->first();
        $this->event = Event::first();
        $this->program = Program::first();
    }

    public function test_user_can_view_knowledge_hub(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('knowledge.index'));
        $response->assertStatus(200);
        $response->assertSee('Memori Organisasi');
        $response->assertSee('Catatan Mengajar');
    }

    public function test_user_can_create_teaching_note(): void
    {
        $response = $this->actingAs($this->volunteerUser)->get(route('knowledge.notes.create'));
        $response->assertStatus(200);

        $payload = [
            'event_id' => $this->event->id,
            'title' => 'Belajar Operasi Perkalian Dasar',
            'materials_used' => 'Kelereng dan gelas plastik',
            'content' => 'Anak-anak sangat antusias mempraktikkan konsep penjumlahan berulang.',
        ];

        $storeResponse = $this->actingAs($this->volunteerUser)
            ->post(route('knowledge.notes.store'), $payload);

        $storeResponse->assertRedirect(route('knowledge.index', ['tab' => 'notes']));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('teaching_notes', [
            'title' => 'Belajar Operasi Perkalian Dasar',
            'event_id' => $this->event->id,
        ]);
    }

    public function test_user_can_view_teaching_note_detail(): void
    {
        $note = TeachingNote::first();

        $response = $this->actingAs($this->volunteerUser)->get(route('knowledge.notes.show', $note));
        $response->assertStatus(200);
        $response->assertSee($note->title);
    }

    public function test_pengurus_can_create_handover_document(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('knowledge.handovers.create'));
        $response->assertStatus(200);

        $payload = [
            'program_id' => $this->program->id,
            'type' => 'ketua',
            'title' => 'Catatan Serah Terima Kepengurusan 2026',
            'content' => 'Berikut adalah daftar kontak kepala sekolah dan arsip proposal.',
        ];

        $storeResponse = $this->actingAs($this->pengurus)
            ->post(route('knowledge.handovers.store'), $payload);

        $storeResponse->assertRedirect(route('knowledge.index', ['tab' => 'handovers']));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('handovers', [
            'title' => 'Catatan Serah Terima Kepengurusan 2026',
            'type' => 'ketua',
        ]);
    }

    public function test_volunteer_cannot_create_handover(): void
    {
        $response = $this->actingAs($this->volunteerUser)->get(route('knowledge.handovers.create'));
        $response->assertStatus(403);
    }

    public function test_user_can_create_evaluation(): void
    {
        $response = $this->actingAs($this->volunteerUser)->get(route('knowledge.evaluations.create'));
        $response->assertStatus(200);

        $payload = [
            'event_id' => $this->event->id,
            'type' => 'group',
            'content' => 'Suasana kelas kondusif, anak-anak memahami materi dengan baik.',
        ];

        $storeResponse = $this->actingAs($this->volunteerUser)
            ->post(route('knowledge.evaluations.store'), $payload);

        $storeResponse->assertRedirect(route('knowledge.index', ['tab' => 'evaluations']));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('evaluations', [
            'content' => 'Suasana kelas kondusif, anak-anak memahami materi dengan baik.',
            'type' => 'group',
        ]);
    }
}
