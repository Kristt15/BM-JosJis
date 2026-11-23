<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Ambil data event lengkap beserta batch, volunteers, dan data presensi yang sudah tercatat.
     */
    public function getEventAttendanceData(Event $event): array
    {
        $event->load(['batch.volunteers.user', 'attendances']);

        $volunteers = $event->batch?->volunteers ?? new Collection();
        $attendances = $event->attendances->keyBy('volunteer_id');

        return [
            'event' => $event,
            'volunteers' => $volunteers,
            'attendances' => $attendances,
        ];
    }

    /**
     * Simpan / perbarui presensi beberapa relawan sekaligus (oleh pengurus/admin).
     */
    public function saveBatchAttendance(Event $event, array $attendanceData): void
    {
        DB::transaction(function () use ($event, $attendanceData) {
            foreach ($attendanceData as $volunteerId => $data) {
                if (empty($data['status'])) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'volunteer_id' => $volunteerId,
                    ],
                    [
                        'status' => $data['status'],
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }
        });
    }

    /**
     * Self check-in langsung oleh relawan.
     */
    public function selfCheckIn(Event $event, Volunteer $volunteer, ?string $notes = null): Attendance
    {
        return Attendance::updateOrCreate(
            [
                'event_id' => $event->id,
                'volunteer_id' => $volunteer->id,
            ],
            [
                'status' => AttendanceStatus::PRESENT,
                'notes' => $notes ?? 'Presensi mandiri oleh relawan',
            ]
        );
    }
}
