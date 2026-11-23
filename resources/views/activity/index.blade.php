<x-layouts.app title="Aktivitas & Presensi">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Aktivitas & Presensi Mengajar</h1>
            <p class="text-sm text-gray-500 mt-1">Jadwal kegiatan pengajaran di Sekolah Rakyat Kejawan (SRK) dan pencatatan presensi relawan.</p>
        </div>

        @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']))
            <a href="{{ route('activities.events.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-lg text-sm shadow-sm transition">
                <span>+</span>
                <span>Tambah Pertemuan</span>
            </a>
        @endif
    </div>

    <!-- Alert Success / Error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-3">
            <span class="text-emerald-600 font-bold">✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-3">
            <span class="text-red-600 font-bold">✕</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Kegiatan / Event -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 text-base">Daftar Pertemuan & Jadwal</h2>
            <span class="text-xs text-gray-400">Total: {{ $events->total() }} Kegiatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pertemuan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch & Program</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu & Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kehadiran Relawan</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($events as $index => $event)
                        @php
                            $totalBatchVolunteers = $event->batch?->volunteers->count() ?? 0;
                            $presentCount = $event->attendances->where('status', \App\Enums\AttendanceStatus::PRESENT)->count();
                            $lateCount = $event->attendances->where('status', \App\Enums\AttendanceStatus::LATE)->count();
                            $attendanceTotal = $presentCount + $lateCount;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">
                                {{ $events->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-semibold text-sm text-gray-900">{{ $event->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $event->type->label() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $event->batch?->name ?? '-' }}
                                </span>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $event->batch?->phase?->program?->name ?? 'Program BM' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="font-medium text-gray-800">{{ $event->event_date?->format('d M Y') ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $event->event_date?->format('H:i') ?? '08:00' }} WIB</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                📍 {{ $event->location ?? 'SRK Kejawan' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold {{ $attendanceTotal > 0 ? 'text-emerald-600' : 'text-gray-400' }}">
                                        {{ $attendanceTotal }} / {{ $totalBatchVolunteers }}
                                    </span>
                                    <span class="text-xs text-gray-400">Hadir</span>
                                </div>
                                <div class="w-24 bg-gray-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                    @php
                                        $percent = $totalBatchVolunteers > 0 ? min(100, round(($attendanceTotal / $totalBatchVolunteers) * 100)) : 0;
                                    @endphp
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('activities.attendance', $event) }}"
                                       class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium transition shadow-sm"
                                       title="Buka Lembar Presensi">
                                        <span>Presensi</span>
                                        <span>→</span>
                                    </a>

                                    @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']))
                                        <a href="{{ route('activities.events.edit', $event) }}"
                                           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-2.5 py-1.5 rounded-lg text-xs font-medium transition"
                                           title="Edit Jadwal">
                                            ✏️ Edit
                                        </a>

                                        <form method="POST" action="{{ route('activities.events.destroy', $event) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal pertemuan {{ $event->name }}? Seluruh catatan presensi kegiatan ini akan ikut terhapus.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg text-xs font-medium transition"
                                                    title="Hapus Pertemuan">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400">
                                Belum ada jadwal kegiatan pertemuan yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
