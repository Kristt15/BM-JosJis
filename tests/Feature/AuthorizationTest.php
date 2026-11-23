<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_protected_route(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Bidikmisi Mengajar');
    }

    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->volunteer()->create([
            'email' => 'relawan@bm.pens.ac.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'relawan@bm.pens.ac.id',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->volunteer()->create([
            'email' => 'relawan@bm.pens.ac.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'relawan@bm.pens.ac.id',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->volunteer()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    public function test_volunteer_cannot_access_admin_only_route(): void
    {
        $volunteer = User::factory()->volunteer()->create();

        $response = $this->actingAs($volunteer)->get('/admin/settings');

        $response->assertStatus(403);
    }

    public function test_volunteer_cannot_access_pengurus_route(): void
    {
        $volunteer = User::factory()->volunteer()->create();

        $response = $this->actingAs($volunteer)->get('/people');

        $response->assertStatus(403);
    }

    public function test_volunteer_can_access_volunteer_routes(): void
    {
        $volunteer = User::factory()->volunteer()->create();

        $response = $this->actingAs($volunteer)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($volunteer)->get('/activities');
        $response->assertStatus(200);

        $response = $this->actingAs($volunteer)->get('/knowledge');
        $response->assertStatus(200);
    }

    public function test_pengurus_can_access_pengurus_routes(): void
    {
        $pengurus = User::factory()->pengurus()->create();

        $response = $this->actingAs($pengurus)->get('/people');
        $response->assertStatus(200);

        $response = $this->actingAs($pengurus)->get('/programs');
        $response->assertStatus(200);
    }

    public function test_pengurus_cannot_access_admin_only_route(): void
    {
        $pengurus = User::factory()->pengurus()->create();

        $response = $this->actingAs($pengurus)->get('/admin/settings');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_all_routes(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/people');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
    }
}
