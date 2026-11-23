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
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnumTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_enum_values_and_labels(): void
    {
        $this->assertSame('admin', UserRole::ADMIN->value);
        $this->assertSame('pengurus', UserRole::PENGURUS->value);
        $this->assertSame('volunteer', UserRole::VOLUNTEER->value);

        $this->assertSame('Admin', UserRole::ADMIN->label());
        $this->assertSame('Pengurus', UserRole::PENGURUS->label());
        $this->assertSame('Volunteer', UserRole::VOLUNTEER->label());
    }

    public function test_program_status_enum_values_and_labels(): void
    {
        $this->assertSame('planning', ProgramStatus::PLANNING->value);
        $this->assertSame('active', ProgramStatus::ACTIVE->value);
        $this->assertSame('completed', ProgramStatus::COMPLETED->value);

        $this->assertSame('Perencanaan', ProgramStatus::PLANNING->label());
        $this->assertSame('Aktif', ProgramStatus::ACTIVE->label());
        $this->assertSame('Selesai', ProgramStatus::COMPLETED->label());
    }

    public function test_attendance_status_enum_values_and_labels(): void
    {
        $this->assertSame('present', AttendanceStatus::PRESENT->value);
        $this->assertSame('absent', AttendanceStatus::ABSENT->value);
        $this->assertSame('late', AttendanceStatus::LATE->value);
        $this->assertSame('excused', AttendanceStatus::EXCUSED->value);

        $this->assertSame('Hadir', AttendanceStatus::PRESENT->label());
        $this->assertSame('Tidak Hadir', AttendanceStatus::ABSENT->label());
        $this->assertSame('Terlambat', AttendanceStatus::LATE->label());
        $this->assertSame('Izin', AttendanceStatus::EXCUSED->label());
    }

    public function test_volunteer_status_enum(): void
    {
        $this->assertSame('active', VolunteerStatus::ACTIVE->value);
        $this->assertSame('alumni', VolunteerStatus::ALUMNI->value);
        $this->assertSame('Aktif', VolunteerStatus::ACTIVE->label());
        $this->assertSame('Alumni', VolunteerStatus::ALUMNI->label());
    }

    public function test_phase_type_enum(): void
    {
        $this->assertSame('teaching', PhaseType::TEACHING->value);
        $this->assertSame('closing', PhaseType::CLOSING->value);
        $this->assertSame('special', PhaseType::SPECIAL->value);
        $this->assertSame('Fase Mengajar', PhaseType::TEACHING->label());
        $this->assertSame('Fase Penutupan', PhaseType::CLOSING->label());
        $this->assertSame('Fase Khusus', PhaseType::SPECIAL->label());
    }

    public function test_event_type_enum(): void
    {
        $this->assertSame('regular', EventType::REGULAR->value);
        $this->assertSame('special', EventType::SPECIAL->value);
        $this->assertSame('aksi', EventType::AKSI->value);
        $this->assertSame('Pertemuan Rutin', EventType::REGULAR->label());
        $this->assertSame('Kegiatan Khusus', EventType::SPECIAL->label());
        $this->assertSame('BM AKSI', EventType::AKSI->label());
    }

    public function test_batch_volunteer_role_enum(): void
    {
        $this->assertSame('mentor', BatchVolunteerRole::MENTOR->value);
        $this->assertSame('assistant', BatchVolunteerRole::ASSISTANT->value);
        $this->assertSame('Mentor', BatchVolunteerRole::MENTOR->label());
        $this->assertSame('Asisten', BatchVolunteerRole::ASSISTANT->label());
    }

    public function test_swap_status_enum(): void
    {
        $this->assertSame('pending', SwapStatus::PENDING->value);
        $this->assertSame('approved', SwapStatus::APPROVED->value);
        $this->assertSame('rejected', SwapStatus::REJECTED->value);
        $this->assertSame('Menunggu Persetujuan', SwapStatus::PENDING->label());
        $this->assertSame('Disetujui', SwapStatus::APPROVED->label());
        $this->assertSame('Ditolak', SwapStatus::REJECTED->label());
    }

    public function test_evaluation_type_enum(): void
    {
        $this->assertSame('individual', EvaluationType::INDIVIDUAL->value);
        $this->assertSame('group', EvaluationType::GROUP->value);
        $this->assertSame('Individu', EvaluationType::INDIVIDUAL->label());
        $this->assertSame('Kelompok', EvaluationType::GROUP->label());
    }

    public function test_handover_type_enum(): void
    {
        $this->assertSame('ketua', HandoverType::KETUA->value);
        $this->assertSame('sekretaris', HandoverType::SEKRETARIS->value);
        $this->assertSame('koordinator', HandoverType::KOORDINATOR->value);
        $this->assertSame('Ketua', HandoverType::KETUA->label());
        $this->assertSame('Sekretaris', HandoverType::SEKRETARIS->label());
        $this->assertSame('Koordinator', HandoverType::KOORDINATOR->label());
    }

    public function test_documentation_type_enum(): void
    {
        $this->assertSame('photo', DocumentationType::PHOTO->value);
        $this->assertSame('video', DocumentationType::VIDEO->value);
        $this->assertSame('Foto', DocumentationType::PHOTO->label());
        $this->assertSame('Video', DocumentationType::VIDEO->label());
    }

    public function test_user_model_casts_role_to_enum(): void
    {
        $user = new User([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);
        $user->role = UserRole::ADMIN;

        $this->assertInstanceOf(UserRole::class, $user->role);
        $this->assertSame(UserRole::ADMIN, $user->role);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isPengurus());
        $this->assertFalse($user->isVolunteer());
    }

    public function test_user_role_helper_methods(): void
    {
        $admin = new User(['name' => 'Admin']);
        $admin->role = UserRole::ADMIN;
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isPengurus());
        $this->assertFalse($admin->isVolunteer());

        $pengurus = new User(['name' => 'Pengurus']);
        $pengurus->role = UserRole::PENGURUS;
        $this->assertFalse($pengurus->isAdmin());
        $this->assertTrue($pengurus->isPengurus());
        $this->assertFalse($pengurus->isVolunteer());

        $volunteer = new User(['name' => 'Volunteer']);
        $volunteer->role = UserRole::VOLUNTEER;
        $this->assertFalse($volunteer->isAdmin());
        $this->assertFalse($volunteer->isPengurus());
        $this->assertTrue($volunteer->isVolunteer());
    }

    public function test_role_is_guarded_against_mass_assignment(): void
    {
        $user = new User([
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'password',
            'role' => UserRole::ADMIN->value,
        ]);

        $this->assertNull($user->role);
    }

    public function test_user_factory_states(): void
    {
        $admin = User::factory()->admin()->make();
        $this->assertSame(UserRole::ADMIN, $admin->role);
        $this->assertTrue($admin->isAdmin());

        $pengurus = User::factory()->pengurus()->make();
        $this->assertSame(UserRole::PENGURUS, $pengurus->role);
        $this->assertTrue($pengurus->isPengurus());

        $volunteer = User::factory()->volunteer()->make();
        $this->assertSame(UserRole::VOLUNTEER, $volunteer->role);
        $this->assertTrue($volunteer->isVolunteer());

        $defaultUser = User::factory()->make();
        $this->assertSame(UserRole::VOLUNTEER, $defaultUser->role);
        $this->assertTrue($defaultUser->isVolunteer());
    }
}
