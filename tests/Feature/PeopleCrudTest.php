<?php

namespace Tests\Feature;

use App\Models\Child;
use App\Models\Program;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeopleCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $pengurus;
    private Volunteer $volunteer;
    private Child $child;
    private Program $program;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->pengurus = User::where('role', 'pengurus')->first();
        $this->volunteer = Volunteer::first();
        $this->child = Child::first();
        $this->program = Program::first();
    }

    public function test_pengurus_can_view_volunteers_index(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('volunteers.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Volunteer');
    }

    public function test_pengurus_can_view_volunteer_create_form(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('volunteers.create'));
        $response->assertStatus(200);
    }

    public function test_pengurus_can_view_volunteer_detail(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('volunteers.show', $this->volunteer));
        $response->assertStatus(200);
    }

    public function test_pengurus_can_view_children_index(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('children.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Anak SRK');
    }

    public function test_pengurus_can_view_child_create_form(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('children.create'));
        $response->assertStatus(200);
    }

    public function test_pengurus_can_view_child_detail(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('children.show', $this->child));
        $response->assertStatus(200);
    }

    public function test_pengurus_can_view_programs_index(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('programs.index'));
        $response->assertStatus(200);
        $response->assertSee('Program Bidikmisi Mengajar');
    }

    public function test_pengurus_can_view_program_detail(): void
    {
        $response = $this->actingAs($this->pengurus)->get(route('programs.show', $this->program));
        $response->assertStatus(200);
    }
}
