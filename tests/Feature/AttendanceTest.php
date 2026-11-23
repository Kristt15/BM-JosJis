<?php

namespace Tests\Feature;

use App\Enums\AttendanceStatus;
use App\Models\Event;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private User $pengurus;
    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->pengurus = User::where('role', 'pengurus')->first();
        $this->event = Event::first();
    }

    public function test_user_can_view_activities_index(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('activities.index'));
        $response->assertStatus(200);
        $response->assertSee('Presensi Mengajar');
        $response->assertSee($this->event->name);
    }

    public function test_user_can_view_event_attendance_sheet(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('activities.attendance', $this->event));
        $response->assertStatus(200);
        $response->assertSee('Lembar Presensi Relawan');
        $response->assertSee($this->event->name);
    }

    public function test_pengurus_can_save_batch_attendance(): void
    {
        $volunteers = $this->event->batch->volunteers;
        $this->assertNotEmpty($volunteers);

        $firstVolunteer = $volunteers->first();

        $payload = [
            'attendances' => [
                $firstVolunteer->id => [
                    'status' => 'present',
                    'notes' => 'Hadir tepat waktu',
                ],
            ],
        ];

        $response = $this->actingAs($this->pengurus)
            ->post(route('activities.attendance.store', $this->event), $payload);

        $response->assertRedirect(route('activities.attendance', $this->event));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'event_id' => $this->event->id,
            'volunteer_id' => $firstVolunteer->id,
            'status' => AttendanceStatus::PRESENT->value,
            'notes' => 'Hadir tepat waktu',
        ]);
    }

    public function test_assigned_volunteer_can_self_check_in(): void
    {
        $assignedVolunteer = $this->event->batch->volunteers->first();
        $volunteerUser = $assignedVolunteer->user;

        $response = $this->actingAs($volunteerUser)
            ->post(route('activities.self-checkin', $this->event));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'event_id' => $this->event->id,
            'volunteer_id' => $assignedVolunteer->id,
            'status' => AttendanceStatus::PRESENT->value,
        ]);
    }
}
