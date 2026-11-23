<?php

namespace Database\Seeders;

use App\Enums\AttendanceStatus;
use App\Enums\BatchVolunteerRole;
use App\Enums\EvaluationType;
use App\Enums\EventType;
use App\Enums\HandoverType;
use App\Enums\PhaseType;
use App\Enums\ProgramStatus;
use App\Enums\UserRole;
use App\Enums\VolunteerStatus;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Child;
use App\Models\ChildAttendance;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Handover;
use App\Models\MentorAssignment;
use App\Models\Phase;
use App\Models\Program;
use App\Models\TeachingNote;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Tiga User Admin/Pengurus
            $adminUser = User::create([
                'name' => 'Admin BM PENS',
                'email' => 'admin@bm.pens.ac.id',
                'password' => Hash::make('password'),
            ]);
            $adminUser->role = UserRole::ADMIN;
            $adminUser->save();

            $ketuaUser = User::create([
                'name' => 'Ketua Pengurus BM',
                'email' => 'ketua@bm.pens.ac.id',
                'password' => Hash::make('password'),
            ]);
            $ketuaUser->role = UserRole::PENGURUS;
            $ketuaUser->save();

            $sekretarisUser = User::create([
                'name' => 'Sekretaris Pengurus BM',
                'email' => 'sekretaris@bm.pens.ac.id',
                'password' => Hash::make('password'),
            ]);
            $sekretarisUser->role = UserRole::PENGURUS;
            $sekretarisUser->save();

            // 2. 15 User Volunteer + Profile Volunteer
            $volunteerData = [
                ['name' => 'Ahmad Fauzan', 'major' => 'D4 Teknik Informatika', 'gen' => '2023'],
                ['name' => 'Bintang Pratama', 'major' => 'D4 Sains Data Terapan', 'gen' => '2023'],
                ['name' => 'Citra Dewi', 'major' => 'D3 Teknik Telekomunikasi', 'gen' => '2024'],
                ['name' => 'Dimas Arya', 'major' => 'D4 Teknik Mekatronika', 'gen' => '2024'],
                ['name' => 'Eka Rahmawati', 'major' => 'D3 Teknik Elektronika', 'gen' => '2024'],
                ['name' => 'Fajar Nugraha', 'major' => 'D4 Teknik Informatika', 'gen' => '2023'],
                ['name' => 'Gita Permata', 'major' => 'D4 Teknologi Rekayasa Multimedia', 'gen' => '2024'],
                ['name' => 'Hadi Kusuma', 'major' => 'D3 Teknik Komputer', 'gen' => '2024'],
                ['name' => 'Indah Lestari', 'major' => 'D4 Teknik Elektro Industri', 'gen' => '2023'],
                ['name' => 'Joko Santoso', 'major' => 'D3 Teknik Telekomunikasi', 'gen' => '2024'],
                ['name' => 'Kartika Sari', 'major' => 'D4 Sains Data Terapan', 'gen' => '2024'],
                ['name' => 'Lukman Hakim', 'major' => 'D4 Teknik Mekatronika', 'gen' => '2023'],
                ['name' => 'Mega Utami', 'major' => 'D4 Teknologi Rekayasa Multimedia', 'gen' => '2024'],
                ['name' => 'Naufal Rizky', 'major' => 'D3 Teknik Informatika', 'gen' => '2024'],
                ['name' => 'Olivia Putri', 'major' => 'D3 Teknik Komputer', 'gen' => '2024'],
            ];

            $volunteers = [];
            foreach ($volunteerData as $index => $item) {
                $num = $index + 1;
                $user = User::create([
                    'name' => $item['name'],
                    'email' => "volunteer{$num}@bm.pens.ac.id",
                    'password' => Hash::make('password'),
                ]);
                $user->role = UserRole::VOLUNTEER;
                $user->save();

                $nimSuffix = str_pad((string) $num, 4, '0', STR_PAD_LEFT);
                $volunteers[] = Volunteer::create([
                    'user_id' => $user->id,
                    'nim' => "312360{$nimSuffix}",
                    'name' => $item['name'],
                    'major' => $item['major'],
                    'generation' => $item['gen'],
                    'phone' => '0812' . str_pad((string) (10000000 + $num), 8, '0', STR_PAD_LEFT),
                    'status' => VolunteerStatus::ACTIVE,
                ]);
            }

            // 3. 20 Anak SRK (Sekolah Rakyat Kejawan)
            $childrenNames = [
                ['name' => 'Alif Ramadhan', 'nick' => 'Alif', 'gender' => 'L', 'grade' => 'Kelas 2 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Bella Safitri', 'nick' => 'Bella', 'gender' => 'P', 'grade' => 'Kelas 3 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Cahyo Wicaksono', 'nick' => 'Cahyo', 'gender' => 'L', 'grade' => 'Kelas 4 SD', 'school' => 'SDN Kejawan Putih II'],
                ['name' => 'Dinda Kirana', 'nick' => 'Dinda', 'gender' => 'P', 'grade' => 'Kelas 1 SD', 'school' => 'MI Al-Hidayah Kejawan'],
                ['name' => 'Erlangga Putra', 'nick' => 'Angga', 'gender' => 'L', 'grade' => 'Kelas 5 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Fania Zahra', 'nick' => 'Fania', 'gender' => 'P', 'grade' => 'Kelas 2 SD', 'school' => 'SDN Kejawan Putih II'],
                ['name' => 'Gilang Pratama', 'nick' => 'Gilang', 'gender' => 'L', 'grade' => 'Kelas 6 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Hanifa Nurul', 'nick' => 'Hani', 'gender' => 'P', 'grade' => 'Kelas 3 SD', 'school' => 'MI Al-Hidayah Kejawan'],
                ['name' => 'Irfan Maulana', 'nick' => 'Irfan', 'gender' => 'L', 'grade' => 'Kelas 4 SD', 'school' => 'SDN Kejawan Putih II'],
                ['name' => 'Jelita Maharani', 'nick' => 'Jelita', 'gender' => 'P', 'grade' => 'Kelas 5 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Kiki Saputra', 'nick' => 'Kiki', 'gender' => 'L', 'grade' => 'Kelas 1 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Lala Octavia', 'nick' => 'Lala', 'gender' => 'P', 'grade' => 'Kelas 3 SD', 'school' => 'SDN Kejawan Putih II'],
                ['name' => 'Miko Hendrawan', 'nick' => 'Miko', 'gender' => 'L', 'grade' => 'Kelas 2 SD', 'school' => 'MI Al-Hidayah Kejawan'],
                ['name' => 'Nadya Amanda', 'nick' => 'Nadya', 'gender' => 'P', 'grade' => 'Kelas 4 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Oki Setiawan', 'nick' => 'Oki', 'gender' => 'L', 'grade' => 'Kelas 6 SD', 'school' => 'SDN Kejawan Putih II'],
                ['name' => 'Pipit Triana', 'nick' => 'Pipit', 'gender' => 'P', 'grade' => 'Kelas 5 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Qori Ananta', 'nick' => 'Qori', 'gender' => 'L', 'grade' => 'Kelas 3 SD', 'school' => 'MI Al-Hidayah Kejawan'],
                ['name' => 'Rendra Bagus', 'nick' => 'Rendra', 'gender' => 'L', 'grade' => 'Kelas 4 SD', 'school' => 'SDN Kejawan Putih I'],
                ['name' => 'Salma Amira', 'nick' => 'Salma', 'gender' => 'P', 'grade' => 'Kelas 2 SD', 'school' => 'SDN Kejawan Putih II'],
                ['name' => 'Tio Pratama', 'nick' => 'Tio', 'gender' => 'L', 'grade' => 'Kelas 5 SD', 'school' => 'SDN Kejawan Putih I'],
            ];

            $children = [];
            foreach ($childrenNames as $i => $childItem) {
                $num = $i + 1;
                $children[] = Child::create([
                    'name' => $childItem['name'],
                    'nickname' => $childItem['nick'],
                    'birth_date' => now()->subYears(6 + ($i % 6))->format('Y-m-d'),
                    'gender' => $childItem['gender'],
                    'grade' => $childItem['grade'],
                    'school' => $childItem['school'],
                    'address' => "Jl. Kejawan Putih Tambak No. {$num}, Mulyorejo, Surabaya",
                    'notes' => "Wali: Orang Tua {$childItem['nick']} (08130000" . str_pad((string) $num, 4, '0', STR_PAD_LEFT) . ')',
                ]);
            }

            // 4. Program "Bidikmisi Mengajar 2026" (ACTIVE)
            $program = Program::create([
                'name' => 'Bidikmisi Mengajar 2026',
                'year' => 2026,
                'status' => ProgramStatus::ACTIVE,
                'description' => 'Program pengabdian masyarakat mengajar adik-adik Sekolah Rakyat Kejawan (SRK) tahun 2026.',
                'start_date' => '2026-01-15',
                'end_date' => '2026-12-20',
            ]);

            // 5. 2 Phase: Teaching Phase & Closing Phase
            $teachingPhase = Phase::create([
                'program_id' => $program->id,
                'name' => 'Teaching Phase',
                'type' => PhaseType::TEACHING,
                'sequence' => 1,
                'start_date' => '2026-02-01',
                'end_date' => '2026-05-31',
            ]);

            $closingPhase = Phase::create([
                'program_id' => $program->id,
                'name' => 'Closing Phase',
                'type' => PhaseType::CLOSING,
                'sequence' => 2,
                'start_date' => '2026-06-01',
                'end_date' => '2026-06-30',
            ]);

            // 6. 2 Batch per Teaching Phase
            $batch1 = Batch::create([
                'phase_id' => $teachingPhase->id,
                'name' => 'Batch 1',
                'start_date' => '2026-02-01',
                'end_date' => '2026-03-31',
            ]);

            $batch2 = Batch::create([
                'phase_id' => $teachingPhase->id,
                'name' => 'Batch 2',
                'start_date' => '2026-04-01',
                'end_date' => '2026-05-31',
            ]);

            // 7. 4 Event per Batch
            $batch1Events = [];
            $eventDates1 = ['2026-02-08', '2026-02-15', '2026-02-22', '2026-03-01'];
            foreach ($eventDates1 as $idx => $date) {
                $num = $idx + 1;
                $batch1Events[] = Event::create([
                    'batch_id' => $batch1->id,
                    'name' => "Pertemuan {$num}",
                    'type' => EventType::REGULAR,
                    'event_date' => "{$date} 09:00:00",
                    'location' => 'Sekolah Rakyat Kejawan',
                    'description' => "Kegiatan belajar mengajar rutin Batch 1 pertemuan ke-{$num}.",
                ]);
            }

            $batch2Events = [];
            $eventDates2 = ['2026-04-05', '2026-04-12', '2026-04-19', '2026-04-26'];
            foreach ($eventDates2 as $idx => $date) {
                $num = $idx + 1;
                $batch2Events[] = Event::create([
                    'batch_id' => $batch2->id,
                    'name' => "Pertemuan {$num}",
                    'type' => EventType::REGULAR,
                    'event_date' => "{$date} 09:00:00",
                    'location' => 'Sekolah Rakyat Kejawan',
                    'description' => "Kegiatan belajar mengajar rutin Batch 2 pertemuan ke-{$num}.",
                ]);
            }

            // 8. Plotting:
            // Batch 1: Volunteers 0..7 (6 mentors, 2 assistants), Children 0..9
            for ($v = 0; $v < 8; $v++) {
                $role = ($v < 6) ? BatchVolunteerRole::MENTOR : BatchVolunteerRole::ASSISTANT;
                $batch1->volunteers()->attach($volunteers[$v]->id, ['role' => $role->value]);
            }
            for ($c = 0; $c < 10; $c++) {
                $batch1->children()->attach($children[$c]->id);
            }
            // Mentor assignments Batch 1: distribute 10 children across 6 mentors (volunteers 0..5)
            for ($c = 0; $c < 10; $c++) {
                $assignedVolunteer = $volunteers[$c % 6];
                MentorAssignment::create([
                    'batch_id' => $batch1->id,
                    'volunteer_id' => $assignedVolunteer->id,
                    'child_id' => $children[$c]->id,
                ]);
            }

            // Batch 2: Volunteers 7..14 (6 mentors, 2 assistants), Children 10..19
            for ($v = 7; $v < 15; $v++) {
                $role = ($v < 13) ? BatchVolunteerRole::MENTOR : BatchVolunteerRole::ASSISTANT;
                $batch2->volunteers()->attach($volunteers[$v]->id, ['role' => $role->value]);
            }
            for ($c = 10; $c < 20; $c++) {
                $batch2->children()->attach($children[$c]->id);
            }
            // Mentor assignments Batch 2: distribute 10 children across 6 mentors (volunteers 7..12)
            for ($c = 10; $c < 20; $c++) {
                $assignedVolunteer = $volunteers[7 + (($c - 10) % 6)];
                MentorAssignment::create([
                    'batch_id' => $batch2->id,
                    'volunteer_id' => $assignedVolunteer->id,
                    'child_id' => $children[$c]->id,
                ]);
            }

            // 9. Presensi untuk Pertemuan 1 & 2 di Batch 1
            $event1 = $batch1Events[0];
            $event2 = $batch1Events[1];

            // Presensi Event 1
            for ($v = 0; $v < 8; $v++) {
                Attendance::create([
                    'event_id' => $event1->id,
                    'volunteer_id' => $volunteers[$v]->id,
                    'status' => ($v === 2) ? AttendanceStatus::LATE : AttendanceStatus::PRESENT,
                    'notes' => ($v === 2) ? 'Terlambat 15 menit karena ban bocor' : null,
                ]);
            }
            for ($c = 0; $c < 10; $c++) {
                ChildAttendance::create([
                    'event_id' => $event1->id,
                    'child_id' => $children[$c]->id,
                    'status' => ($c === 4) ? AttendanceStatus::ABSENT : AttendanceStatus::PRESENT,
                    'notes' => ($c === 4) ? 'Sakit demam' : null,
                ]);
            }

            // Presensi Event 2
            for ($v = 0; $v < 8; $v++) {
                Attendance::create([
                    'event_id' => $event2->id,
                    'volunteer_id' => $volunteers[$v]->id,
                    'status' => ($v === 5) ? AttendanceStatus::EXCUSED : AttendanceStatus::PRESENT,
                    'notes' => ($v === 5) ? 'Izin mengikuti lomba robotika' : null,
                ]);
            }
            for ($c = 0; $c < 10; $c++) {
                ChildAttendance::create([
                    'event_id' => $event2->id,
                    'child_id' => $children[$c]->id,
                    'status' => AttendanceStatus::PRESENT,
                ]);
            }

            // 10. Sample Teaching Notes & Evaluasi
            TeachingNote::create([
                'event_id' => $event1->id,
                'volunteer_id' => $volunteers[0]->id,
                'title' => 'Pengenalan Matematika Dasar & Operasi Hitung',
                'content' => 'Adik-adik belajar penjumlahan dan pengurangan dengan alat bantu stick es krim. Semua antusias dan aktif menjawab.',
                'materials_used' => 'Stick es krim warna-warni, modul matematika dasar kelas 2-3.',
            ]);

            TeachingNote::create([
                'event_id' => $event2->id,
                'volunteer_id' => $volunteers[1]->id,
                'title' => 'Membaca dan Menceritakan Kembali Kisah Teladan',
                'content' => 'Fokus pada literasi dan pemahaman bacaan. Alif dan Bella sangat percaya diri maju bercerita di depan kelas.',
                'materials_used' => 'Buku cerita bergambar dari perpustakaan mini BM.',
            ]);

            Evaluation::create([
                'event_id' => $event1->id,
                'evaluator_volunteer_id' => $volunteers[0]->id,
                'evaluated_volunteer_id' => $volunteers[1]->id,
                'type' => EvaluationType::INDIVIDUAL,
                'content' => 'Kerja sama tim sangat baik. Kak Bintang mampu mengkondisikan adik-adik yang ramai dengan sabar.',
            ]);

            Evaluation::create([
                'event_id' => $event2->id,
                'evaluator_volunteer_id' => $volunteers[1]->id,
                'evaluated_volunteer_id' => $volunteers[0]->id,
                'type' => EvaluationType::INDIVIDUAL,
                'content' => 'Penyampaian materi Kak Ahmad sangat komunikatif dan mudah dipahami adik-adik.',
            ]);

            Handover::create([
                'program_id' => $program->id,
                'created_by' => $ketuaUser->id,
                'type' => HandoverType::KETUA,
                'title' => 'Laporan Serah Terima Awal Kepengurusan BM 2026',
                'content' => 'Dokumen serah terima inventaris Sekolah Rakyat Kejawan dan modul ajar dari kepengurusan 2025 ke 2026.',
            ]);
        });
    }
}
