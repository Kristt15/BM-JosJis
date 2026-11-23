<?php

namespace Tests\Feature;

use App\Enums\EventType;
use App\Models\Batch;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $pengurus;
    private User $volunteerUser;
    private Batch $batch;
    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->pengurus = User::where('role', 'pengurus')->first();
        $this->volunteerUser = User::where('role', 'volunteer')->first();
        $this->batch = Batch::first();
        $this->event = Event::first();
    }

    public function test_pengurus_can_view_event_create_form(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('activities.events.create'));
        $response->assertStatus(200);
        $response->assertSee('Jadwalkan Pertemuan Baru');
    }

    public function test_pengurus_can_store_new_event(): void
    {
        $payload = [
            'batch_id' => $this->batch->id,
            'name' => 'Pertemuan Khusus Robotik',
            'type' => 'special',
            'event_date' => '2026-05-10',
            'event_time' => '09:30',
            'location' => 'Lab Komputer PENS',
            'description' => 'Mengenalkan coding scratch dasar untuk anak-anak SRK.',
        ];

        $response = $this->actingAs($this->pengurus)->post(route('activities.events.store'), $payload);

        $response->assertRedirect(route('activities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'name' => 'Pertemuan Khusus Robotik',
            'location' => 'Lab Komputer PENS',
            'type' => EventType::SPECIAL->value,
        ]);
    }

    public function test_pengurus_can_view_event_edit_form(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('activities.events.edit', $this->event));
        $response->assertStatus(200);
        $response->assertSee('Ubah Jadwal Pertemuan');
        $response->assertSee($this->event->name);
    }

    public function test_pengurus_can_update_event(): void
    {
        $payload = [
            'batch_id' => $this->batch->id,
            'name' => 'Pertemuan 1 - Revisi Jadwal',
            'type' => 'regular',
            'event_date' => '2026-04-05',
            'event_time' => '10:00',
            'location' => 'Aula SRK Baru',
            'description' => 'Materi dipindah ke aula.',
        ];

        $response = $this->actingAs($this->pengurus)->put(route('activities.events.update', $this->event), $payload);

        $response->assertRedirect(route('activities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'id' => $this->event->id,
            'name' => 'Pertemuan 1 - Revisi Jadwal',
            'location' => 'Aula SRK Baru',
        ]);
    }

    public function test_pengurus_can_delete_event(): void
    {
        $response = $this->actingAs($this->pengurus)->delete(route('activities.events.destroy', $this->event));

        $response->assertRedirect(route('activities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('events', [
            'id' => $this->event->id,
        ]);
    }

    public function test_volunteer_cannot_manage_events(): void
    {
        $response = $this->actingAs($this->volunteerUser)->get(route('activities.events.create'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->volunteerUser)->delete(route('activities.events.destroy', $this->event));
        $response->assertStatus(403);
    }
}
