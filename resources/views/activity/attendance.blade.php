<x-layouts.app title="Presensi - {{ $event->name }}">
    <!-- Breadcrumb & Back -->
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
            <span>←</span>
            <span>Kembali ke Daftar Aktivitas</span>
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-3">
            <span class="text-emerald-600 font-bold text-base">✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-3">
            <span class="text-red-600 font-bold text-base">✕</span>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Card Ringkasan Pertemuan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $event->type->label() }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                        {{ $event->batch?->name ?? 'Batch' }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $event->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $event->batch?->phase?->program?->name ?? 'Program BM' }} • {{ $event->description ?? 'Kegiatan Pengajaran SRK' }}
                </p>
            </div>

            <div class="flex flex-wrap gap-4 text-sm text-gray-600 border-t md:border-t-0 pt-4 md:pt-0">
                <div class="bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                    <div class="text-xs text-gray-400">Tanggal & Jam</div>
                    <div class="font-semibold text-gray-800">
                        {{ $event->event_date?->format('d M Y') ?? '-' }}, {{ $event->event_date?->format('H:i') ?? '08:00' }} WIB
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                    <div class="text-xs text-gray-400">Lokasi</div>
                    <div class="font-semibold text-gray-800">📍 {{ $event->location ?? 'SRK Kejawan' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Presensi Mandiri (Khusus Relawan yang Terdaftar) -->
    @if($isAssignedVolunteer)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-blue-900">Halo, {{ $currentVolunteer->user->name }}! 👋</h2>
                    <p class="text-sm text-blue-700 mt-0.5">
                        Anda terdaftar sebagai relawan pada pertemuan ini.
                        @if($myAttendance)
                            Status kehadiran Anda:
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold 
                                {{ $myAttendance->status->value === 'present' ? 'bg-emerald-100 text-emerald-800' : 
                                  ($myAttendance->status->value === 'late' ? 'bg-amber-100 text-amber-800' : 
                                  ($myAttendance->status->value === 'excused' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $myAttendance->status->label() }}
                            </span>
                        @else
                            Anda belum mencatatkan presensi kedatangan hari ini.
                        @endif
                    </p>
                </div>

                @if(!$myAttendance || $myAttendance->status->value !== 'present')
                    <form method="POST" action="{{ route('activities.self-checkin', $event) }}">
                        @csrf
                        <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm shadow transition flex items-center gap-2">
                            <span>✓</span>
                            <span>Presensi Hadir Sekarang</span>
                        </button>
                    </form>
                @else
                    <div class="text-xs text-emerald-700 font-semibold bg-emerald-100 px-3 py-1.5 rounded-lg border border-emerald-200">
                        ✓ Presensi Anda Telah Tercatat
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Lembar Presensi Seluruh Relawan (Admin & Pengurus) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-bold text-gray-800 text-base">Lembar Presensi Relawan Batch</h2>
                <p class="text-xs text-gray-400 mt-0.5">Pengurus dapat mengisi atau memperbarui status kehadiran seluruh relawan di bawah ini.</p>
            </div>
            <div class="text-xs text-gray-500 font-medium">
                Total Relawan: <span class="font-bold text-gray-800">{{ $volunteers->count() }} Orang</span>
            </div>
        </div>

        @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']))
            <form method="POST" action="{{ route('activities.attendance.store', $event) }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Relawan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran Batch</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($volunteers as $volunteer)
                                @php
                                    $att = $attendances->get($volunteer->id);
                                    $currentStatus = $att?->status->value ?? 'present';
                                    $currentNotes = $att?->notes ?? '';
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                                {{ strtoupper(substr($volunteer->user->name ?? 'V', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-sm text-gray-900">{{ $volunteer->user->name ?? '-' }}</div>
                                                <div class="text-xs text-gray-400">NIM: {{ $volunteer->nim }} • {{ $volunteer->jurusan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ ($volunteer->pivot->role ?? 'mentor') === 'mentor' ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($volunteer->pivot->role ?? 'Relawan') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3 text-xs">
                                            <label class="flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="attendances[{{ $volunteer->id }}][status]" value="present"
                                                       {{ $currentStatus === 'present' ? 'checked' : '' }}
                                                       class="text-emerald-600 focus:ring-emerald-500">
                                                <span class="text-emerald-700 font-medium">Hadir</span>
                                            </label>
                                            <label class="flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="attendances[{{ $volunteer->id }}][status]" value="late"
                                                       {{ $currentStatus === 'late' ? 'checked' : '' }}
                                                       class="text-amber-600 focus:ring-amber-500">
                                                <span class="text-amber-700 font-medium">Terlambat</span>
                                            </label>
                                            <label class="flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="attendances[{{ $volunteer->id }}][status]" value="excused"
                                                       {{ $currentStatus === 'excused' ? 'checked' : '' }}
                                                       class="text-blue-600 focus:ring-blue-500">
                                                <span class="text-blue-700 font-medium">Izin</span>
                                            </label>
                                            <label class="flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="attendances[{{ $volunteer->id }}][status]" value="absent"
                                                       {{ $currentStatus === 'absent' ? 'checked' : '' }}
                                                       class="text-red-600 focus:ring-red-500">
                                                <span class="text-red-700 font-medium">Alpa</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text"
                                               name="attendances[{{ $volunteer->id }}][notes]"
                                               value="{{ $currentNotes }}"
                                               placeholder="Opsional (cth: izin sakit, telat 15 mnt)"
                                               class="w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">
                                        Tidak ada relawan yang terdaftar di batch kegiatan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($volunteers->isNotEmpty())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg text-sm shadow transition">
                            Simpan Seluruh Presensi
                        </button>
                    </div>
                @endif
            </form>
        @else
            <!-- Tampilan Read-only untuk Volunteer biasa -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Relawan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($volunteers as $volunteer)
                            @php
                                $att = $attendances->get($volunteer->id);
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $volunteer->user->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ ucfirst($volunteer->pivot->role ?? 'Relawan') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($att)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                            {{ $att->status->value === 'present' ? 'bg-emerald-100 text-emerald-800' :
                                              ($att->status->value === 'late' ? 'bg-amber-100 text-amber-800' :
                                              ($att->status->value === 'excused' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ $att->status->label() }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $att?->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400">
                                    Tidak ada relawan di batch ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layouts.app>
